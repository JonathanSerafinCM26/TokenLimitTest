<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiTokenTestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-token-limit', [GeminiTokenTestController::class, 'testTokenLimit']);
