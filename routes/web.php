<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/system', [AdminController::class, 'system'])->name('system');
    
    // Tenant management
    Route::resource('tenants', TenantController::class);

    // Access (RBAC) management
    Route::prefix('access')->name('access.')->group(function () {
        Route::get('/', [AccessController::class, 'dashboard'])->name('dashboard');
        Route::get('/roles', [AccessController::class, 'roles'])->name('roles');
        Route::get('/users', [AccessController::class, 'users'])->name('users');
        Route::post('/users/{user}/assign-role', [AccessController::class, 'assignRole'])->name('assign-role');
        Route::delete('/users/{user}/revoke-role', [AccessController::class, 'revokeRole'])->name('revoke-role');
    });
});
