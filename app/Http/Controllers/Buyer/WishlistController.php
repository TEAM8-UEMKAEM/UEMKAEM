<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Menampilkan daftar wishlist milik buyer yang sedang login (PBI #25).
     */
    public function index(Request $request)
    {
        $wishlists = Wishlist::with(['product.category', 'product.region', 'product.artisan'])
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->get();

        return view('buyer.wishlist.index', compact('wishlists'));
    }

    /**
     * Menyimpan produk ke wishlist jika belum ada, atau menghapusnya jika sudah ada (idempoten).
     */
    public function toggle(Request $request, string $product)
    {
        $productModel = Product::where('slug', $product)
            ->when(is_numeric($product), fn ($query) => $query->orWhere('id', (int) $product))
            ->firstOrFail();

        $existing = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productModel->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $message = 'Produk berhasil dihapus dari wishlist.';
        } else {
            Wishlist::firstOrCreate([
                'user_id' => $request->user()->id,
                'product_id' => $productModel->id,
            ]);
            $message = 'Produk berhasil disimpan ke wishlist.';
        }

        return redirect()->back()->with('wishlist_status', $message);
    }
}
