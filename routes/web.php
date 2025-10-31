<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DashboardController,
    ProductController,
    ProductVariantController,
    CategoryController,
    UserController,
    OrderController,
    ImageController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('home');

// ========== ADMIN AREA ==========
Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {

    // ================== DASHBOARD ==================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ================== IMAGES ==================
    Route::get('images/api/list', [ImageController::class, 'apiList'])->name('images.api.list');
    Route::post('images/upload', [ImageController::class, 'upload'])->name('images.upload');
    Route::post('images/bulk-action', [ImageController::class, 'bulkAction'])->name('images.bulk-action');
    Route::resource('images', ImageController::class);

    // ================== PRODUCTS ==================
    Route::prefix('products')->name('products.')->group(function () {

        // ====== THÙNG RÁC ======
        Route::get('trash', [ProductController::class, 'trash'])->name('trash');

        // Khôi phục một sản phẩm
        Route::post('restore/{id}', [ProductController::class, 'restore'])->name('restore');

        // Khôi phục tất cả sản phẩm trong thùng rác
        Route::post('restore-all', [ProductController::class, 'restoreAll'])->name('restoreAll');

        // Xóa vĩnh viễn một sản phẩm
        Route::delete('force-delete/{id}', [ProductController::class, 'forceDestroy'])->name('forceDelete');

        // Xóa vĩnh viễn tất cả sản phẩm trong thùng rác
        Route::delete('force-delete-all', [ProductController::class, 'forceDeleteAll'])->name('forceDeleteAll');

        // Bulk actions
        Route::post('bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('bulk-update-status', [ProductController::class, 'bulkUpdateStatus'])->name('bulk-update-status');

        // CRUD chính
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('{product}', [ProductController::class, 'show'])->name('show');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');

        // Toggle status
        Route::post('{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
    });

    // ================== PRODUCT VARIANTS ==================
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
        Route::post('/', [ProductVariantController::class, 'store'])->name('store');
        Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
        Route::get('/{variant}/stock', [ProductVariantController::class, 'stock'])->name('stock');
        Route::post('/{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock');
        Route::post('/bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('bulk-create');
        Route::get('/check-sku', [ProductVariantController::class, 'checkSKU'])
            ->name('checkSku');

    });

    // ================== CATEGORIES ==================
    Route::resource('categories', CategoryController::class);

    // ================== USERS ==================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('trashed', [UserController::class, 'trashed'])->name('trashed');
        Route::post('restore/{id}', [UserController::class, 'restore'])->name('restore');
        Route::post('restore-all', [UserController::class, 'restoreAll'])->name('restoreAll');
        Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('forceDelete');
        Route::delete('force-delete-selected', [UserController::class, 'forceDeleteSelected'])->name('forceDeleteSelected');
        Route::post('{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
    });
    Route::resource('users', UserController::class);

    // // ================== ORDERS ==================
    // // Route::resource('orders', OrderController::class);
    // // Trash routes - phải đặt trước resource routes
    // Route::get('orders/trashed', [OrderController::class, 'trashed'])->name('orders.trashed');
    // Route::get('orders/{id}/restore', [OrderController::class, 'restore'])->name('orders.restore');
    // Route::get('orders/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('orders.force-delete');
    // Route::get('orders/empty-trash', [OrderController::class, 'emptyTrash'])->name('orders.empty-trash');

    // // Resource routes
    // Route::resource('orders', OrderController::class);

    // // Additional routes
    // Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    // Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    // Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');


    // // 🔹 Các route xử lý hành động đơn hàng
    // Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    // Route::post('/orders/{order}/ship', [OrderController::class, 'ship'])->name('orders.ship');
    // Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('orders.complete');
    // Order Management
    // Route::prefix('orders')->name('orders.')->group(function () {
    //     // Main CRUD
    //     Route::get('/', [OrderController::class, 'index'])->name('index');
    //     Route::get('/create', [OrderController::class, 'create'])->name('create');
    //     Route::post('/', [OrderController::class, 'store'])->name('store');
    //     Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    //     Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');
    //     Route::put('/{id}', [OrderController::class, 'update'])->name('update');
    //     Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');

    //     // Trashed Orders
    //     Route::get('/trashed/list', [OrderController::class, 'trashed'])->name('trashed');
    //     Route::post('/restore/{id}', [OrderController::class, 'restore'])->name('restore');
    //     Route::delete('/force-delete/{id}', [OrderController::class, 'forceDelete'])->name('force-delete');

    //     // Status Management
    //     Route::patch('/{id}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    //     Route::post('/{id}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('confirm-payment');
    //     Route::post('/{id}/cancel', [OrderController::class, 'cancel'])->name('cancel');

    //     // Export & Print
    //     Route::get('/{id}/invoice', [OrderController::class, 'invoice'])->name('invoice');
    //     Route::get('/export/excel', [OrderController::class, 'export'])->name('export');
    //     Route::patch('orders/{order}/update-status', [Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    // });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/trashed', [OrderController::class, 'trashed'])->name('trashed');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::put('/{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');

        // Trashed restore / force delete
        Route::post('/{id}/restore', [OrderController::class, 'restore'])->name('restore');
        Route::delete('/{id}/force-delete', [OrderController::class, 'forceDelete'])->name('force-delete');

        // Quick status actions
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
        Route::post('/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('confirm-payment');
        Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');

        // Export / Invoice
        Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
        Route::get('/export/excel', [OrderController::class, 'export'])->name('export');
    });
});





// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\{
//     DashboardController,
//     ProductController,
//     ProductVariantController,
//     CategoryController,
//     UserController,
//     OrderController,
//     ImageController
// };

// /*
// |--------------------------------------------------------------------------
// | Web Routes - Admin
// |--------------------------------------------------------------------------
// */

// Route::get('/', [DashboardController::class, 'index'])->name('home');

// // ================== ADMIN AREA ==================
// Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {

//     // ================== DASHBOARD ==================
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//     // ================== IMAGES ==================
//     Route::prefix('images')->name('images.')->group(function () {
//         Route::get('api/list', [ImageController::class, 'apiList'])->name('api.list');
//         Route::post('upload', [ImageController::class, 'upload'])->name('upload');
//         Route::post('bulk-action', [ImageController::class, 'bulkAction'])->name('bulk-action');
//     });
//     Route::resource('images', ImageController::class);

//     // ================== PRODUCTS ==================
//     Route::prefix('products')->name('products.')->group(function () {

//         // 🔹 Thùng rác
//         Route::get('trash', [ProductController::class, 'trash'])->name('trash');
//         Route::post('restore/{id}', [ProductController::class, 'restore'])->name('restore');
//         Route::post('restore-all', [ProductController::class, 'restoreAll'])->name('restoreAll');
//         Route::delete('force-delete/{id}', [ProductController::class, 'forceDestroy'])->name('forceDelete');
//         Route::delete('force-delete-all', [ProductController::class, 'forceDeleteAll'])->name('forceDeleteAll');

//         // 🔹 Bulk actions
//         Route::post('bulk-delete', [ProductController::class, 'bulkDelete'])->name('bulk-delete');
//         Route::post('bulk-update-status', [ProductController::class, 'bulkUpdateStatus'])->name('bulk-update-status');

//         // 🔹 CRUD chính
//         Route::get('/', [ProductController::class, 'index'])->name('index');
//         Route::get('create', [ProductController::class, 'create'])->name('create');
//         Route::post('/', [ProductController::class, 'store'])->name('store');
//         Route::get('{product}', [ProductController::class, 'show'])->name('show');
//         Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
//         Route::put('{product}', [ProductController::class, 'update'])->name('update');
//         Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');

//         // 🔹 Bật/tắt trạng thái
//         Route::post('{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggle-status');
//     });

//     // ================== PRODUCT VARIANTS ==================
//     Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
//         Route::get('/', [ProductVariantController::class, 'index'])->name('index');
//         Route::get('create', [ProductVariantController::class, 'create'])->name('create');
//         Route::post('/', [ProductVariantController::class, 'store'])->name('store');
//         Route::get('{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
//         Route::put('{variant}', [ProductVariantController::class, 'update'])->name('update');
//         Route::delete('{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');
//         Route::get('{variant}/stock', [ProductVariantController::class, 'stock'])->name('stock');
//         Route::post('{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock');
//         Route::post('bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('bulk-create');
//         // ✅ Route mới cho storeMany
//         Route::post('store-many', [ProductVariantController::class, 'storeMany'])->name('storeMany');
//         Route::get('/check-sku', [ProductVariantController::class, 'checkSKU'])->name('checkSKU');
//         Route::get('/suggest-sku', [ProductVariantController::class, 'suggestSKU'])->name('suggestSKU');

//     });

//     // ================== CATEGORIES ==================
//     Route::resource('categories', CategoryController::class);

//     // ================== USERS ==================
//     Route::prefix('users')->name('users.')->group(function () {
//         Route::get('trashed', [UserController::class, 'trashed'])->name('trashed');
//         Route::post('restore/{id}', [UserController::class, 'restore'])->name('restore');
//         Route::post('restore-all', [UserController::class, 'restoreAll'])->name('restoreAll');
//         Route::delete('force-delete/{id}', [UserController::class, 'forceDelete'])->name('forceDelete');
//         Route::delete('force-delete-selected', [UserController::class, 'forceDeleteSelected'])->name('forceDeleteSelected');
//         Route::post('{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
//     });
//     Route::resource('users', UserController::class);

//     // ================== ORDERS ==================
//     Route::prefix('orders')->name('orders.')->group(function () {

//         // 🔹 Thao tác quản lý trạng thái đơn hàng
//         Route::post('{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
//         Route::post('{order}/ship', [OrderController::class, 'ship'])->name('ship');
//         Route::post('{order}/complete', [OrderController::class, 'complete'])->name('complete');

//         // 🔹 Trash & khôi phục
//         Route::get('trashed', [OrderController::class, 'trashed'])->name('trashed');
//         Route::get('{id}/restore', [OrderController::class, 'restore'])->name('restore');
//         Route::get('{id}/force-delete', [OrderController::class, 'forceDelete'])->name('force-delete');
//         Route::get('empty-trash', [OrderController::class, 'emptyTrash'])->name('empty-trash');

//         // 🔹 Cập nhật trạng thái, xuất hóa đơn, export excel
//         Route::patch('{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
//         Route::get('{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
//         Route::get('export', [OrderController::class, 'export'])->name('export');

//         // 🔹 CRUD chính
//         Route::get('/', [OrderController::class, 'index'])->name('index');
//         Route::get('create', [OrderController::class, 'create'])->name('create');
//         Route::post('/', [OrderController::class, 'store'])->name('store');
//         Route::get('{id}', [OrderController::class, 'show'])->name('show');
//         Route::delete('{id}', [OrderController::class, 'destroy'])->name('destroy');
//         Route::get('{id}/edit', [OrderController::class, 'edit'])->name('edit');
//         Route::put('{id}', [OrderController::class, 'update'])->name('update');

//     });

// });
