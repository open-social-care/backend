<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
Route::post('password/email', ForgotPasswordController::class)->name('password.send-email');
Route::post('password/reset', ResetPasswordController::class)->name('password.reset');

Route::middleware(['auth:sanctum'])
    ->group(function () {
        // Auth routes
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
