<?php

use App\Http\Controllers\Api\Admin\OrganizationController;
use App\Http\Controllers\Api\Admin\UserController;
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

// Admin routes
Route::middleware(['auth:sanctum', 'only_admin_user'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // User Routes
        Route::resource('users', UserController::class)
            ->except(['create', 'edit', 'show']);

        Route::get('/users/form-infos', [UserController::class, 'formInfos'])->name('users.form-infos');
        Route::get('/users/{user}', [UserController::class, 'getUser'])->name('users.get-user');

        // Organization Routes
        Route::resource('organizations', OrganizationController::class)
            ->except(['create', 'edit', 'show']);

        Route::post('/organizations/{organization}/associate-users', [OrganizationController::class, 'associateUsersToOrganization'])
            ->name('organizations.associate-users');

        Route::get('/organizations/{organization}/get-users-by-role/{role}', [OrganizationController::class, 'getOrganizationUsersListByRole'])
            ->name('organizations.get-users-by-role');
    });
