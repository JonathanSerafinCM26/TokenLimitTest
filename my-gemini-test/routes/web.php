<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeminiTokenTestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-gemini-tokens', [GeminiTokenTestController::class, 'testTokenLimits']);
