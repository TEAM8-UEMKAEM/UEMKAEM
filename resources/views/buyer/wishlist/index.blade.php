@extends('layouts.buyer')

@section('title', 'Wishlist Saya - UEMKAEM Kriya Nusantara')

@section('content')
    @php
        $fallbackImages = [
            'https://lh3.googleusercontent.com/aida-public/AB6AXuDyELIYPSFM46_kESXgGHSzvwBLlDIPD_vKFQTGYCWQmJ_mu7628LGLQv5NDyhv69z2EQJB5a28KashW8PdJg6CLF1hGFGImkkPbbxdmyPdkibL1ZAau3kWh9GB4oRBJWpmzD0z63LlyMM5ENo7zU3eVlMDjwnauk6TG-cYX03TuI-FDmv5c4iM7IRKpjt6hRUpMpjnmlGl6XcpwvsUTOKiOvORueoOj1dijrpJvQ',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuBsVFUAHIQfoByPH-VA8pyBqOjZgy8DG0sSeCZu28SBA6qrkg0U69P9oUv0bKfE5ycEvPZ4cDqrQ6y_NMOvlHGzIlJ1z1avLwW1TXGtnhSTzsuHRBwEwNrbtFH363PnKOkDGI7P9YpNT6lGL44AGYut0q5inY8SCAt5jgkF4gbbf1jIyRHWQKkp4dwmlFIlDeYbHSNHYegiK4yzI3cmvSYG5cK-SFFf6SF2jcYLQA',
            'https://lh3.googleusercontent.com/aida-public/AB6AXuAah_TmASOq1lWpmshCQTOkLJRmGrSZ5LnDUK2e7NZwgc-xw8LaGKW_TLSbPWwR_zURuyGeQ3YC8vDbvOMZL2FBU_n4tZvELXYkfVMdPFFwFA5Ij_B8g8HMw6seF4JimJA-sXK9vvYJyw26ze5UuPOgtG5IjlEiZMnKxbzIYSQrmJt0YhJS4Cz8_KKH81ZtIo6XC3wn3xvXKPftF9SI2Qnn5b0P8yP5Sv0-3Y7dXg',
        ];
    @endphp

    <div class="flex flex-col w-full">
        <div class="w-full max-w-7xl mx-auto px-margin py-space-xl">
            {{-- Title & Contextual Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md mb-space-lg" dusk="wishlist-header">
                <div class="flex flex-col max-w-2xl">
                    <div class="flex items-center gap-space-sm mb-space-xs">
                        <span class="font-label-sm text-label-sm uppercase tracking-widest text-secondary font-bold">Koleksi Kurasi Pribadi</span>
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant">Sesi Aktif</span>
                    </div>
                    <h1 class="font-headline-lg text-headline-lg text-on-surface tracking-tight">Wishlist Saya</h1>
                    <p class="font-body-lg text-body-lg text-on-surface-variant mt-1">
                        Daftar produk kriya favorit yang Anda simpan untuk dikoleksi atau dibeli nanti.
                    </p>
                </div>

                {{-- Live Buyer Impact Capsule --}}
                <div class="flex items-center gap-space-md p-space-md bg-surface-container rounded-xl shadow-sm max-w-md">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">handshake</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-label-sm text-label-sm text-primary font-bold uppercase tracking-wide">Dampak Dukungan Anda</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant">
                            {{ $wishlists->count() }} karya pengrajin lokal terhubung langsung dengan Anda tanpa tengkulak.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Control Ribbon / Top Bar --}}
            <div class="bg-surface-container-lowest p-space-md rounded-xl shadow-sm mb-space-xl flex flex-wrap items-center justify-between gap-space-md">
                <div class="flex items-center gap-space-md flex-wrap">
                    <div dusk="wishlist-count-badge" class="inline-flex items-center gap-2 bg-secondary/10 text-secondary px-4 py-1.5 rounded-full font-label-lg text-label-lg font-bold">
                        <span class="material-symbols-outlined text-[18px]">favorite</span>
                        <span id="saved-counter">{{ $wishlists->count() }} Produk Tersimpan</span>
                    </div>
                    <span class="text-outline-variant hidden sm:inline">•</span>
                    <a href="{{ route('products.index') }}" dusk="back-to-catalog" class="font-label-md text-label-md text-primary hover:text-primary-container font-semibold inline-flex items-center gap-1.5 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">storefront</span>
                        <span>Lanjut Jelajahi Katalog</span>
                    </a>
                </div>
            </div>

            {{-- Main Active Wishlist Content Area (PBI #25) --}}
            @if ($wishlists->isEmpty())
                {{-- Empty State View --}}
                <div dusk="wishlist-empty" class="w-full py-space-xl">
                    <div class="bg-surface-container-lowest max-w-xl mx-auto rounded-xl p-space-xl text-center shadow-sm flex flex-col items-center">
                        <div class="w-24 h-24 mb-space-md rounded-full bg-surface-container flex items-center justify-center text-primary-container">
                            <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 64 64">
                                <path d="M32 10v4m0 36v4m-22-22h4m36 0h4m-7.07-14.93l-2.83 2.83m-20.2 20.2l-2.83 2.83m0-25.86l2.83 2.83m20.2 20.2l2.83 2.83" opacity="0.3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                <path d="M32 46c-7.73 0-14-6.27-14-14s6.27-14 14-14 14 6.27 14 14-6.27 14-14 14z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"></path>
                                <path d="M32 24c-2.5 0-4.5 2-4.5 4.5 0 3.5 4.5 7.5 4.5 7.5s4.5-4 4.5-7.5c0-2.5-2-4.5-4.5-4.5z" fill="currentColor" fill-opacity="0.15" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                            </svg>
                        </div>
                        <h2 class="font-headline-md text-headline-md text-on-surface">Wishlist Anda Masih Kosong</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant max-w-md mt-2 mb-space-lg">
                            Belum ada produk favorit lain. Simpan karya artisan favorit Anda saat menjelajah agar mudah ditemukan kembali dan terus dukung keberlanjutan tradisi kriya Nusantara.
                        </p>
                        <a href="{{ route('products.index') }}" dusk="explore-catalog-btn" class="px-6 py-3 bg-primary hover:bg-primary-container text-on-primary font-label-lg text-label-lg font-semibold rounded-xl shadow-md transition-colors inline-flex items-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">explore</span>
                            <span>Mulai Penjelajahan Kriya</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="w-full" id="wishlist-grid-wrapper">
                    <div dusk="wishlist-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter" id="product-grid">
                        @foreach ($wishlists as $wishlistItem)
                            @php
                                $product = $wishlistItem->product;
                                if (! $product) {
                                    continue;
                                }
                                $isAvailable = $product->status === 'published';
                                $ft = (int) ($product->fair_trade_percent ?? 90);
                                $artisanName = $product->artisan?->name ?? $product->artisan_name ?? 'Perajin Nusantara';
                                $originName = $product->region?->name ?? $product->origin ?? 'Nusantara';
                                $imgSrc = !empty($product->image) ? $product->image : $fallbackImages[$loop->index % count($fallbackImages)];
                            @endphp

                            <article
                                dusk="wishlist-item-{{ $product->id }}"
                                class="group bg-surface-container-lowest rounded-xl flex flex-col justify-between overflow-hidden shadow-sm hover:shadow-md transition-all duration-300"
                            >
                                <div class="relative w-full aspect-[4/3] overflow-hidden bg-surface-container">
                                    <img
                                        src="{{ $imgSrc }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 {{ ! $isAvailable ? 'grayscale opacity-75' : '' }}"
                                    >
                                    <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                                        @if ($isAvailable)
                                            <span class="px-3 py-1 rounded-full bg-primary/85 backdrop-blur-sm text-on-primary font-label-sm text-label-sm font-semibold flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-[14px]">eco</span>
                                                {{ $product->category?->name ?? '100% Pewarna Alami' }}
                                            </span>
                                        @else
                                            <span dusk="product-unavailable-badge" class="px-3 py-1 rounded-full bg-error text-on-error font-label-sm text-label-sm font-semibold flex items-center gap-1 shadow-sm">
                                                <span class="material-symbols-outlined text-[14px]">block</span>
                                                Produk Tidak Tersedia
                                            </span>
                                        @endif
                                    </div>
                                    <span class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-surface/90 backdrop-blur-sm text-on-surface-variant font-label-sm text-label-sm font-medium">
                                        {{ $originName }}
                                    </span>
                                </div>

                                <div class="p-space-md flex flex-col flex-1">
                                    <div class="flex items-center gap-1.5 text-on-surface-variant mb-1">
                                        <span class="material-symbols-outlined text-[16px] text-primary">person_pin</span>
                                        <span class="font-body-sm text-body-sm font-medium">{{ $artisanName }} • {{ $originName }}</span>
                                    </div>
                                    <h3 dusk="wishlist-product-name-{{ $product->id }}" class="font-headline-sm text-headline-sm text-on-surface group-hover:text-primary transition-colors">
                                        {{ $product->name }}
                                    </h3>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-1 line-clamp-2">
                                        {{ $product->description ?: 'Karya kriya autentik nusantara dengan standar perdagangan berkeadilan (Fair-Trade).' }}
                                    </p>

                                    <div class="mt-auto pt-space-md flex items-center justify-between gap-2">
                                        <div class="flex flex-col">
                                            <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Harga Kriya</span>
                                            <span dusk="wishlist-product-price-{{ $product->id }}" class="font-headline-sm text-headline-sm text-primary font-bold">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full bg-surface-container font-label-sm text-label-sm text-primary-container font-semibold flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[14px] text-primary">verified</span>
                                            Fair-Price ({{ $ft }}%)
                                        </span>
                                    </div>
                                </div>

                                <div class="px-space-md pb-space-md pt-space-xs flex items-center gap-space-sm">
                                    @if ($isAvailable)
                                        <a
                                            href="{{ route('products.show', $product->slug) }}"
                                            dusk="wishlist-detail-link-{{ $product->id }}"
                                            class="flex-1 py-2.5 px-4 bg-primary text-on-primary font-label-md text-label-md font-bold rounded-xl text-center hover:bg-primary-container transition-colors shadow-sm flex items-center justify-center gap-2"
                                        >
                                            <span>Lihat Detail</span>
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </a>
                                    @else
                                        <button
                                            type="button"
                                            dusk="wishlist-buy-disabled-{{ $product->id }}"
                                            disabled
                                            class="flex-1 py-2.5 px-4 bg-surface-container-high text-on-surface-variant font-label-md text-label-md font-bold rounded-xl text-center opacity-60 cursor-not-allowed flex items-center justify-center gap-2"
                                        >
                                            <span>Produk Tidak Tersedia</span>
                                        </button>
                                    @endif

                                    <form method="POST" action="{{ route('wishlist.toggle', $product->slug) }}" class="shrink-0">
                                        @csrf
                                        <button
                                            type="submit"
                                            dusk="wishlist-remove-{{ $product->id }}"
                                            aria-label="Hapus dari Wishlist"
                                            title="Hapus dari Wishlist"
                                            class="btn-remove w-11 h-11 shrink-0 rounded-xl bg-surface-container hover:bg-error-container text-secondary hover:text-on-error-container flex items-center justify-center transition-colors"
                                        >
                                            <span class="material-symbols-outlined text-[20px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Curated Artisan Fair-Trade Commitment Banner --}}
            <div class="mt-space-xl p-space-xl bg-surface-container-low rounded-xl flex flex-col lg:flex-row items-center justify-between gap-space-lg">
                <div class="flex flex-col max-w-xl">
                    <div class="inline-flex items-center gap-1.5 text-secondary font-label-sm text-label-sm font-bold uppercase tracking-wider mb-2">
                        <span class="material-symbols-outlined text-[16px]">verified_user</span>
                        Prinsip Fair-Trade Kriya
                    </div>
                    <h3 class="font-headline-sm text-headline-sm text-primary font-bold">Setiap item di Wishlist Anda dibayar layak di hulu</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-2">
                        Sebagian besar dari nominal pembelian diteruskan langsung ke kelompok perajin desa, melestarikan teknik tenun, canting, dan ukir lintas generasi.
                    </p>
                </div>
                <div class="flex items-center gap-space-md shrink-0">
                    <div class="text-center px-4 py-3 bg-surface-container-lowest rounded-xl shadow-sm">
                        <span class="font-headline-md text-headline-md text-primary block font-bold">100%</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Bahan Alami</span>
                    </div>
                    <div class="text-center px-4 py-3 bg-surface-container-lowest rounded-xl shadow-sm">
                        <span class="font-headline-md text-headline-md text-secondary block font-bold">0%</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Tengkulak</span>
                    </div>
                    <div class="text-center px-4 py-3 bg-surface-container-lowest rounded-xl shadow-sm">
                        <span class="font-headline-md text-headline-md text-tertiary-container block font-bold">SDG 8</span>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Upah Layak</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
