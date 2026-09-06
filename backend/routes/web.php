<?php

use App\Http\Controllers\AdminAttendanceController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminExpenseController;
use App\Http\Controllers\AdminInventoryController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminSettingController;
use App\Http\Controllers\AdminTableController;
use App\Http\Controllers\KdsController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\WebAuthController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Warkop Dadang Ecosystem
|--------------------------------------------------------------------------
*/

// Landing Page & Self Order
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/self-order', [LandingController::class, 'selfOrder'])->name('self-order');

// Authentication
Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [WebAuthController::class, 'login']);
Route::post('/logout', [WebAuthController::class, 'logout'])->name('logout');

// POS Kasir (Cashier / Manager / Owner)
Route::middleware(['auth'])->group(function () {
    // POS Kasir (Cashier / Manager / Owner)
    Route::middleware(['role:owner,manager,cashier'])->group(function () {
        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/checkout', [PosController::class, 'checkout'])->name('pos.checkout');
        Route::get('/pos/receipt/{id}', [PosController::class, 'receipt'])->name('pos.receipt');
    });

    // Kitchen Display System (KDS - Barista / Dapur)
    Route::middleware(['role:owner,manager,barista'])->group(function () {
        Route::get('/kds', [KdsController::class, 'index'])->name('kds.index');
        Route::get('/kds/api/orders', [KdsController::class, 'getOrdersApi'])->name('kds.api.orders');
        Route::post('/kds/orders/{id}/status', [KdsController::class, 'updateOrderStatus'])->name('kds.order.status');
        Route::post('/kds/items/{id}/status', [KdsController::class, 'updateItemStatus'])->name('kds.item.status');
    });

    // Admin Panel (Manager / Owner)
    Route::prefix('admin')->name('admin.')->middleware(['role:owner,manager'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])->name('products.index');
        Route::post('/products', [AdminProductController::class, 'store'])->name('products.store');
        Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('products.destroy');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

        // Attendance
        Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');

        // Expenses
        Route::get('/expenses', [AdminExpenseController::class, 'index'])->name('expenses.index');
        Route::post('/expenses', [AdminExpenseController::class, 'store'])->name('expenses.store');
        Route::post('/expenses/{id}/approve', [AdminExpenseController::class, 'approve'])->name('expenses.approve');
        Route::post('/expenses/{id}/reject', [AdminExpenseController::class, 'reject'])->name('expenses.reject');

        // Tables & QR
        Route::get('/tables', [AdminTableController::class, 'index'])->name('tables.index');
        Route::post('/tables', [AdminTableController::class, 'store'])->name('tables.store');
        Route::put('/tables/{id}/status', [AdminTableController::class, 'updateStatus'])->name('tables.update-status');
        Route::delete('/tables/{id}', [AdminTableController::class, 'destroy'])->name('tables.destroy');

        // Inventory
        Route::get('/inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory', [AdminInventoryController::class, 'store'])->name('inventory.store');
        Route::post('/inventory/{id}/adjust', [AdminInventoryController::class, 'adjustStock'])->name('inventory.adjust');

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
