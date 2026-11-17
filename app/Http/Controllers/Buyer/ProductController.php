<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Region;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'region', 'artisan'])
            ->published();

        // Pencarian berdasarkan nama produk, deskripsi, atau nama pengrajin
        $search = trim((string) $request->input('q', $request->input('search', '')));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('artisan_name', 'like', "%{$search}%");
            });
        }

        // Filter kategori (mendukung slug maupun ID)
        $selectedCategory = trim((string) $request->input('category', $request->input('kategori', '')));
        if ($selectedCategory !== '') {
            $query->where(function ($q) use ($selectedCategory) {
                if (is_numeric($selectedCategory)) {
                    $q->where('category_id', (int) $selectedCategory);
                }
                $q->orWhereHas('category', fn ($cq) => $cq->where('slug', $selectedCategory));
            });
        }

        // Filter wilayah / region (mendukung slug, ID region, maupun kolom origin)
        $selectedRegion = trim((string) $request->input('region', $request->input('provinsi', '')));
        if ($selectedRegion !== '') {
            $query->where(function ($q) use ($selectedRegion) {
                if (is_numeric($selectedRegion)) {
                    $q->where('region_id', (int) $selectedRegion);
                }
                $q->orWhere('origin', 'like', "%{$selectedRegion}%")
                    ->orWhereHas('region', function ($rq) use ($selectedRegion) {
                        $rq->where('slug', $selectedRegion)
                            ->orWhere('name', 'like', "%{$selectedRegion}%");
                    });
            });
        }

        // Filter rentang harga minimum & maksimum
        $minPrice = $request->input('min_price');
        if ($minPrice !== null && $minPrice !== '' && is_numeric($minPrice)) {
            $query->where('price', '>=', (int) $minPrice);
        }

        $maxPrice = $request->input('max_price');
        if ($maxPrice !== null && $maxPrice !== '' && is_numeric($maxPrice)) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        // Sorting / pengurutan
        $sort = trim((string) $request->input('sort', $request->input('urut', 'latest')));
        match ($sort) {
            'price_asc', 'termurah' => $query->orderBy('price', 'asc')->orderByDesc('id'),
            'price_desc', 'termahal' => $query->orderBy('price', 'desc')->orderByDesc('id'),
            'fairtrade' => $query->orderByDesc('fair_trade_percent')->orderByDesc('id'),
            default => $query->orderByDesc('created_at')->orderByDesc('id'),
        };

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $regions = Region::orderBy('name')->get();

        $wishlistedProductIds = Auth::check()
            ? Wishlist::where('user_id', Auth::id())->pluck('product_id')->all()
            : [];

        return view('buyer.products.index', compact(
            'products',
            'categories',
            'regions',
            'search',
            'selectedCategory',
            'selectedRegion',
            'minPrice',
            'maxPrice',
            'sort',
            'wishlistedProductIds'
        ));
    }

    public function show(string $product)
    {
        $product = Product::with(['category', 'region', 'artisan', 'fairPrice'])
            ->published()
            ->where(function ($query) use ($product) {
                $query->where('slug', $product);
                if (is_numeric($product)) {
                    $query->orWhere('id', (int) $product);
                }
            })
            ->firstOrFail();

        $inWishlist = Auth::check()
            && Wishlist::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();

        return view('buyer.products.show', compact('product', 'inWishlist'));
    }
}
