<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Hanya kategori aktif yang ditampilkan di katalog publik
        $categories = Category::active()->withCount('products')->get();

        return view('categories.index', compact('categories'));
    }
}
