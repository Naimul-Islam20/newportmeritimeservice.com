<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCertificateGroupRequest;
use App\Http\Requests\Admin\StoreQualityCertificateRequest;
use App\Http\Requests\Admin\UpdateCertificateGroupRequest;
use App\Http\Requests\Admin\UpdateCertificatePageRequest;
use App\Http\Requests\Admin\UpdateQualityCertificateRequest;
use App\Models\CertificateGroup;
use App\Models\CertificatePage;
use App\Models\QualityCertificate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class QualityCertificateController extends Controller
{
    public function index(): View
    {
        $page = CertificatePage::singleton();
        $this->authorize('viewAny', $page);

        $groups = CertificateGroup::query()
            ->ordered()
            ->with(['certificates' => fn ($q) => $q->ordered()])
            ->get();

        $nextGroupSort = ((int) (CertificateGroup::query()->max('sort_order') ?? 0)) + 1;

        return view('admin.quality-certificates.index', [
            'page' => $page,
            'groups' => $groups,
            'nextGroupSort' => $nextGroupSort,
        ]);
    }

    public function updatePage(UpdateCertificatePageRequest $request): RedirectResponse
    {
        $page = CertificatePage::singleton();
        $this->authorize('update', $page);

        $prevHero = $page->hero_background;

        $data = $request->validated();
        unset($data['hero_background_file'], $data['remove_hero_background']);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('hero_background_file')) {
            $data['hero_background'] = $request->file('hero_background_file')->store('certificate-pages/hero', 'public_site');
        } elseif ($request->boolean('remove_hero_background')) {
            $data['hero_background'] = null;
        }

        $page->fill($data);
        if ($page->hero_background !== $prevHero && CertificatePage::isManagedUploadPath($prevHero)) {
            CertificatePage::deleteManagedUpload($prevHero);
        }
        $page->save();

        return redirect()
            ->route('admin.quality-certificates.index')
            ->with('status', 'Certificates page settings saved.');
    }

    public function storeGroup(StoreCertificateGroupRequest $request): RedirectResponse
    {
        $this->authorize('create', CertificateGroup::class);

        $data = $request->validated();
        $slug = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : CertificateGroup::uniqueSlug($data['title']);

        CertificateGroup::create([
            'title' => $data['title'],
            'slug' => $slug,
            'intro' => $data['intro'] ?? null,
            'layout' => $data['layout'],
            'sort_order' => (int) ($data['sort_order'] ?? ((int) (CertificateGroup::query()->max('sort_order') ?? 0) + 1)),
            'show_divider_before' => $request->boolean('show_divider_before'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.quality-certificates.index')
            ->with('status', 'Section added.');
    }

    public function editGroup(CertificateGroup $certificate_group): View
    {
        $this->authorize('update', $certificate_group);

        $certificate_group->load(['certificates' => fn ($q) => $q->ordered()]);
        $nextCertSort = ((int) ($certificate_group->certificates()->max('sort_order') ?? 0)) + 1;

        return view('admin.quality-certificates.edit-group', [
            'group' => $certificate_group,
            'nextCertSort' => $nextCertSort,
        ]);
    }

    public function updateGroup(UpdateCertificateGroupRequest $request, CertificateGroup $certificate_group): RedirectResponse
    {
        $data = $request->validated();
        $slug = filled($data['slug'] ?? null)
            ? Str::slug($data['slug'])
            : CertificateGroup::uniqueSlug($data['title'], $certificate_group->id);

        $certificate_group->update([
            'title' => $data['title'],
            'slug' => $slug,
            'intro' => $data['intro'] ?? null,
            'layout' => $data['layout'],
            'sort_order' => (int) ($data['sort_order'] ?? $certificate_group->sort_order),
            'show_divider_before' => $request->boolean('show_divider_before'),
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.quality-certificates.groups.edit', $certificate_group)
            ->with('status', 'Section updated.');
    }

    public function destroyGroup(CertificateGroup $certificate_group): RedirectResponse
    {
        $this->authorize('delete', $certificate_group);

        foreach ($certificate_group->certificates as $cert) {
            $this->deleteCertificateFiles($cert);
        }
        $certificate_group->delete();

        return redirect()
            ->route('admin.quality-certificates.index')
            ->with('status', 'Section removed.');
    }

    public function storeCertificate(StoreQualityCertificateRequest $request, CertificateGroup $certificate_group): RedirectResponse
    {
        $data = $request->validated();
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('quality-certificates/images', 'public_site')
            : null;
        $pdfPath = $request->file('pdf')->store('quality-certificates/pdfs', 'public_site');

        QualityCertificate::create([
            'certificate_group_id' => $certificate_group->id,
            'title' => $data['title'],
            'image_path' => $imagePath,
            'pdf_path' => $pdfPath,
            'sort_order' => (int) ($data['sort_order'] ?? ((int) ($certificate_group->certificates()->max('sort_order') ?? 0) + 1)),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.quality-certificates.groups.edit', $certificate_group)
            ->with('status', 'Certificate added.');
    }

    public function updateCertificate(UpdateQualityCertificateRequest $request, CertificateGroup $certificate_group, QualityCertificate $quality_certificate): RedirectResponse
    {
        if ((int) $quality_certificate->certificate_group_id !== (int) $certificate_group->id) {
            abort(404);
        }

        $data = $request->validated();
        $prevImage = $quality_certificate->image_path;
        $prevPdf = $quality_certificate->pdf_path;

        $payload = [
            'title' => $data['title'],
            'sort_order' => (int) ($data['sort_order'] ?? $quality_certificate->sort_order),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('quality-certificates/images', 'public_site');
        }

        if ($request->hasFile('pdf')) {
            $payload['pdf_path'] = $request->file('pdf')->store('quality-certificates/pdfs', 'public_site');
        }

        $quality_certificate->update($payload);

        if (isset($payload['image_path']) && $prevImage !== $payload['image_path']) {
            QualityCertificate::deleteManagedUpload($prevImage);
        }
        if (isset($payload['pdf_path']) && $prevPdf !== $payload['pdf_path']) {
            QualityCertificate::deleteManagedUpload($prevPdf);
        }

        return redirect()
            ->route('admin.quality-certificates.groups.edit', $certificate_group)
            ->with('status', 'Certificate updated.');
    }

    public function destroyCertificate(CertificateGroup $certificate_group, QualityCertificate $quality_certificate): RedirectResponse
    {
        if ((int) $quality_certificate->certificate_group_id !== (int) $certificate_group->id) {
            abort(404);
        }

        $this->authorize('delete', $quality_certificate);
        $this->deleteCertificateFiles($quality_certificate);
        $quality_certificate->delete();

        return redirect()
            ->route('admin.quality-certificates.groups.edit', $certificate_group)
            ->with('status', 'Certificate removed.');
    }

    private function deleteCertificateFiles(QualityCertificate $cert): void
    {
        QualityCertificate::deleteManagedUpload($cert->image_path);
        QualityCertificate::deleteManagedUpload($cert->pdf_path);
    }
}
