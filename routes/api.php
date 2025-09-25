<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EpresenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::post('/epresence', [EpresenceController::class, 'store']);
});
