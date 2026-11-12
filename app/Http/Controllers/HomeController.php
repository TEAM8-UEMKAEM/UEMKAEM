<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Halaman Beranda: tampilkan 5 kategori unggulan beserta jumlah produknya
        $categories = Category::withCount('products')->take(5)->get();

        return view('home', compact('categories'));
    }
}
