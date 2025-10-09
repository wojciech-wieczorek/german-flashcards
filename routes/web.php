<?php

use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\ReviewController;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::controller(FlashcardController::class)->group(function () {
    Route::get('/flashcards', 'index');
    Route::get('/flashcards/new', 'create');
    Route::post('/flashcards', 'store')->middleware([HandlePrecognitiveRequests::class]);
    Route::get('/flashcards/{index}', 'show');
    Route::get('/flashcards/{index}/edit', 'edit');
    Route::patch('/flashcards/{index}', 'update')->middleware([HandlePrecognitiveRequests::class]);
    Route::delete('/flashcards/{index}', 'destroy');
});

Route::controller(ReviewController::class)->group(function () {
    Route::get('/boxes', 'index');
    Route::get('/boxes/{box}', 'show');
});
