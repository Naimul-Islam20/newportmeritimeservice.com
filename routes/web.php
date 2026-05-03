<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExpertSessionController as AdminExpertSessionController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\NewsletterCategoryController;
use App\Http\Controllers\Admin\QuoteRequestController as AdminQuoteRequestController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ContactFormController;
use App\Http\Controllers\ExpertSessionController;
use App\Http\Controllers\QuoteRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/get-a-quote', [QuoteRequestController::class, 'create'])->name('quote.create');
Route::post('/get-a-quote', [QuoteRequestController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('quote.store');

Route::get('/contact', [ContactFormController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactFormController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

Route::get('/free-expert-session', [ExpertSessionController::class, 'create'])->name('expert-session.create');
Route::post('/free-expert-session', [ExpertSessionController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('expert-session.store');

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

        Route::resource('expert-sessions', AdminExpertSessionController::class)
            ->only(['index', 'show', 'destroy'])
            ->names('admin.expert-sessions');

        Route::resource('newsletters', NewsletterController::class)
            ->names('admin.newsletters');

        Route::resource('newsletter-categories', NewsletterCategoryController::class)
            ->except(['show'])
            ->names('admin.newsletter-categories');
    });
});
