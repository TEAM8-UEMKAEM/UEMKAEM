<?php

use App\Http\Controllers\Artisan\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('artisan')->name('artisan.')->middleware(['auth', 'artisan'])->group(function () {
    Route::get('/', fn () => redirect()->route('artisan.products.index'))->name('home');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::patch('/products/{id}/publish', [ProductController::class, 'publish'])->name('products.publish');
    Route::patch('/products/{id}/unpublish', [ProductController::class, 'unpublish'])->name('products.unpublish');
    Route::patch('/products/{id}/archive', [ProductController::class, 'archive'])->name('products.archive');
});
