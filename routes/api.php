<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
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
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(['auth:sanctum']);
Route::post('password/email', ForgotPasswordController::class)->name('password.send-email');
Route::post('password/reset', ResetPasswordController::class)->name('password.reset');

Route::middleware(['auth:sanctum', 'only_admin_user'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', \App\Http\Controllers\Api\Admin\UserController::class);
    });
