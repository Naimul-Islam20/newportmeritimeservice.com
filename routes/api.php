<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ExpertSessionController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\QuoteRequestController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:30,1')->group(function (): void {
    Route::get('/newsletters', [NewsletterController::class, 'index']);
    Route::get('/newsletters/{newsletter}', [NewsletterController::class, 'show']);
});

Route::middleware('throttle:10,1')->group(function (): void {
    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/contact/submit', [ContactController::class, 'submitMessage']);
    Route::post('/contact-messages', [ContactController::class, 'store']);
    Route::post('/expert-sessions', [ExpertSessionController::class, 'store']);
    Route::post('/quote-requests', [QuoteRequestController::class, 'store']);
});
