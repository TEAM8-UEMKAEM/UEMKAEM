<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [ProductController::class, 'index'])->name('products.index');
Route::get('/kategori', [CategoryController::class, 'index'])->name('categories.index');

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
