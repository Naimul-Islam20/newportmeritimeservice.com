<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsletterRequest;
use App\Http\Requests\Admin\UpdateNewsletterRequest;
use App\Models\Newsletter;
use App\Models\NewsletterCategory;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Newsletter::class);

        return view('admin.newsletters.index', [
            'newsletters' => Newsletter::query()->with('category')->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Newsletter::class);

        return view('admin.newsletters.create', [
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function store(StoreNewsletterRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only(['title', 'category_id', 'description']);
        $payload['published_at'] = now()->toDateString();
        $payload['image_path'] = $this->storeImage($request->file('image'));

        $newsletter = Newsletter::create($payload);

        AuditLogger::log('admin.newsletter.created', $newsletter, [], $request);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter created successfully.');
    }

    public function show(Newsletter $newsletter): View
    {
        $this->authorize('view', $newsletter);

        return view('admin.newsletters.show', ['newsletter' => $newsletter->load('category')]);
    }

    public function edit(Newsletter $newsletter): View
    {
        $this->authorize('update', $newsletter);

        return view('admin.newsletters.edit', [
            'newsletter' => $newsletter->load('category'),
            'categories' => NewsletterCategory::query()->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateNewsletterRequest $request, Newsletter $newsletter): RedirectResponse
    {
        $this->authorize('update', $newsletter);

        $payload = $request->safe()->only(['title', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $this->deleteImage($newsletter->image_path);
            $payload['image_path'] = $this->storeImage($request->file('image'));
        }

        $newsletter->fill($payload);

        if (! $newsletter->isDirty()) {
            return redirect()->route('admin.newsletters.edit', $newsletter)->with('warning', 'No changes found. Newsletter was not updated.');
        }

        $newsletter->save();

        AuditLogger::log('admin.newsletter.updated', $newsletter, [], $request);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter updated successfully.');
    }

    public function destroy(Newsletter $newsletter): RedirectResponse
    {
        $this->authorize('delete', $newsletter);

        $this->deleteImage($newsletter->image_path);
        $newsletter->delete();

        AuditLogger::log('admin.newsletter.deleted', $newsletter);

        return redirect()->route('admin.newsletters.index')->with('status', 'Newsletter deleted successfully.');
    }

    private function storeImage(?UploadedFile $image): string
    {
        if (! $image) {
            abort(422, 'Newsletter image is required.');
        }

        $directory = public_path('uploads/newsletters');
        File::ensureDirectoryExists($directory);

        $filename = Str::uuid()->toString().'.'.$image->getClientOriginalExtension();
        $image->move($directory, $filename);

        return 'uploads/newsletters/'.$filename;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $fullPath = public_path($path);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
