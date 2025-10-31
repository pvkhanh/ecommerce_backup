<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {

    Route::get('/', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('images', \App\Http\Controllers\Admin\ImageController::class);
    Route::get('images/api/list', [\App\Http\Controllers\Admin\ImageController::class, 'apiList'])->name('images.api.list');

    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);

    //27/10/2025
    // Product Variants Management
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/', [ProductVariantController::class, 'index'])->name('index');
        Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
        Route::post('/', [ProductVariantController::class, 'store'])->name('store');
        Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');

        // Stock Management
        Route::get('/{variant}/stock', [ProductVariantController::class, 'stock'])->name('stock');
        Route::post('/{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock');

        // Bulk Create
        Route::post('/bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('bulk-create');
    });
    // ➕ Route xử lý thao tác hàng loạt (bulk-action)
    Route::post('images/bulk-action', [ImageController::class, 'bulkAction'])
        ->name('images.bulk-action');


});
















// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Admin\ImageController;
// use App\Http\Controllers\Admin\ProductController;
// use App\Http\Controllers\Admin\ProductVariantController;

// /*
// |--------------------------------------------------------------------------
// | Admin Routes
// |--------------------------------------------------------------------------
// */

// Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

//     // Dashboard
//     Route::get('/', function () {
//         return view('admin.dashboard');
//     })->name('dashboard');

//     // Images Management
//     Route::resource('images', ImageController::class);
//     Route::get('images/api/list', [ImageController::class, 'apiList'])->name('images.api.list');

//     // Products Management
//     Route::resource('products', ProductController::class);

//     // Product Variants Management
//     Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
//         Route::get('/', [ProductVariantController::class, 'index'])->name('index');
//         Route::get('/create', [ProductVariantController::class, 'create'])->name('create');
//         Route::post('/', [ProductVariantController::class, 'store'])->name('store');
//         Route::get('/{variant}/edit', [ProductVariantController::class, 'edit'])->name('edit');
//         Route::put('/{variant}', [ProductVariantController::class, 'update'])->name('update');
//         Route::delete('/{variant}', [ProductVariantController::class, 'destroy'])->name('destroy');

//         // Stock Management
//         Route::get('/{variant}/stock', [ProductVariantController::class, 'stock'])->name('stock');
//         Route::post('/{variant}/stock', [ProductVariantController::class, 'updateStock'])->name('update-stock');

//         // Bulk Create
//         Route::post('/bulk-create', [ProductVariantController::class, 'bulkCreate'])->name('bulk-create');
//     });
// });
