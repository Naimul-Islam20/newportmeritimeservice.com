<?php

use App\Http\Controllers\Admin\AboutPageController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CareerPageController;
use App\Http\Controllers\Admin\CeoMessagePageController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuPageSectionController;
use App\Http\Controllers\Admin\OurServicesSubMenuController;
use App\Http\Controllers\Admin\OurStoryPageController;
use App\Http\Controllers\Admin\OurTeamPageController;
use App\Http\Controllers\Admin\QuoteRequestController as AdminQuoteRequestController;
use App\Http\Controllers\Admin\ServicePageController;
use App\Http\Controllers\Admin\ServiceSidebarController;
use App\Http\Controllers\Admin\SiteDetailController;
use App\Http\Controllers\Admin\AwardSubMenuController;
use App\Http\Controllers\Admin\ShipSupplySubMenuController;
use App\Http\Controllers\Admin\SubMenuController;
use App\Http\Controllers\Admin\WhereWeAreLocationController;
use App\Http\Controllers\Admin\WhoWeAreSubMenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\BlogCommentController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\MenuPageController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\QualityCertificateController;
use App\Http\Controllers\Site\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect default /login to admin login (no separate user login route exists).
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Common typo fallback: redirect /adimn/* -> /admin/*
Route::any('/adimn/{any?}', function (?string $any = null) {
    $path = $any ? "/admin/{$any}" : '/admin';

    return redirect($path);
})->where('any', '.*');

Route::get('/ship-supply', [PageController::class, 'shipSupply'])->name('ship-supply');
Route::get('/technical-stores', [PageController::class, 'technicalStores'])->name('technical-stores');
Route::get('/our-services', [PageController::class, 'ourServices'])->name('our-services');
Route::get('/our-services/transit-delivery', [PageController::class, 'serviceTransitDelivery'])->name('service.transit-delivery');
Route::get('/our-services/port-delivery', [PageController::class, 'servicePortDelivery'])->name('service.port-delivery');
Route::get('/our-services/operations-logistics', [PageController::class, 'serviceOperationsLogistics'])->name('service.operations-logistics');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/our-story', [PageController::class, 'ourStory'])->name('our-story');
Route::get('/message-from-ceo', [PageController::class, 'messageFromCeo'])->name('message-from-ceo');
Route::get('/our-team-management', [PageController::class, 'ourTeamManagement'])->name('our-team-management');
Route::get('/career', [PageController::class, 'career'])->name('career');
Route::get('/where-we-are', [PageController::class, 'whereWeAre'])->name('where-we-are');
Route::get('/where-we-are/{location}/ports/{port}', [PageController::class, 'whereWeArePort'])->name('where-we-are.port');
Route::get('/where-we-are/{slug}', [PageController::class, 'whereWeAreLocation'])->name('where-we-are.location');
Route::get('/locations', [PageController::class, 'locations'])->name('locations');
Route::get('/quality-certificates-memberships', [QualityCertificateController::class, 'index'])->name('quality-certificates');
Route::get('/award', [PageController::class, 'award'])->name('award');
Route::get('/get-a-quote', [QuoteRequestController::class, 'create'])->name('quote.request');
Route::post('/get-a-quote', [QuoteRequestController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('quote.store');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::post('/blog/comments/{sub_menu}', [BlogCommentController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('blog.comments.store');

// Dynamic menu/sub-menu pages (must stay after all explicit site routes above).
Route::get('/{any}', [MenuPageController::class, 'show'])
    ->where('any', '^(?!admin($|/)).+');

Route::prefix('admin')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AuthController::class, 'login'])->name('admin.login.store');
    });

    Route::middleware(['auth', 'admin.access'])->group(function (): void {
        Route::get('/', DashboardController::class)->name('admin.dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        Route::resource('users', UserController::class)
            ->except(['show'])
            ->names('admin.users');

        Route::resource('contact-messages', ContactMessageController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.contact-messages');

        Route::resource('quote-requests', AdminQuoteRequestController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.quote-requests');

        Route::resource('menus', MenuController::class)
            ->except(['show'])
            ->names('admin.menus');

        Route::patch('menus/{menu}/toggle-active', [MenuController::class, 'toggleActive'])
            ->name('admin.menus.toggle-active');

        Route::get('menus/{menu}/page-sections', [MenuPageSectionController::class, 'indexMenu'])
            ->name('admin.menus.page-sections.index');
        Route::get('menus/{menu}/page-sections/create', [MenuPageSectionController::class, 'createMenu'])
            ->name('admin.menus.page-sections.create');
        Route::post('menus/{menu}/page-sections', [MenuPageSectionController::class, 'storeMenu'])
            ->name('admin.menus.page-sections.store');
        Route::get('menus/{menu}/page-sections/{section}/edit', [MenuPageSectionController::class, 'editMenu'])
            ->name('admin.menus.page-sections.edit');
        Route::put('menus/{menu}/page-sections/{section}', [MenuPageSectionController::class, 'updateMenu'])
            ->name('admin.menus.page-sections.update');
        Route::delete('menus/{menu}/page-sections/{section}', [MenuPageSectionController::class, 'destroyMenu'])
            ->name('admin.menus.page-sections.destroy');

        Route::resource('sub-menus', SubMenuController::class)
            ->except(['show'])
            ->names('admin.sub-menus');

        Route::get('sub-menus/{sub_menu}/manage', [SubMenuController::class, 'manageCategory'])
            ->name('admin.sub-menus.manage');

        Route::get('who-we-are/sub-menus', [WhoWeAreSubMenuController::class, 'index'])
            ->name('admin.who-we-are-sub-menus.index');
        Route::get('who-we-are/sub-menus/create', [WhoWeAreSubMenuController::class, 'create'])
            ->name('admin.who-we-are-sub-menus.create');
        Route::post('who-we-are/sub-menus', [WhoWeAreSubMenuController::class, 'store'])
            ->name('admin.who-we-are-sub-menus.store');
        Route::get('who-we-are/sub-menus/{sub_menu}/edit', [WhoWeAreSubMenuController::class, 'edit'])
            ->name('admin.who-we-are-sub-menus.edit');
        Route::put('who-we-are/sub-menus/{sub_menu}', [WhoWeAreSubMenuController::class, 'update'])
            ->name('admin.who-we-are-sub-menus.update');
        Route::patch('who-we-are/sub-menus/{sub_menu}/toggle-active', [WhoWeAreSubMenuController::class, 'toggleActive'])
            ->name('admin.who-we-are-sub-menus.toggle-active');
        Route::delete('who-we-are/sub-menus/{sub_menu}', [WhoWeAreSubMenuController::class, 'destroy'])
            ->name('admin.who-we-are-sub-menus.destroy');

        Route::get('ship-supply/sub-menus', [ShipSupplySubMenuController::class, 'index'])
            ->name('admin.ship-supply-sub-menus.index');
        Route::get('ship-supply/sub-menus/create', [ShipSupplySubMenuController::class, 'create'])
            ->name('admin.ship-supply-sub-menus.create');
        Route::post('ship-supply/sub-menus', [ShipSupplySubMenuController::class, 'store'])
            ->name('admin.ship-supply-sub-menus.store');
        Route::get('ship-supply/sub-menus/{sub_menu}/edit', [ShipSupplySubMenuController::class, 'edit'])
            ->name('admin.ship-supply-sub-menus.edit');
        Route::put('ship-supply/sub-menus/{sub_menu}', [ShipSupplySubMenuController::class, 'update'])
            ->name('admin.ship-supply-sub-menus.update');
        Route::patch('ship-supply/sub-menus/{sub_menu}/toggle-active', [ShipSupplySubMenuController::class, 'toggleActive'])
            ->name('admin.ship-supply-sub-menus.toggle-active');
        Route::delete('ship-supply/sub-menus/{sub_menu}', [ShipSupplySubMenuController::class, 'destroy'])
            ->name('admin.ship-supply-sub-menus.destroy');

        Route::get('our-services/sub-menus', [OurServicesSubMenuController::class, 'index'])
            ->name('admin.our-services-sub-menus.index');
        Route::get('our-services/sub-menus/create', [OurServicesSubMenuController::class, 'create'])
            ->name('admin.our-services-sub-menus.create');
        Route::post('our-services/sub-menus', [OurServicesSubMenuController::class, 'store'])
            ->name('admin.our-services-sub-menus.store');
        Route::get('our-services/sub-menus/{sub_menu}/edit', [OurServicesSubMenuController::class, 'edit'])
            ->name('admin.our-services-sub-menus.edit');
        Route::put('our-services/sub-menus/{sub_menu}', [OurServicesSubMenuController::class, 'update'])
            ->name('admin.our-services-sub-menus.update');
        Route::patch('our-services/sub-menus/{sub_menu}/toggle-active', [OurServicesSubMenuController::class, 'toggleActive'])
            ->name('admin.our-services-sub-menus.toggle-active');
        Route::delete('our-services/sub-menus/{sub_menu}', [OurServicesSubMenuController::class, 'destroy'])
            ->name('admin.our-services-sub-menus.destroy');

        Route::get('award/sub-menus', [AwardSubMenuController::class, 'index'])
            ->name('admin.award-sub-menus.index');
        Route::get('award/sub-menus/create', [AwardSubMenuController::class, 'create'])
            ->name('admin.award-sub-menus.create');
        Route::post('award/sub-menus', [AwardSubMenuController::class, 'store'])
            ->name('admin.award-sub-menus.store');
        Route::get('award/sub-menus/{sub_menu}/edit', [AwardSubMenuController::class, 'edit'])
            ->name('admin.award-sub-menus.edit');
        Route::put('award/sub-menus/{sub_menu}', [AwardSubMenuController::class, 'update'])
            ->name('admin.award-sub-menus.update');
        Route::patch('award/sub-menus/{sub_menu}/toggle-active', [AwardSubMenuController::class, 'toggleActive'])
            ->name('admin.award-sub-menus.toggle-active');
        Route::delete('award/sub-menus/{sub_menu}', [AwardSubMenuController::class, 'destroy'])
            ->name('admin.award-sub-menus.destroy');

        Route::get('sub-menus/{sub_menu}/page-sections', [MenuPageSectionController::class, 'indexSubMenu'])
            ->name('admin.sub-menus.page-sections.index');
        Route::get('sub-menus/{sub_menu}/page-sections/create', [MenuPageSectionController::class, 'createSubMenu'])
            ->name('admin.sub-menus.page-sections.create');
        Route::post('sub-menus/{sub_menu}/page-sections', [MenuPageSectionController::class, 'storeSubMenu'])
            ->name('admin.sub-menus.page-sections.store');
        Route::get('sub-menus/{sub_menu}/page-sections/{section}/edit', [MenuPageSectionController::class, 'editSubMenu'])
            ->name('admin.sub-menus.page-sections.edit');
        Route::put('sub-menus/{sub_menu}/page-sections/{section}', [MenuPageSectionController::class, 'updateSubMenu'])
            ->name('admin.sub-menus.page-sections.update');
        Route::delete('sub-menus/{sub_menu}/page-sections/{section}', [MenuPageSectionController::class, 'destroySubMenu'])
            ->name('admin.sub-menus.page-sections.destroy');

        Route::resource('hero-slides', HeroSlideController::class)
            ->only(['index', 'store', 'edit', 'update', 'destroy'])
            ->names('admin.hero-slides');

        Route::get('site-details', [SiteDetailController::class, 'edit'])->name('admin.site-details.edit');
        Route::put('site-details/{site_detail}', [SiteDetailController::class, 'update'])->name('admin.site-details.update');

        Route::get('about-page', [AboutPageController::class, 'edit'])->name('admin.about-page.edit');
        Route::put('about-page/{about_page}', [AboutPageController::class, 'update'])->name('admin.about-page.update');

        Route::get('our-story-page', [OurStoryPageController::class, 'edit'])->name('admin.our-story-page.edit');
        Route::put('our-story-page/{our_story_page}', [OurStoryPageController::class, 'update'])->name('admin.our-story-page.update');
        Route::get('ceo-message-page', [CeoMessagePageController::class, 'edit'])->name('admin.ceo-message-page.edit');
        Route::put('ceo-message-page/{ceo_message_page}', [CeoMessagePageController::class, 'update'])->name('admin.ceo-message-page.update');
        Route::get('our-team-page', [OurTeamPageController::class, 'edit'])->name('admin.our-team-page.edit');
        Route::put('our-team-page/{our_team_page}', [OurTeamPageController::class, 'update'])->name('admin.our-team-page.update');
        Route::get('career-page', [CareerPageController::class, 'edit'])->name('admin.career-page.edit');
        Route::put('career-page/{career_page}', [CareerPageController::class, 'update'])->name('admin.career-page.update');

        Route::get('where-we-are-locations', [WhereWeAreLocationController::class, 'index'])->name('admin.where-we-are-locations.index');
        Route::get('where-we-are-locations/create', [WhereWeAreLocationController::class, 'create'])->name('admin.where-we-are-locations.create');
        Route::post('where-we-are-locations', [WhereWeAreLocationController::class, 'store'])->name('admin.where-we-are-locations.store');
        Route::get('where-we-are-locations/{where_we_are_location}/edit', [WhereWeAreLocationController::class, 'edit'])->name('admin.where-we-are-locations.edit');
        Route::put('where-we-are-locations/{where_we_are_location}', [WhereWeAreLocationController::class, 'update'])->name('admin.where-we-are-locations.update');
        Route::delete('where-we-are-locations/{where_we_are_location}', [WhereWeAreLocationController::class, 'destroy'])->name('admin.where-we-are-locations.destroy');
        Route::get('where-we-are-locations/{where_we_are_location}/ports/{port}/edit', [\App\Http\Controllers\Admin\WhereWeArePortController::class, 'edit'])->name('admin.where-we-are-ports.edit');
        Route::put('where-we-are-locations/{where_we_are_location}/ports/{port}', [\App\Http\Controllers\Admin\WhereWeArePortController::class, 'update'])->name('admin.where-we-are-ports.update');

        Route::get('service-pages', [ServicePageController::class, 'index'])->name('admin.service-pages.index');
        Route::get('service-pages/{service_page}/edit', [ServicePageController::class, 'edit'])->name('admin.service-pages.edit');
        Route::put('service-pages/{service_page}', [ServicePageController::class, 'update'])->name('admin.service-pages.update');
        Route::get('service-sidebar', [ServiceSidebarController::class, 'edit'])->name('admin.service-sidebar.edit');
        Route::put('service-sidebar/{service_sidebar_setting}', [ServiceSidebarController::class, 'update'])->name('admin.service-sidebar.update');

        Route::get('quality-certificates', [App\Http\Controllers\Admin\QualityCertificateController::class, 'index'])->name('admin.quality-certificates.index');
        Route::put('quality-certificates/page', [App\Http\Controllers\Admin\QualityCertificateController::class, 'updatePage'])->name('admin.quality-certificates.page.update');
        Route::post('quality-certificates/groups', [App\Http\Controllers\Admin\QualityCertificateController::class, 'storeGroup'])->name('admin.quality-certificates.groups.store');
        Route::get('quality-certificates/groups/{certificate_group}/edit', [App\Http\Controllers\Admin\QualityCertificateController::class, 'editGroup'])->name('admin.quality-certificates.groups.edit');
        Route::put('quality-certificates/groups/{certificate_group}', [App\Http\Controllers\Admin\QualityCertificateController::class, 'updateGroup'])->name('admin.quality-certificates.groups.update');
        Route::delete('quality-certificates/groups/{certificate_group}', [App\Http\Controllers\Admin\QualityCertificateController::class, 'destroyGroup'])->name('admin.quality-certificates.groups.destroy');
        Route::post('quality-certificates/groups/{certificate_group}/certificates', [App\Http\Controllers\Admin\QualityCertificateController::class, 'storeCertificate'])->name('admin.quality-certificates.groups.certificates.store');
        Route::put('quality-certificates/groups/{certificate_group}/certificates/{quality_certificate}', [App\Http\Controllers\Admin\QualityCertificateController::class, 'updateCertificate'])->name('admin.quality-certificates.groups.certificates.update');
        Route::delete('quality-certificates/groups/{certificate_group}/certificates/{quality_certificate}', [App\Http\Controllers\Admin\QualityCertificateController::class, 'destroyCertificate'])->name('admin.quality-certificates.groups.certificates.destroy');

        Route::get('about-page/{about_page}/page-sections', [MenuPageSectionController::class, 'indexAbout'])
            ->name('admin.about-page.page-sections.index');
        Route::get('about-page/{about_page}/page-sections/create', [MenuPageSectionController::class, 'createAbout'])
            ->name('admin.about-page.page-sections.create');
        Route::post('about-page/{about_page}/page-sections', [MenuPageSectionController::class, 'storeAbout'])
            ->name('admin.about-page.page-sections.store');
        Route::get('about-page/{about_page}/page-sections/{section}/edit', [MenuPageSectionController::class, 'editAbout'])
            ->name('admin.about-page.page-sections.edit');
        Route::put('about-page/{about_page}/page-sections/{section}', [MenuPageSectionController::class, 'updateAbout'])
            ->name('admin.about-page.page-sections.update');
        Route::delete('about-page/{about_page}/page-sections/{section}', [MenuPageSectionController::class, 'destroyAbout'])
            ->name('admin.about-page.page-sections.destroy');

        Route::get('home-sections', [HomeSectionController::class, 'index'])->name('admin.home-sections.index');
        Route::get('home-sections/service-area', [HomeSectionController::class, 'serviceArea'])->name('admin.home-sections.service-area');
        Route::put('home-sections/service-area', [HomeSectionController::class, 'updateServiceArea'])->name('admin.home-sections.service-area.update');
        Route::get('home-sections/visual-frames', [HomeSectionController::class, 'visualFrames'])->name('admin.home-sections.visual-frames');
        Route::put('home-sections/visual-frames', [HomeSectionController::class, 'updateVisualFrames'])->name('admin.home-sections.visual-frames.update');
        Route::get('home-sections/create', [HomeSectionController::class, 'create'])->name('admin.home-sections.create');
        Route::post('home-sections', [HomeSectionController::class, 'store'])->name('admin.home-sections.store');
        Route::get('home-sections/details', [HomeSectionController::class, 'details'])->name('admin.home-sections.details');
        Route::post('home-sections/details', [HomeSectionController::class, 'saveDetails'])->name('admin.home-sections.details.store');
        Route::get('home-sections/{home_section}/edit', [HomeSectionController::class, 'edit'])->name('admin.home-sections.edit');
        Route::put('home-sections/{home_section}', [HomeSectionController::class, 'update'])->name('admin.home-sections.update');
        Route::delete('home-sections/{home_section}', [HomeSectionController::class, 'destroy'])->name('admin.home-sections.destroy');
    });
});
