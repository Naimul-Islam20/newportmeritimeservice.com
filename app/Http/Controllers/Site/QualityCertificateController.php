<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\CertificateGroup;
use App\Models\CertificatePage;
use App\Models\SiteDetail;
use Illuminate\View\View;

class QualityCertificateController extends Controller
{
    public function index(): View
    {
        $page = CertificatePage::singleton();

        if (! $page->is_active) {
            abort(404);
        }

        $groups = CertificateGroup::query()
            ->active()
            ->ordered()
            ->with(['activeCertificates'])
            ->get()
            ->filter(fn (CertificateGroup $group) => $group->activeCertificates->isNotEmpty());

        $heroTitle = filled($page->hero_title) ? $page->hero_title : 'Quality Certificates & Memberships';
        $meta = filled($page->meta_description)
            ? $page->meta_description
            : 'Quality certificates and industry memberships.';

        return view('site.pages.quality-certificates', [
            'title' => SiteDetail::pageTitle($heroTitle),
            'metaDescription' => $meta,
            'page' => $page,
            'groups' => $groups,
            'heroTitle' => $heroTitle,
            'pageIntro' => filled($page->page_intro) ? $page->page_intro : $heroTitle,
        ]);
    }
}
