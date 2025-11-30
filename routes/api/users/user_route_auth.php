<?php

use App\Http\Controllers\Backend\auth\AuthController;
use App\Http\Controllers\Backend\cart\CartController;
use App\Http\Controllers\Backend\category\CategoryController;
<<<<<<< HEAD
use App\Http\Controllers\backend\orders\OrderController;
=======
use App\Http\Controllers\Backend\dashboard\DashboardController;
>>>>>>> 2a0322896c28ac6098a4041156f9ba15ca0a26bc
use App\Http\Controllers\Backend\orders\OrderListController;
use App\Http\Controllers\Backend\orders\PaymentController;
use App\Http\Controllers\Backend\product\ProductController;
use App\Http\Controllers\Backend\report\ReportController;
use App\Http\Controllers\Backend\table\TableNumberController;
use App\Http\Controllers\Backend\user\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->name('refresh');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
});

// Admin-only routes
Route::prefix('admin')->middleware('role:admin')->group(function () {

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/statistics', [UserController::class, 'statistics'])->name('statistics');
        Route::get('/active', [UserController::class, 'activeUsers'])->name('active');
        Route::get('/admins', [UserController::class, 'admins'])->name('admins');
        Route::get('/cashiers', [UserController::class, 'cashiers'])->name('cashiers');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::post('/cashier', [UserController::class, 'cashier'])->name('create.cashier');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle.status');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
    });

    // Tables
    Route::prefix('tables')->group(function () {
        Route::get('/', [TableNumberController::class, 'index']);
        Route::get('/{id}', [TableNumberController::class, 'show']);
        Route::post('/', [TableNumberController::class, 'store']);
        Route::put('/{id}', [TableNumberController::class, 'update']);
        Route::delete('/{id}', [TableNumberController::class, 'destroy']);
    });

    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
    });

    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    Route::prefix('dashboard')->group(function () {
        // Main dashboard statistics
        Route::get('/', [DashboardController::class, 'index']);

        // Products
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/search/products', [DashboardController::class, 'searchProducts']);

        // Categories
        Route::get('/search/categories', [DashboardController::class, 'searchCategories']);
        Route::get('/category-performance', [DashboardController::class, 'categoryPerformance']);

        // Earnings
        Route::get('/earnings', [DashboardController::class, 'earnings']);
        Route::get('/earnings/chart', [DashboardController::class, 'earningsChart']);
        Route::get('/financial-summary', [DashboardController::class, 'financialSummary']);

        // Orders
        Route::get('/orders', [DashboardController::class, 'orders']);
    });

    Route::prefix('reports')->group(function () {
        // Basic Reports
        Route::get('/total-earnings', [ReportController::class, 'getTotalEarnings']);
        Route::get('/current-month-earnings', [ReportController::class, 'getCurrentMonthEarnings']);
        Route::get('/total-cashiers', [ReportController::class, 'getTotalCashiers']);
        Route::get('/products-most-earnings', [ReportController::class, 'getProductsMostOrderEarnings']);
        Route::get('/monthly-earnings-chart', [ReportController::class, 'getMonthlyEarningsChart']);
        Route::get('/detailed', [ReportController::class, 'getDetailedReport']);

        // Additional Reports
        Route::get('/summary', [ReportController::class, 'getSummaryReport']);
        Route::get('/cashier-performance', [ReportController::class, 'getCashierPerformance']);
        Route::get('/sales-summary', [ReportController::class, 'getSalesSummary']);
        Route::get('/product-performance', [ReportController::class, 'getProductPerformance']);
        Route::get('/category-revenue', [ReportController::class, 'getCategoryRevenue']);
        Route::get('/daily-earnings', [ReportController::class, 'getDailyEarnings']);
        Route::get('/order-status', [ReportController::class, 'getOrderStatus']);
        Route::get('/payment-methods', [ReportController::class, 'getPaymentMethods']);
        Route::get('/top-customers', [ReportController::class, 'getTopCustomers']);
        Route::get('/revenue-comparison', [ReportController::class, 'getRevenueComparison']);

        // Export Reports
        Route::post('/export/pdf', [ReportController::class, 'generatePdf']);
        Route::post('/export/excel', [ReportController::class, 'generateExcel']);
    });

});

// Cashier routes
Route::prefix('cashier')->middleware('role:cashier')->group(function () {

    // Carts (full access)
    Route::prefix('carts')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/{id}', [CartController::class, 'show']);
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::delete('/{id}', [CartController::class, 'destroy']);
    });

    // Orders (full access)
    Route::prefix('orders')->group(function () {
<<<<<<< HEAD
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::get('/status', [OrderController::class, 'getByStatus']);
        Route::get('/findByNumber/{id}', [OrderController::class, 'findByNumber']);
        Route::put('/markAsDone/{id}', [OrderController::class, 'markAsDone']);
        Route::get('/checkOrder', [OrderController::class, 'checkOrder']);
        Route::put('/cancelOrder/{id}', [OrderController::class, 'cancelOrder']);
    });
    Route::prefix('payment')->group(function () {
        Route::post('/create', [PaymentController::class, 'createPayment']);
        Route::get('/success', [PaymentController::class, 'paymentSuccess']);
        Route::get('/cancel', [PaymentController::class, 'paymentCancel']);
    });


=======
        Route::get('/', [OrderListController::class, 'index']);
        Route::get('/{id}', [OrderListController::class, 'show']);
        Route::post('/', [OrderListController::class, 'store']);
        Route::put('/{id}', [OrderListController::class, 'update']);
        Route::delete('/{id}', [OrderListController::class, 'destroy']);
        Route::get('/status', [OrderListController::class, 'getByStatus']);
        Route::get('/findByNumber/{id}', [OrderListController::class, 'findByNumber']);
        Route::put('/markAsDone/{id}', [OrderListController::class, 'markAsDone']);
        Route::get('/checkOrder', [OrderListController::class, 'checkOrder']);
        Route::get('/cancelOrder', [OrderListController::class, 'cancelOrder']);
    });

    // View-only access to products, categories, tables
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::get('/tables', [TableNumberController::class, 'index']);
    Route::get('/tables/{id}', [TableNumberController::class, 'show']);
>>>>>>> 2a0322896c28ac6098a4041156f9ba15ca0a26bc
});
