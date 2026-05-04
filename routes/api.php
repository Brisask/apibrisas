<?php

use App\Http\Controllers\Api\TenantApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Tenant API routes
Route::prefix('tenants')->group(function () {
    Route::get('/', [TenantApiController::class, 'index']);          // GET /api/tenants
    Route::post('/', [TenantApiController::class, 'store']);         // POST /api/tenants  
    Route::get('/{tenant}', [TenantApiController::class, 'show']);   // GET /api/tenants/{id}
    Route::put('/{tenant}', [TenantApiController::class, 'update']); // PUT /api/tenants/{id}
    Route::delete('/{tenant}', [TenantApiController::class, 'destroy']); // DELETE /api/tenants/{id}
});
