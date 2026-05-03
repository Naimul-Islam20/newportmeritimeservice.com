<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/ship-supply', [PageController::class, 'shipSupply'])->name('ship-supply');
Route::get('/our-services', [PageController::class, 'ourServices'])->name('our-services');
Route::get('/award', [PageController::class, 'award'])->name('award');
Route::get('/get-a-quote', [PageController::class, 'quote'])->name('quote.request');

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('contact.store');

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
    });
});
