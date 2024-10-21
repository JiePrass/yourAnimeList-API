<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AnimeController;

Route::get('/animes', [AnimeController::class, 'index']);
Route::post('/animes', [AnimeController::class, 'store']);
Route::get('/animes/{id}', [AnimeController::class, 'show']);
Route::put('/animes/{id}', [AnimeController::class, 'update']);
Route::delete('/animes/{id}', [AnimeController::class, 'destroy']);

