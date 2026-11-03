<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Pencarian berdasarkan nama produk atau artisan
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('artisan_name', 'like', "%{$keyword}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->kategori));
        }

        // Urutan
        match ($request->urut) {
            'termurah' => $query->orderBy('price'),
            'fairtrade' => $query->orderByDesc('fair_trade_percent'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }
}
