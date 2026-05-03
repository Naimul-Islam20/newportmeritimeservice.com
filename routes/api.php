<?php

use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('throttle:10,1')->group(function (): void {
    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/contact/submit', [ContactController::class, 'submitMessage']);
    Route::post('/contact-messages', [ContactController::class, 'store']);
});
