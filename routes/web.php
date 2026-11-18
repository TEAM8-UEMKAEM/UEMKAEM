<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\PriceRuleController as AdminPriceRuleController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\PriceCheckController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceGuideController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Buyer\OrderPaymentController;
use App\Http\Controllers\Admin\PaymentVerificationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');

// Panduan harga artisan + API validasi harga (hanya membaca, dibatasi 60 permintaan/menit)
Route::get('/panduan-harga', [PriceGuideController::class, 'index'])->name('price-guide');
Route::get('/api/v1/price-check', PriceCheckController::class)->middleware('throttle:60,1')->name('api.price-check');

// Login / logout (tanpa registrasi publik)
Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store')->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

// Dashboard hanya untuk admin
Route::get('/dashboard', function () {return view('dashboard');})->middleware('admin')->name('dashboard');
Route::get('/account', function () {return view('account');})->name('account');

// ---------- Panel admin (data master + riwayat perubahan) ----------
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.categories.index'))->name('home');

    $masters = [
        'categories' => AdminCategoryController::class,
        'materials'  => AdminMaterialController::class,
        'regions'    => AdminRegionController::class,
    ];

    foreach ($masters as $name => $controller) {
        Route::patch("{$name}/{id}/toggle", [$controller, 'toggle'])->name("{$name}.toggle");
        Route::resource($name, $controller)->except(['destroy']); // tidak ada hapus permanen
    }

    Route::patch('price-rules/{id}/toggle', [AdminPriceRuleController::class, 'toggle'])->name('price-rules.toggle');
    Route::resource('price-rules', AdminPriceRuleController::class)->except(['destroy']);

    Route::get('settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('settings.update');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
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

require __DIR__.'/buyer.php';
Route::middleware(['auth'])->group(function () {
    
    Route::get('/orders/{order}/payment', [OrderPaymentController::class, 'show'])->name('buyer.orders.payment.show');
    Route::post('/orders/{order}/payment', [OrderPaymentController::class, 'store'])->name('buyer.orders.payment.store');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/payments/pending', [PaymentVerificationController::class, 'index'])->name('payments.pending');
        Route::post('/payments/{payment}/verify', [PaymentVerificationController::class, 'verify'])->name('payments.verify');
    });
});
