<?php

use App\Http\Controllers\Buyer\ProductController;
use App\Http\Controllers\Buyer\WishlistController;
use Illuminate\Support\Facades\Route;

if (! Route::has('login')) {
    Route::get('/login', function () {
        return view('buyer.auth-login-stub');
    })->name('login');
}

Route::get('/products', [ProductController::class, 'index'])->name('buyer.products.index');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{product}', [ProductController::class, 'show'])->name('buyer.products.show');
Route::get('/produk/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
});
