<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Buyer\OrderPaymentController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');
Route::get('/account', function () {return view('account');})->name('account');

Route::get('/test-login', function () {
    auth()->loginUsingId(1);
    return redirect('/orders/1/payment');
});

Route::get('/kategori/{id}/image', function ($id) {
    $category = DB::table('categories')->where('id', $id)->first();

    if (!$category || !$category->image) {
        abort(404);
    }

    $image = $category->image;

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($image);

    return response($image)
        ->header('Content-Type', $mimeType)
        ->header('Cache-Control', 'public, max-age=86400');

})->name('categories.image');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/orders/{order}/payment', [OrderPaymentController::class, 'show'])->name('buyer.orders.payment.show');
    Route::post('/orders/{order}/payment', [OrderPaymentController::class, 'store'])->name('buyer.orders.payment.store');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/payments/pending', [PaymentVerificationController::class, 'index'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    });
});