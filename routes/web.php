<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSlideController;
use App\Http\Controllers\Admin\HomeSectionController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\SiteDetailController;
use App\Http\Controllers\Admin\SubMenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\MenuPageController;
use App\Http\Controllers\Site\PageController;
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
Route::get('/our-services', [PageController::class, 'ourServices'])->name('our-services');
Route::get('/about-us', [PageController::class, 'aboutUs'])->name('about-us');
Route::get('/where-we-are', [PageController::class, 'whereWeAre'])->name('where-we-are');
Route::get('/award', [PageController::class, 'award'])->name('award');
Route::get('/get-a-quote', [PageController::class, 'quote'])->name('quote.request');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

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

        Route::resource('menus', MenuController::class)
            ->except(['show'])
            ->names('admin.menus');

        Route::resource('sub-menus', SubMenuController::class)
            ->except(['show'])
            ->names('admin.sub-menus');

        Route::resource('hero-slides', HeroSlideController::class)
            ->only(['index', 'store', 'destroy'])
            ->names('admin.hero-slides');

        Route::get('site-details', [SiteDetailController::class, 'edit'])->name('admin.site-details.edit');
        Route::put('site-details/{site_detail}', [SiteDetailController::class, 'update'])->name('admin.site-details.update');

        Route::get('home-sections', [HomeSectionController::class, 'index'])->name('admin.home-sections.index');
        Route::get('home-sections/create', [HomeSectionController::class, 'create'])->name('admin.home-sections.create');
        Route::post('home-sections', [HomeSectionController::class, 'store'])->name('admin.home-sections.store');
        Route::get('home-sections/details', [HomeSectionController::class, 'details'])->name('admin.home-sections.details');
        Route::post('home-sections/details', [HomeSectionController::class, 'saveDetails'])->name('admin.home-sections.details.store');
        Route::get('home-sections/{home_section}/edit', [HomeSectionController::class, 'edit'])->name('admin.home-sections.edit');
        Route::put('home-sections/{home_section}', [HomeSectionController::class, 'update'])->name('admin.home-sections.update');
    });
});
