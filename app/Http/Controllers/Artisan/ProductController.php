<?php

namespace App\Http\Controllers\Artisan;

use App\Http\Controllers\Controller;
use App\Models\Artisan;
use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Region;
use App\Services\PriceCheckService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request, PriceCheckService $priceCheckService)
    {
        $artisan = $this->currentArtisan();
        $status = (string) $request->query('status', 'all');
        $search = trim((string) $request->query('q', ''));

        $baseQuery = Product::where('artisan_id', $artisan->id);

        $counts = [
            'all' => (clone $baseQuery)->count(),
            'published' => (clone $baseQuery)->where('status', 'published')->count(),
            'draft' => (clone $baseQuery)->where('status', 'draft')->count(),
            'archived' => (clone $baseQuery)->where('status', 'archived')->count(),
        ];

        $query = Product::with(['category', 'material', 'region', 'images'])
            ->where('artisan_id', $artisan->id);

        if (in_array($status, ['published', 'draft', 'archived'], true)) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->orderByDesc('updated_at')->orderByDesc('id')->paginate(12)->withQueryString();

        $priceChecks = [];
        foreach ($products as $product) {
            if ($product->category_id && $product->material_id) {
                $priceChecks[$product->id] = $priceCheckService->check(
                    (int) $product->category_id,
                    (int) $product->material_id,
                    (int) $product->price
                );
            }
        }

        return view('artisan.products.index', compact('artisan', 'products', 'status', 'search', 'counts', 'priceChecks'));
    }

    public function create()
    {
        $artisan = $this->currentArtisan();
        $product = new Product([
            'region_id' => $artisan->region_id,
            'fair_trade_percent' => 90,
            'stock' => 1,
            'status' => 'draft',
        ]);

        return view('artisan.products.form', [
            'artisan' => $artisan,
            'product' => $product,
            'isEdit' => false,
            'categories' => Category::active()->orderBy('name')->get(),
            'materials' => Material::active()->orderBy('name')->get(),
            'regions' => Region::active()->orderBy('name')->get(),
            'priceCheck' => null,
        ]);
    }

    public function store(Request $request, PriceCheckService $priceCheckService)
    {
        $artisan = $this->currentArtisan();
        $data = $this->validateProduct($request);

        $region = ! empty($data['region_id']) ? Region::find($data['region_id']) : $artisan->region;
        $origin = $region?->name ?? $artisan->location ?? 'Nusantara';

        $product = Product::create([
            'artisan_id' => $artisan->id,
            'artisan_name' => $artisan->name,
            'category_id' => (int) $data['category_id'],
            'material_id' => (int) $data['material_id'],
            'region_id' => $region?->id,
            'name' => $data['name'],
            'slug' => $this->generateUniqueSlug($data['name']),
            'description' => $data['description'],
            'origin' => $origin,
            'price' => (int) $data['price'],
            'stock' => (int) $data['stock'],
            'fair_trade_percent' => (int) ($data['fair_trade_percent'] ?? 90),
            'status' => $data['status'] ?? 'draft',
        ]);

        $this->syncImages($request, $product);

        $redirect = redirect()->route('artisan.products.index')
            ->with('success', 'Produk "' . $product->name . '" berhasil ditambahkan.');

        if ($warning = $this->fairPriceWarning($priceCheckService, $product)) {
            $redirect->with('warning', $warning);
        }

        return $redirect;
    }

    public function edit(int $id, PriceCheckService $priceCheckService)
    {
        $artisan = $this->currentArtisan();
        $product = $this->findOwnedProduct($id, $artisan)->load('images');

        $categories = Category::where(function ($q) use ($product) {
            $q->where('is_active', true)->orWhere('id', $product->category_id);
        })->orderBy('name')->get();

        $materials = Material::where(function ($q) use ($product) {
            $q->where('is_active', true)->orWhere('id', $product->material_id);
        })->orderBy('name')->get();

        $regions = Region::where(function ($q) use ($product) {
            $q->where('is_active', true);
            if ($product->region_id) {
                $q->orWhere('id', $product->region_id);
            }
        })->orderBy('name')->get();

        $priceCheck = ($product->category_id && $product->material_id)
            ? $priceCheckService->check((int) $product->category_id, (int) $product->material_id, (int) $product->price)
            : null;

        return view('artisan.products.form', [
            'artisan' => $artisan,
            'product' => $product,
            'isEdit' => true,
            'categories' => $categories,
            'materials' => $materials,
            'regions' => $regions,
            'priceCheck' => $priceCheck,
        ]);
    }

    public function update(Request $request, int $id, PriceCheckService $priceCheckService)
    {
        $artisan = $this->currentArtisan();
        $product = $this->findOwnedProduct($id, $artisan);
        $data = $this->validateProduct($request, $product);

        $region = ! empty($data['region_id']) ? Region::find($data['region_id']) : $artisan->region;
        $origin = $region?->name ?? $artisan->location ?? $product->origin ?? 'Nusantara';

        $product->update([
            'category_id' => (int) $data['category_id'],
            'material_id' => (int) $data['material_id'],
            'region_id' => $region?->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'origin' => $origin,
            'price' => (int) $data['price'],
            'stock' => (int) $data['stock'],
            'fair_trade_percent' => (int) ($data['fair_trade_percent'] ?? $product->fair_trade_percent ?? 90),
        ]);

        $this->syncImages($request, $product);

        $redirect = redirect()->route('artisan.products.edit', $product->id)
            ->with('success', 'Perubahan produk "' . $product->name . '" berhasil disimpan.');

        if ($warning = $this->fairPriceWarning($priceCheckService, $product)) {
            $redirect->with('warning', $warning);
        }

        return $redirect;
    }

    public function publish(int $id)
    {
        $product = $this->findOwnedProduct($id, $this->currentArtisan());
        $product->update(['status' => 'published']);

        return back()->with('success', 'Produk "' . $product->name . '" telah dipublikasikan ke etalase.');
    }

    public function unpublish(int $id)
    {
        $product = $this->findOwnedProduct($id, $this->currentArtisan());
        $product->update(['status' => 'draft']);

        return back()->with('success', 'Produk "' . $product->name . '" ditarik kembali ke draf.');
    }

    public function archive(int $id)
    {
        $product = $this->findOwnedProduct($id, $this->currentArtisan());
        $product->update(['status' => 'archived']);

        return back()->with('success', 'Produk "' . $product->name . '" telah diarsipkan.');
    }

    private function currentArtisan(): Artisan
    {
        return Auth::user()->artisan;
    }

    private function findOwnedProduct(int $id, Artisan $artisan): Product
    {
        $product = Product::findOrFail($id);
        abort_unless((int) $product->artisan_id === (int) $artisan->id, 403, 'Anda tidak memiliki akses ke produk ini.');

        return $product;
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $categoryRule = Rule::exists('categories', 'id')->where(function ($q) use ($product) {
            $q->where('is_active', true);
            if ($product?->category_id) {
                $q->orWhere('id', $product->category_id);
            }
        });

        $materialRule = Rule::exists('materials', 'id')->where(function ($q) use ($product) {
            $q->where('is_active', true);
            if ($product?->material_id) {
                $q->orWhere('id', $product->material_id);
            }
        });

        $regionRule = Rule::exists('regions', 'id')->where(function ($q) use ($product) {
            $q->where('is_active', true);
            if ($product?->region_id) {
                $q->orWhere('id', $product->region_id);
            }
        });

        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'category_id' => ['required', 'integer', $categoryRule],
            'material_id' => ['required', 'integer', $materialRule],
            'region_id' => ['nullable', 'integer', $regionRule],
            'price' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'stock' => ['required', 'integer', 'min:0', 'max:65535'],
            'fair_trade_percent' => ['nullable', 'integer', 'min:0', 'max:100'],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'primary_image_id' => ['nullable', 'integer'],
        ], [], [
            'name' => 'Nama produk',
            'description' => 'Deskripsi produk',
            'category_id' => 'Kategori kriya',
            'material_id' => 'Material',
            'region_id' => 'Wilayah asal',
            'price' => 'Harga jual',
            'stock' => 'Jumlah stok',
            'fair_trade_percent' => 'Persentase hak perajin',
            'photos' => 'Foto produk',
            'photos.*' => 'Foto produk',
        ]);
    }

    private function syncImages(Request $request, Product $product): void
    {
        $removeIds = $request->input('remove_images', []);
        if (is_array($removeIds) && count($removeIds) > 0) {
            $toDelete = $product->images()->whereIn('id', $removeIds)->get();
            foreach ($toDelete as $img) {
                $relative = ltrim(Str::after($img->path, '/storage/'), '/');
                if ($relative !== '') {
                    Storage::disk('public')->delete($relative);
                }
                $img->delete();
            }
        }

        $nextOrder = (int) ($product->images()->max('sort_order') ?? 0);
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if (! $file || ! $file->isValid()) {
                    continue;
                }
                $stored = $file->store('products', 'public');
                $nextOrder++;
                $product->images()->create([
                    'path' => '/storage/' . $stored,
                    'sort_order' => $nextOrder,
                    'is_primary' => false,
                ]);
            }
        }

        $images = $product->images()->get();
        if ($images->isEmpty()) {
            $product->update(['image' => null]);
            return;
        }

        $requestedPrimaryId = $request->input('primary_image_id');
        $primary = $requestedPrimaryId
            ? $images->firstWhere('id', (int) $requestedPrimaryId)
            : $images->firstWhere('is_primary', true);

        if (! $primary) {
            $primary = $images->first();
        }

        $product->images()->update(['is_primary' => false]);
        $primary->update(['is_primary' => true]);
        $product->update(['image' => $primary->path]);
    }

    private function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'produk-kriya';
        $slug = $base;
        $counter = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function fairPriceWarning(PriceCheckService $priceCheckService, Product $product): ?string
    {
        if (! $product->category_id || ! $product->material_id) {
            return null;
        }

        $check = $priceCheckService->check(
            (int) $product->category_id,
            (int) $product->material_id,
            (int) $product->price
        );

        if ($check['valid'] === false) {
            return 'Peringatan Harga Adil: ' . $check['message'];
        }

        return null;
    }
}
