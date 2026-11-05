<?php

use App\Http\Controllers\SnippetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SnippetController::class, 'index'])->name('home');

Route::post('/snippets', [SnippetController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('snippets.store');

Route::get('/s/{slug}', [SnippetController::class, 'show'])->name('snippets.show');
Route::get('/s/{slug}/raw', [SnippetController::class, 'raw'])->name('snippets.raw');
