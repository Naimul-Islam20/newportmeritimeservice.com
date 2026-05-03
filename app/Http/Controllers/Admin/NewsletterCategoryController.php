<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreNewsletterCategoryRequest;
use App\Http\Requests\Admin\UpdateNewsletterCategoryRequest;
use App\Models\NewsletterCategory;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterCategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', NewsletterCategory::class);

        return view('admin.newsletter-categories.index', [
            'categories' => NewsletterCategory::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', NewsletterCategory::class);

        return view('admin.newsletter-categories.create');
    }

    public function store(StoreNewsletterCategoryRequest $request): RedirectResponse
    {
        $category = NewsletterCategory::create($request->safe()->only(['name']));

        AuditLogger::log('admin.newsletter_category.created', $category, [], $request);

        return redirect()->route('admin.newsletter-categories.index')->with('status', 'Newsletter category created successfully.');
    }

    public function edit(NewsletterCategory $newsletterCategory): View
    {
        $this->authorize('update', $newsletterCategory);

        return view('admin.newsletter-categories.edit', [
            'category' => $newsletterCategory,
        ]);
    }

    public function update(UpdateNewsletterCategoryRequest $request, NewsletterCategory $newsletterCategory): RedirectResponse
    {
        $this->authorize('update', $newsletterCategory);

        $newsletterCategory->fill($request->safe()->only(['name']));

        if (! $newsletterCategory->isDirty()) {
            return redirect()->route('admin.newsletter-categories.edit', $newsletterCategory)->with('warning', 'No changes found. Category was not updated.');
        }

        $newsletterCategory->save();

        AuditLogger::log('admin.newsletter_category.updated', $newsletterCategory, [], $request);

        return redirect()->route('admin.newsletter-categories.index')->with('status', 'Newsletter category updated successfully.');
    }

    public function destroy(NewsletterCategory $newsletterCategory): RedirectResponse
    {
        $this->authorize('delete', $newsletterCategory);

        if ($newsletterCategory->newsletters()->exists()) {
            return redirect()->route('admin.newsletter-categories.index')->with('warning', 'This category is used in newsletter items and cannot be deleted.');
        }

        $newsletterCategory->delete();

        AuditLogger::log('admin.newsletter_category.deleted', $newsletterCategory);

        return redirect()->route('admin.newsletter-categories.index')->with('status', 'Newsletter category deleted successfully.');
    }
}
