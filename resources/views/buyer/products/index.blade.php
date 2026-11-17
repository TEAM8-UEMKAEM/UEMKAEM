@extends('layouts.buyer')

@section('title', 'Jelajahi Karya Nusantara - UEMKAEM')

@section('content')
    @php
        $wishlistCount = is_array($wishlistedProductIds ?? null) ? count($wishlistedProductIds) : 0;
        $fallbackImages = [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCaZPQskB8BQ74ApHlHNtLDoPSL9G0FSiCDB5g8o57VLHsPyD0TTq3et8cEY28X4KJbT_WHCjmk9eDzgplg8gZIG13uWcKHQw9K6368zz9x0sN2cFIKSYhH5bXl0l3xiqJvvtUMYtB7uBolxNK-qJxXk-Dy212FlZSlFDEBu1X3Z268V2sps9_xjfJVR9Sbzf7U997v0Dvv3n9VRy089L8Qc-V2l-ef-pgSfK1aCQ',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDtoYHXYpQXNj0NTWqFtmXJXVwTvv0HqOfvYoBODvO9V9rcIgv9630voxPMX-K_LzQVzPEUdlLn_97ZfGHyv1OUbbq4-5wlqmnWhVTArCuOWnFIC3Z5E0RVm_HS5c1HiK_jTCKLKyPLppQvsh6Th_6KS91hW6BEUC1TmIiWPZn7ofEmbIiG8P5lYQXzqbNZIOZUk4oYVX2StIyDOIHRt4evnjXxMC_LLQCO1rjulg',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDr_V4kUxHT8sb34FRuqEN7SpF0xgvWyuIc7iN8S6x0pTJohyryrNqjt21i3B9sxYO7Wbs20Ij3bh4CurYjbzac4y3zsoFhmQygW9y0T3FJvum7VDo3bE_uklINfsZ17sB3viW02_a-ghDNnUxYXcDXtZvmjuiaQrg1aVdvWqeUP_NFtEFSfWCgCMfNztLIZpcy3X1BAGDjEEs8hGI7HTCQeD_Ffw6WaSwyFQX7yQ',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuA4LVMiWzqkoe0ptXAR-QkeTpVjurSEK6vlaXcumQkspiHzGkQ4gzzDkur2OqBLq653ExqbQkvEO-Rzn3jOeiKIRdq4IduDswl1q9gY9Rjp9O54OK2Pjl3mnIo1jWQ6KIrGFbPYNYyl_1UOpb4Vay2gPpjNfpjd34VYXMPJQwhp0MKye02-DaoVluY79mBITv7aYxibp-0QFl-y9LFjn0vDQSNVl9SM5o5TykToWw',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBkH82xxTehGfCH6q2d6cWJ_ogCTN9OGKAUaETZbw7z668KU5vDlhb1TYdU3-ceuJuyWbaIyP215fiqLssjpDgPb1yg3OUldsucGt8SOEI4kWwd2u0swtcLQ1QuclyAvlhpRMesf5xYuvZeZLfDdoDBB2rfMXuuVtd471yEjeeRl6o1WBMmkL-nn_r1F-b2LFoWk7k5afNNC1XwvvvOe2ghZ-i7LbL1Ju2NjGdepw',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuCqnHmr077BmblDFESAoabhuUUUN_peWwKyzxuPNt2wvMDCNAmT0clAsfO0Qndba8R12c8QVp35tTJ3QedS4njVrg-epDCbsEtON77MTyK51W8xEFkWrCu9D8rw3wuV33VwaFO6fWYyKkkLUykRAi6j7yUprrCUk1gsqA3wX9_11heM_l4il-bvC7d13Q4Go6nOrqRa1gGITeaCSJN7jhZisnMavwUHFrOXi6tKxA',
        ];
    @endphp

    <div class="flex flex-col w-full">
        <div class="w-full max-w-7xl mx-auto px-margin py-space-xl">
            {{-- Hero Header & Section Controls --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-lg mb-space-xl" dusk="product-listing-header">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container text-primary font-label-sm text-label-sm mb-2" dusk="total-products-badge">
                        <span class="material-symbols-outlined text-[14px]">verified</span>
                        <span>{{ $products->total() }} Mahakarya Terkurasi • SDG 8.3 &amp; 8.9</span>
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface tracking-tight">
                        Jelajahi Karya Nusantara
                    </h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mt-space-xs">
                        Temukan produk kriya terkurasi dengan jaminan harga adil (fair-trade) dari pengrajin lokal.
                    </p>
                </div>

                {{-- Segment Tabs --}}
                <div class="flex items-center gap-space-sm shrink-0">
                    <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-full font-label-md text-label-md bg-primary text-on-primary shadow-sm font-bold transition-all">
                        Semua Produk
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="px-5 py-2.5 rounded-full font-label-md text-label-md bg-surface-container-lowest text-on-surface shadow-md font-bold flex items-center gap-1.5 hover:bg-surface-container transition-all">
                        <span>Wishlist Saya</span>
                        <span class="w-5 h-5 rounded-full bg-secondary-container/20 text-secondary flex items-center justify-center text-[11px] font-bold">{{ $wishlistCount }}</span>
                    </a>
                </div>
            </div>

            {{-- Main 2-Column Catalog Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
                {{-- Filter Sidebar Column (PBI #23) --}}
                <aside class="lg:col-span-3 w-full bg-surface-container-lowest rounded-2xl p-6 shadow-[0_4px_20px_-2px_rgba(19,78,72,0.04)] lg:sticky lg:top-24">
                    <form method="GET" action="{{ route('products.index') }}" dusk="filter-form">
                        {{-- Search Input Well --}}
                        <div class="relative flex items-center w-full bg-surface-container rounded-xl px-3.5 py-2.5">
                            <span class="material-symbols-outlined text-outline text-[20px] mr-2">search</span>
                            <input
                                type="text"
                                name="q"
                                dusk="search-input"
                                value="{{ $search ?? request('q') }}"
                                class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none"
                                placeholder="Search kriya..."
                            >
                        </div>

                        {{-- Filter: Kategori --}}
                        <div class="mt-6">
                            <label class="block font-label-lg text-label-lg text-on-surface mb-2" for="select-category">Kategori</label>
                            <div class="relative w-full mb-3">
                                <select
                                    name="category"
                                    id="select-category"
                                    dusk="filter-category"
                                    class="w-full appearance-none bg-surface-container rounded-xl px-4 py-3 font-label-md text-label-md text-on-surface pr-10 focus:outline-none cursor-pointer"
                                >
                                    <option value="">Semua Kategori</option>
                                    @foreach (($categories ?? []) as $category)
                                        <option value="{{ $category->slug }}" @selected(($selectedCategory ?? '') === $category->slug || ($selectedCategory ?? '') === (string) $category->id)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant text-[20px]">
                                    expand_more
                                </span>
                            </div>

                            @if (!empty($categories) && count($categories) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($categories->take(5) as $catChip)
                                        @php
                                            $isChipActive = ($selectedCategory ?? '') === $catChip->slug;
                                        @endphp
                                        <a
                                            href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $catChip->slug])) }}"
                                            class="px-3.5 py-1.5 rounded-full font-label-md text-label-md {{ $isChipActive ? 'bg-primary text-on-primary' : 'bg-surface-container-high text-on-surface hover:bg-primary hover:text-on-primary' }} transition-all"
                                        >
                                            {{ $catChip->name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- Filter: Wilayah --}}
                        <div class="mt-6">
                            <label class="block font-label-lg text-label-lg text-on-surface mb-2" for="select-region">Wilayah</label>
                            <div class="relative w-full">
                                <select
                                    name="region"
                                    id="select-region"
                                    dusk="filter-region"
                                    class="w-full appearance-none bg-surface-container rounded-xl px-4 py-3 font-label-md text-label-md text-on-surface pr-10 focus:outline-none cursor-pointer"
                                >
                                    <option value="">Semua Wilayah</option>
                                    @foreach (($regions ?? []) as $region)
                                        <option value="{{ $region->slug }}" @selected(($selectedRegion ?? '') === $region->slug || ($selectedRegion ?? '') === (string) $region->id)>
                                            {{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant text-[20px]">
                                    expand_more
                                </span>
                            </div>
                        </div>

                        {{-- Filter: Rentang Harga --}}
                        <div class="mt-6">
                            <span class="block font-label-lg text-label-lg text-on-surface mb-1">Rentang harga</span>
                            <div class="flex items-center justify-between font-label-md text-label-md text-on-surface py-1">
                                <span>Rp {{ number_format((int) ($minPrice ?: 50000), 0, ',', '.') }}</span>
                                <span class="text-outline">-</span>
                                <span>Rp {{ number_format((int) ($maxPrice ?: 1500000), 0, ',', '.') }}</span>
                            </div>

                            {{-- Custom Dual Thumb Range Bar Visual --}}
                            <div class="relative w-full py-3 flex items-center">
                                <div class="w-full h-2 rounded-full bg-surface-container relative">
                                    <div class="absolute left-0 right-0 top-0 bottom-0 rounded-full bg-primary-container"></div>
                                    <div class="absolute -top-1.5 left-0 w-5 h-5 rounded-full bg-surface-container-lowest shadow-md flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-primary-container"></div>
                                    </div>
                                    <div class="absolute -top-1.5 right-0 w-5 h-5 rounded-full bg-surface-container-lowest shadow-md flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-primary-container"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <div>
                                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Harga Min (Rp)</label>
                                    <input
                                        type="number"
                                        name="min_price"
                                        dusk="filter-min-price"
                                        value="{{ $minPrice ?? request('min_price') }}"
                                        placeholder="50000"
                                        min="0"
                                        class="w-full bg-surface-container rounded-xl px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none"
                                    >
                                </div>
                                <div>
                                    <label class="block font-label-sm text-label-sm text-on-surface-variant mb-1">Harga Maks (Rp)</label>
                                    <input
                                        type="number"
                                        name="max_price"
                                        dusk="filter-max-price"
                                        value="{{ $maxPrice ?? request('max_price') }}"
                                        placeholder="1500000"
                                        min="0"
                                        class="w-full bg-surface-container rounded-xl px-3 py-2 font-body-sm text-body-sm text-on-surface focus:outline-none"
                                    >
                                </div>
                            </div>
                        </div>

                        {{-- Filter: Urutkan / Sorting --}}
                        <div class="mt-6">
                            <label class="block font-label-lg text-label-lg text-on-surface mb-2" for="select-sort">Urutkan</label>
                            <div class="relative w-full">
                                <select
                                    name="sort"
                                    id="select-sort"
                                    dusk="sort-select"
                                    class="w-full appearance-none bg-surface-container rounded-xl px-4 py-3 font-label-md text-label-md text-on-surface pr-10 focus:outline-none cursor-pointer"
                                >
                                    <option value="latest" @selected(($sort ?? 'latest') === 'latest' || ($sort ?? '') === '')>Terbaru</option>
                                    <option value="price_asc" @selected(($sort ?? '') === 'price_asc' || ($sort ?? '') === 'termurah')>Harga Terendah</option>
                                    <option value="price_desc" @selected(($sort ?? '') === 'price_desc' || ($sort ?? '') === 'termahal')>Harga Tertinggi</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant text-[20px]">
                                    expand_more
                                </span>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="mt-6">
                            <button
                                type="submit"
                                dusk="filter-submit"
                                class="w-full py-3 px-4 rounded-xl bg-primary-container hover:bg-primary text-on-primary font-label-md text-label-md font-bold transition-colors shadow-sm"
                            >
                                Terapkan Filter
                            </button>
                        </div>

                        {{-- Filter Reset & Quick Stats --}}
                        <div class="mt-5 pt-4 border-t border-outline-variant/30 flex items-center justify-between">
                            <span class="font-body-sm text-body-sm text-on-surface-variant" dusk="showing-count">
                                Menampilkan {{ $products->count() }} dari {{ $products->total() }} kriya
                            </span>
                            <a href="{{ route('products.index') }}" dusk="filter-reset" class="font-label-sm text-label-sm text-secondary hover:underline">
                                Reset Filter
                            </a>
                        </div>
                    </form>
                </aside>

                {{-- Product Listing Subgrid (PBI #22) --}}
                <section class="lg:col-span-9 w-full">
                    @if ($products->isEmpty())
                        <div dusk="empty-state" class="bg-surface-container-lowest rounded-2xl p-12 text-center shadow-[0_4px_20px_-2px_rgba(19,78,72,0.04)] flex flex-col items-center">
                            <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center text-primary-container mb-4">
                                <span class="material-symbols-outlined text-[32px]">inventory_2</span>
                            </div>
                            <h2 class="font-headline-md text-headline-md text-on-surface">Belum Ada Produk Tersedia</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant max-w-md mt-2">
                                Produk tidak ditemukan. Silakan ubah kata kunci pencarian atau kriteria filter Anda.
                            </p>
                            <a href="{{ route('products.index') }}" class="mt-6 px-6 py-2.5 rounded-xl bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors">
                                Reset Semua Filter
                            </a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-space-lg" dusk="product-grid">
                            @foreach ($products as $product)
                                @php
                                    $ft = (int) ($product->fair_trade_percent ?? 90);
                                    $artisanName = $product->artisan?->name ?? $product->artisan_name ?? 'Perajin Nusantara';
                                    $originName = $product->region?->name ?? $product->origin ?? 'Nusantara';
                                    $stock = $product->stock ?? null;
                                    $hasShow = \Illuminate\Support\Facades\Route::has('products.show');
                                    $isSaved = in_array($product->id, $wishlistedProductIds ?? []);
                                    $imgSrc = !empty($product->image) ? $product->image : $fallbackImages[$loop->index % count($fallbackImages)];
                                @endphp

                                <article
                                    dusk="product-card-{{ $product->id }}"
                                    class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-[0_4px_20px_-2px_rgba(19,78,72,0.04)] hover:shadow-[0_12px_28px_-4px_rgba(19,78,72,0.08)] transition-all flex flex-col group"
                                >
                                    <div class="relative aspect-[4/3] w-full overflow-hidden bg-surface-container">
                                        <img
                                            src="{{ $imgSrc }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                        >
                                        <div class="absolute top-3 left-3 flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary-container/90 text-on-primary backdrop-blur-sm font-label-sm text-label-sm">
                                            <span class="material-symbols-outlined text-[14px]">eco</span>
                                            <span dusk="product-category-{{ $product->id }}">{{ $product->category?->name ?? '100% Pewarna Alami' }}</span>
                                        </div>
                                        @if ($stock !== null)
                                            <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-surface/90 backdrop-blur-sm text-on-surface font-label-sm text-label-sm" dusk="product-stock-{{ $product->id }}">
                                                Tersedia {{ $stock }} Pcs
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-space-md flex flex-col flex-1">
                                        <h3 dusk="product-name-{{ $product->id }}" class="font-headline-sm text-headline-sm text-on-surface line-clamp-1">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="font-body-md text-body-md text-on-surface-variant mt-0.5">
                                            <span dusk="product-artisan-{{ $product->id }}">{{ $artisanName }}</span>
                                            •
                                            <span dusk="product-origin-{{ $product->id }}">{{ $originName }}</span>
                                        </p>

                                        <div class="flex items-center justify-between mt-space-md mb-space-md gap-2">
                                            <span dusk="product-price-{{ $product->id }}" class="font-headline-sm text-headline-sm text-on-surface font-extrabold">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <span dusk="product-fairtrade-{{ $product->id }}" class="px-2.5 py-1 rounded-full bg-surface-container font-label-sm text-label-sm text-primary-container font-medium">
                                                Fair-Price Approved ({{ $ft }}%)
                                            </span>
                                        </div>

                                        <div class="mt-auto flex items-center gap-space-sm pt-space-xs">
                                            <a
                                                href="{{ $hasShow ? route('products.show', $product->slug) : '#' }}"
                                                dusk="product-detail-link-{{ $product->id }}"
                                                class="flex-1 py-2.5 px-4 rounded-xl bg-primary-container text-on-primary font-label-md text-label-md hover:bg-primary transition-colors text-center"
                                            >
                                                Detail Produk
                                            </a>

                                            <form method="POST" action="{{ route('wishlist.toggle', $product->slug) }}" class="shrink-0">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    dusk="wishlist-toggle-{{ $product->id }}"
                                                    data-wishlisted="{{ $isSaved ? 'true' : 'false' }}"
                                                    title="{{ $isSaved ? 'Tersimpan di Wishlist' : 'Simpan Wishlist' }}"
                                                    class="h-11 px-3 rounded-xl {{ $isSaved ? 'bg-secondary-fixed text-secondary' : 'bg-surface-container-low hover:bg-surface-container text-secondary' }} flex items-center justify-center gap-1 font-label-sm text-label-sm font-bold transition-colors"
                                                >
                                                    <span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' {{ $isSaved ? 1 : 0 }};">favorite</span>
                                                    <span>{{ $isSaved ? 'Tersimpan di Wishlist' : 'Simpan Wishlist' }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        {{-- Pagination Bar --}}
                        <div dusk="pagination" class="mt-space-xl flex flex-col sm:flex-row items-center justify-between gap-space-md p-6 bg-surface-container-lowest rounded-2xl shadow-sm">
                            <div class="flex items-center gap-2" dusk="pagination-info">
                                <span class="font-label-md text-label-md text-on-surface-variant">
                                    Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }} • Menampilkan {{ $products->count() }} dari {{ $products->total() }} kriya
                                </span>
                            </div>

                            @if ($products->hasPages())
                                <nav class="flex items-center gap-2" role="navigation" aria-label="Pagination" dusk="pagination-links">
                                    @if ($products->onFirstPage())
                                        <span dusk="pagination-prev-disabled" class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-on-surface opacity-40 cursor-not-allowed">
                                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                        </span>
                                    @else
                                        <a href="{{ $products->previousPageUrl() }}" rel="prev" dusk="pagination-prev" class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                                        </a>
                                    @endif

                                    @for ($page = 1; $page <= $products->lastPage(); $page++)
                                        @if ($page == $products->currentPage())
                                            <span aria-current="page" dusk="pagination-page-{{ $page }}" class="w-9 h-9 rounded-lg bg-primary text-on-primary font-label-md text-label-md flex items-center justify-center shadow-sm">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $products->url($page) }}" dusk="pagination-page-{{ $page }}" class="w-9 h-9 rounded-lg bg-surface-container-lowest text-on-surface font-label-md text-label-md flex items-center justify-center hover:bg-surface-container transition-colors">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    @if ($products->hasMorePages())
                                        <a href="{{ $products->nextPageUrl() }}" rel="next" dusk="pagination-next" class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </a>
                                    @else
                                        <span dusk="pagination-next-disabled" class="w-9 h-9 rounded-lg bg-surface-container flex items-center justify-center text-on-surface opacity-40 cursor-not-allowed">
                                            <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                        </span>
                                    @endif
                                </nav>
                            @endif
                        </div>
                    @endif
                </section>
            </div>
        </div>
    </div>
@endsection
