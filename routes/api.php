<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Auth routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/reset-password', [NewPasswordController::class, 'store']);
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);

Route::middleware(['auth:sanctum'])
    ->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout']);
    });
