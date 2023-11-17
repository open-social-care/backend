<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;

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
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('reset-password');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('forgot-password');

Route::middleware(['auth:sanctum'])
    ->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
