<?php

use App\Http\Controllers\Api\Admin\AdminOrganizationController;
use App\Http\Controllers\Api\Admin\AdminUserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Manager\ManagerOrganizationController;
use App\Http\Controllers\Api\Manager\ManagerUserController;
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
        Route::resource('users', AdminUserController::class)
            ->only(['index', 'store', 'update', 'destroy']);

        Route::get('/users/form-infos', [AdminUserController::class, 'formInfos'])->name('users.form-infos');
        Route::get('/users/{user}', [AdminUserController::class, 'getUser'])->name('users.get-user');

        // Organization Routes
        Route::resource('organizations', AdminOrganizationController::class)
            ->only(['index', 'store', 'update', 'destroy', 'show']);

        Route::post('/organizations/{organization}/associate-users', [AdminOrganizationController::class, 'associateUsersToOrganization'])
            ->name('organizations.associate-users');

        Route::post('/organizations/{organization}/disassociate-users', [AdminOrganizationController::class, 'disassociateUsersToOrganization'])
            ->name('organizations.disassociate-users');

        Route::get('/organizations/{organization}/get-users-by-role/{role}', [AdminOrganizationController::class, 'getOrganizationUsersListByRole'])
            ->name('organizations.get-users-by-role');

        Route::get('/organizations/get-users-by-role-that-not-belong-to-organization/{organization}',
            [AdminOrganizationController::class, 'getUsersListByRoleThatNotBelongToOrganization'])
            ->name('organizations.get-users-by-role-that-not-belong-to-organization');
    });

// Manager routes
Route::middleware(['auth:sanctum', 'only_manager_user'])
    ->prefix('manager')
    ->name('manager.')
    ->group(function () {

        // Organization Routes
        Route::resource('organizations', AdminOrganizationController::class)
            ->only(['update', 'show']);

        Route::get('/organizations/{organization}/get-users-by-role/{role}', [ManagerOrganizationController::class, 'getOrganizationUsersListByRole'])
            ->name('organizations.get-users-by-role');

        // User Routes
        Route::resource('users/{organization}', ManagerUserController::class)
            ->only(['index', 'store'])
            ->names([
                'index' => 'users.index',
                'store' => 'users.store',
            ]);

        Route::put('/users/{user}', [ManagerUserController::class, 'update'])->name('users.update');
        Route::delete('/users/disassociate-user-from-organization/{user}/{organization}', [ManagerUserController::class, 'disassociateUserFromOrganization'])->name('users.disassociate-user-from-organization');
        Route::get('/users/show/{user}', [ManagerUserController::class, 'getUser'])->name('users.get-user');
    });
