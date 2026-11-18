<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\MaterialController as AdminMaterialController;
use App\Http\Controllers\Admin\PriceRuleController;
use App\Http\Controllers\Admin\RegionController as AdminRegionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Api\PriceCheckController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PriceGuideController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');

// Panduan harga untuk artisan (publik) dan API validasi harga (publik, hanya-baca)
Route::get('/panduan-harga', [PriceGuideController::class, 'show'])->name('price-guide');
Route::get('/api/price-check', PriceCheckController::class)->middleware('throttle:60,1')->name('api.price-check');

// Login / logout (tanpa registrasi publik)
Route::get('/login', [LoginController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'store'])->name('login.store')->middleware(['guest', 'throttle:5,1']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout')->middleware('auth');

// Dashboard hanya untuk admin
Route::get('/dashboard', function () {return view('dashboard');})->middleware('admin')->name('dashboard');
Route::get('/account', function () {return view('account');})->name('account');

// ---------- Panel admin ----------
Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.categories.index'))->name('home');

    $masters = [
        'categories' => AdminCategoryController::class,
        'materials'  => AdminMaterialController::class,
        'regions'    => AdminRegionController::class,
        'price-rules' => PriceRuleController::class,
    ];

    foreach ($masters as $name => $controller) {
        Route::patch("{$name}/{id}/toggle", [$controller, 'toggle'])->name("{$name}.toggle");
        Route::resource($name, $controller)->except(['destroy']); // tidak ada hapus permanen
    }

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
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
