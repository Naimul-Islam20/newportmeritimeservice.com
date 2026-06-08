<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HonorableClient;
use App\Models\HonorableClientPage;
use App\Models\SiteDetail;
use Illuminate\View\View;

class HonorableClientController extends Controller
{
    public function index(): View
    {
        $page = HonorableClientPage::singleton();

        abort_unless($page->is_active, 404);

        return view('site.pages.honorable-clients', [
            'title' => SiteDetail::pageTitle($page->hero_title),
            'metaDescription' => $page->meta_description,
            'page' => $page,
            'clients' => HonorableClient::forPublicPage(),
        ]);
    }
}
