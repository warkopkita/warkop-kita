<?php

use App\Http\Controllers\Api\AttendanceApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LoyaltyApiController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderApiController;
use App\Http\Controllers\Api\OwnerDashboardApiController;
use App\Http\Controllers\Api\TableController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Warkop Dadang Ecosystem
|--------------------------------------------------------------------------
*/

// Public Authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Public Menu Catalog & Tables
Route::get('/categories', [MenuController::class, 'categories']);
Route::get('/products', [MenuController::class, 'products']);
Route::get('/products/{slug}', [MenuController::class, 'show']);
Route::get('/tables', [TableController::class, 'index']);
Route::get('/tables/qr/{qr_code}', [TableController::class, 'getByQr']);

// Public Order Placement & Tracking (Guest Self-Order)
Route::post('/orders/guest', [OrderApiController::class, 'store']);
Route::get('/orders/track/{order_number}', [OrderApiController::class, 'track']);

// Authenticated Routes (Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    // Current User Profile & Logout
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Customer & General Orders
    Route::get('/orders', [OrderApiController::class, 'index']);
    Route::post('/orders', [OrderApiController::class, 'store']);
    Route::get('/orders/{id}', [OrderApiController::class, 'show']);

    // Loyalty Points & Vouchers
    Route::get('/loyalty/balance', [LoyaltyApiController::class, 'balance']);
    Route::post('/loyalty/redeem', [LoyaltyApiController::class, 'redeem']);

    // Staff Attendance (Karyawan/Barista/Kasir/Manager/Owner)
    Route::middleware(['role:owner,manager,cashier,barista,waiter'])->group(function () {
        Route::get('/attendance/today', [AttendanceApiController::class, 'today']);
        Route::post('/attendance/clock-in', [AttendanceApiController::class, 'clockIn']);
        Route::post('/attendance/clock-out', [AttendanceApiController::class, 'clockOut']);
        Route::get('/attendance/history', [AttendanceApiController::class, 'history']);
    });

    // Staff & Kitchen Order Management
    Route::put('/orders/{id}/status', [OrderApiController::class, 'updateStatus']);

    // Owner Monitoring & Approvals
    Route::prefix('owner')->middleware(['role:owner,manager'])->group(function () {
        Route::get('/summary', [OwnerDashboardApiController::class, 'summary']);
        Route::get('/expenses', [OwnerDashboardApiController::class, 'expenses']);
        Route::post('/expenses/{id}/approve', [OwnerDashboardApiController::class, 'approveExpense']);
    });
});
