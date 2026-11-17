@extends('layouts.buyer')

@section('title', $product->name . ' - Detail Kriya Nusantara')

@section('content')
    @php
        $artisan = $product->artisan;
        $artisanName = $artisan?->name ?? $product->artisan_name ?? 'Perajin Nusantara';
        $workshopName = $artisan?->workshop_name ?? ($artisan?->specialty ? 'Sanggar ' . $artisan->specialty : 'Sanggar Kriya Mandiri');
        $regionName = $product->region?->name ?? $product->origin ?? $artisan?->location ?? 'Nusantara';
        $artisanStory = $artisan?->story ?? 'Dikerjakan secara manual menggunakan teknik warisan leluhur oleh perajin lokal dengan bahan alami pilihan.';
        $isVerified = $artisan ? (bool) ($artisan->is_verified && $artisan->verification_status !== 'unverified') : true;

        $fairPrice = $product->fairPrice;
        $ftPercent = (int) ($fairPrice?->artisan_share_percent ?? $product->fair_trade_percent ?? 90);
        $minFairPrice = (int) ($fairPrice?->min_price ?? round($product->price * 0.85));
        $maxFairPrice = (int) ($fairPrice?->max_price ?? round($product->price * 1.15));
        $recommendedPrice = (int) ($fairPrice?->recommended_price ?? round(($minFairPrice + $maxFairPrice) / 2));
        $fairPriceNotes = $fairPrice?->notes ?? 'Sesuai estimasi bahan alami serta pengerjaan manual oleh artisan lokal. Bebas pemotongan rantai tengkulak.';
        $isWithinFairRange = $product->price >= $minFairPrice && $product->price <= $maxFairPrice;

        $artisanShareNominal = (int) round($product->price * $ftPercent / 100);
        $logisticPercent = max(0, 100 - $ftPercent);
        $isSaved = (bool) ($inWishlist ?? false);

        $mainImage = !empty($product->image)
            ? $product->image
            : 'https://lh3.googleusercontent.com/aida-public/AB6AXuB1zJp6TajXNbAFHITBLmeGNleFMfBgHnC2T52UQNDkuH9v3Ducxov5G_P9rJymF2H32ziQLXfcXZ5pxaZ2LZwjdvNQGwThoTQnSNDsa6AOoIrxXUR-foRX9uPgo7yIwAfotsGVZwX5C2R0NWKNOy_ef8Oj9llR4hnpC2P4xYmYBgz8s8GrOfqqs_FmvWn6R_bMX5qSJulL_O9_Zciwu4AyFnkK2qqe_rPLjrH2DA';
    @endphp

    <div class="flex flex-col w-full">
        <div class="max-w-7xl mx-auto w-full px-margin py-space-md">
            {{-- Breadcrumb Navigation --}}
            <nav aria-label="Breadcrumb" dusk="detail-breadcrumb" class="flex items-center gap-space-xs text-on-surface-variant mb-space-lg">
                <a class="font-label-md text-label-md hover:text-primary transition-colors" href="{{ route('products.index') }}" dusk="back-to-catalog">Jelajahi</a>
                <span class="material-symbols-outlined text-[16px] text-outline">chevron_right</span>
                @if ($product->category)
                    <a class="font-label-md text-label-md hover:text-primary transition-colors" href="{{ route('products.index', ['category' => $product->category->slug]) }}">
                        {{ $product->category->name }}
                    </a>
                    <span class="material-symbols-outlined text-[16px] text-outline">chevron_right</span>
                @endif
                <span class="font-label-md text-label-md text-primary font-semibold truncate">{{ $product->name }}</span>
            </nav>

            {{-- Main Two-Column Product Detail Showcase (PBI #24) --}}
            <div dusk="product-detail" class="grid grid-cols-1 lg:grid-cols-12 gap-gutter lg:gap-space-xl items-start">
                {{-- Left Panel: Gallery & Process Visuals --}}
                <div class="lg:col-span-6 flex flex-col gap-space-md lg:sticky lg:top-24">
                    <div dusk="product-image" class="relative overflow-hidden rounded-xl bg-surface-container-low shadow-sm aspect-[4/3] sm:aspect-[1/1] w-full group">
                        <img
                            id="featured-image"
                            src="{{ $mainImage }}"
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                        >
                        {{-- Floating Feature Badges --}}
                        <div class="absolute top-4 left-4 flex flex-wrap gap-space-xs">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-primary/90 backdrop-blur-md text-on-primary font-label-sm text-label-sm shadow-sm">
                                <span class="material-symbols-outlined text-[15px] text-tertiary-fixed">eco</span>
                                100% Pewarna Alami
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-primary font-label-sm text-label-sm shadow-sm">
                                <span class="material-symbols-outlined text-[15px] text-secondary">verified</span>
                                Karya Asli {{ $regionName }}
                            </span>
                        </div>
                        {{-- Zoom Micro-action Button --}}
                        <button aria-label="Perbesar gambar" class="absolute bottom-4 right-4 w-10 h-10 rounded-full bg-surface-container-lowest/90 backdrop-blur-md text-on-surface hover:text-primary flex items-center justify-center shadow-md transition-colors" type="button">
                            <span class="material-symbols-outlined text-[20px]">zoom_in</span>
                        </button>
                    </div>

                    {{-- Thumbnail Switcher Row --}}
                    <div class="grid grid-cols-4 gap-space-sm" id="thumb-container">
                        <button class="thumb-btn active relative aspect-square rounded-lg overflow-hidden bg-surface-container transition-all ring-2 ring-primary" onclick="switchThumb(0)" type="button">
                            <img class="w-full h-full object-cover" alt="Detail Tenun" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBa7g4lqWn9D9Qx1dotOjodlOSAwSK3cKOkTUdcgDKJjfBe0MMDNBV9d7mIjg7N-Mhv3snzZVfpEFFgRaaDaIa9ZTbiOHUTLIakIKvMCRrUtxVVb9BBCpcrxgXWsPnAdhW5Rkqwjz1dC0_5RErPmEB9sOPVp02XCzFgFYyOVQ3pOZDLNLo5CZXqxDXRrmtWVyVlpxLULbAYHzefJFi8hOipP2w2I0HYmBLGNllrkA">
                            <span class="absolute inset-x-0 bottom-0 bg-primary/80 text-on-primary font-label-sm text-[10px] py-0.5 text-center truncate px-1">Detail Karya</span>
                        </button>
                        <button class="thumb-btn relative aspect-square rounded-lg overflow-hidden bg-surface-container opacity-70 hover:opacity-100 transition-all" onclick="switchThumb(1)" type="button">
                            <img class="w-full h-full object-cover" alt="Pewarna Alami" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCMrrKXVIqJA9-RahPzT6_AG2bBvNDJtSLSLNMkBxFL0wvncK0_xKE0QWQImUO7WFsQQuqj8jQfQSQeOhqDQE-eHnSzCZd5GyseBzaP9wT8B6U6yWeVx_CFGLfdHt6AdQCOfXKTJA-Chy1Ye_bvZ5MHN35Yeg6yTXJSAaaA7hhdCNJCHNrQBj0Wba_FczsfzwlJ4_PBxaYr-4T3diVhpU8aGRmSxM6Osl67298SKg">
                            <span class="absolute inset-x-0 bottom-0 bg-primary/80 text-on-primary font-label-sm text-[10px] py-0.5 text-center truncate px-1">Pewarna Alami</span>
                        </button>
                        <button class="thumb-btn relative aspect-square rounded-lg overflow-hidden bg-surface-container opacity-70 hover:opacity-100 transition-all" onclick="switchThumb(2)" type="button">
                            <img class="w-full h-full object-cover" alt="Serat Benang" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC15uMUHeBg8Sx6vwMPGSsxjGfwG-2-Mb9uNbp2DUMBufZF8k_kBAw0XWKK-bVd6dkmEr3NfS8k49k5HF6iwkURsejAH_pt_LOGYU0I26YMdJOTsO_Izsw1Ciyhj5bFcifjaaxIotGQrylic5ehiFNlMQVNwqxfC8l2d0xOHhBHQt3Mmx3YOnnlPx9F2ZnO4lzL2Xr7Sevj0q2zTpw9FvmnM7ePV0dyx3Erqp3-qg">
                            <span class="absolute inset-x-0 bottom-0 bg-primary/80 text-on-primary font-label-sm text-[10px] py-0.5 text-center truncate px-1">Material Asli</span>
                        </button>
                        <button class="thumb-btn relative aspect-square rounded-lg overflow-hidden bg-surface-container opacity-70 hover:opacity-100 transition-all" onclick="switchThumb(3)" type="button">
                            <img class="w-full h-full object-cover" alt="Proses Kriya" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDT26_J2cbyhXooBi4Lpxpf9WmfQMk1U6Gs0Pb9OMkeD5RsiZ0nqXiGEphieDvA3SfzPHAv2tw3OxWsst4wP-QzhSRZ0z4c3e0mbZ8ExxaRkY2eLxSsQvAXjj0DbRG8oipwRimD5IE-atKd82mLaXVcaBNHZdHn8UPupcxhnm5Ro1T-V77HcHvBK8GG1P_c7uXRxlK6GKq5XKddSt-Y4wf0B-GXYhwkVyuLMDtDKg">
                            <span class="absolute inset-x-0 bottom-0 bg-primary/80 text-on-primary font-label-sm text-[10px] py-0.5 text-center truncate px-1">Proses Kriya</span>
                        </button>
                    </div>

                    {{-- Geographic Provenance Chip --}}
                    <div class="p-space-md rounded-xl bg-surface-container-low flex items-center justify-between gap-space-sm">
                        <div class="flex items-center gap-space-sm">
                            <span class="material-symbols-outlined text-secondary text-[22px]">explore</span>
                            <div>
                                <p class="font-label-md text-label-md text-on-surface font-semibold">{{ $workshopName }}</p>
                                <p class="font-body-sm text-body-sm text-on-surface-variant">
                                    <span dusk="product-origin">{{ $regionName }}</span> • Warisan Budaya Nusantara
                                </p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 rounded bg-surface-container-high text-primary font-label-sm text-label-sm font-semibold">TERVERIFIKASI</span>
                    </div>
                </div>

                {{-- Right Panel: Specifications, Fair-Trade Breakdown, Action CTAs --}}
                <div class="lg:col-span-6 flex flex-col gap-space-md">
                    {{-- Header Info --}}
                    <div class="flex flex-col gap-space-xs">
                        <div class="flex items-center justify-between gap-space-sm">
                            <span dusk="product-category" class="inline-flex items-center px-3 py-1 rounded-full bg-primary-container/15 text-primary-container font-label-sm text-label-sm font-bold tracking-wide">
                                {{ $product->category?->name ?? 'Kriya Nusantara' }}
                            </span>
                            <div class="flex items-center gap-1 text-on-surface-variant font-label-sm text-label-sm" dusk="product-stock">
                                <span class="material-symbols-outlined text-[16px] text-tertiary">inventory_2</span>
                                @if (($product->stock ?? 0) > 0)
                                    <span>Stok Tersedia: {{ $product->stock }} Pcs</span>
                                @else
                                    <span>Stok Habis</span>
                                @endif
                            </div>
                        </div>

                        <h1 dusk="product-title" class="font-headline-lg text-headline-lg text-on-surface tracking-tight mt-1">
                            {{ $product->name }}
                        </h1>

                        {{-- Artisan Profile Link & Stats --}}
                        <div class="flex flex-wrap items-center gap-space-sm pt-space-xs">
                            <div class="flex items-center gap-2">
                                <span class="font-label-lg text-label-lg text-primary font-bold">Oleh {{ $artisanName }} ({{ $regionName }})</span>
                                @if ($isVerified)
                                    <span class="material-symbols-outlined text-primary text-[18px]" title="Terverifikasi UEMKAEM">verified</span>
                                @endif
                            </div>
                            <span class="text-outline-variant">•</span>
                            <div class="flex items-center gap-1 font-label-md text-label-md text-on-surface">
                                <span class="material-symbols-outlined text-secondary text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                <span class="font-bold">4.9</span>
                                <span class="text-on-surface-variant">(38 ulasan)</span>
                            </div>
                            <span class="text-outline-variant">•</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant">Kurasi Langsung Sanggar</span>
                        </div>
                    </div>

                    {{-- Price & Fair-Trade Card (Blok Fair-Price) --}}
                    <div dusk="fair-price-section" class="rounded-xl bg-surface-container p-space-md flex flex-col gap-space-md shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-space-xs">
                            <div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider block">Harga Kurasi Pengrajin</span>
                                <span dusk="product-price" class="font-headline-xl text-headline-xl text-primary font-extrabold tracking-tight">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <span dusk="fair-price-indicator" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-surface-container-lowest text-primary font-label-md text-label-md shadow-sm self-start sm:self-auto font-bold">
                                <span class="material-symbols-outlined text-[18px] text-tertiary">balance</span>
                                {{ $isWithinFairRange ? 'Fair-Price Approved' : 'Transparansi Fair-Trade Terbuka' }}
                            </span>
                        </div>

                        {{-- Fair-Price Guideline Banner --}}
                        <div dusk="fair-price-range" class="rounded-lg bg-surface-container-lowest p-space-md flex flex-col gap-space-xs">
                            <div class="flex flex-wrap items-center justify-between gap-2 text-primary font-label-lg text-label-lg font-bold">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px] text-tertiary">check_circle</span>
                                    <span>
                                        Panduan Harga Adil:
                                        <span dusk="fair-price-min">Rp {{ number_format($minFairPrice, 0, ',', '.') }}</span>
                                        -
                                        <span dusk="fair-price-max">Rp {{ number_format($maxFairPrice, 0, ',', '.') }}</span>
                                    </span>
                                </div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">
                                    Rekomendasi: <strong dusk="fair-price-recommended" class="text-primary">Rp {{ number_format($recommendedPrice, 0, ',', '.') }}</strong>
                                </span>
                            </div>

                            <p dusk="fair-price-notes" class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                {{ $fairPriceNotes }}
                            </p>

                            {{-- Visual Distribution Bar --}}
                            <div class="mt-space-xs flex flex-col gap-1.5">
                                <div class="h-2.5 w-full bg-surface-container-high rounded-full overflow-hidden flex">
                                    <div class="h-full bg-primary-container" style="width: {{ $ftPercent }}%;" title="{{ $ftPercent }}% Dana Langsung Artisan"></div>
                                    <div class="h-full bg-secondary-container" style="width: {{ $logisticPercent }}%;" title="{{ $logisticPercent }}% Logistik &amp; Kurasi"></div>
                                </div>
                                <div class="flex justify-between items-center font-label-sm text-label-sm">
                                    <span class="text-primary font-bold flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-primary-container"></span>
                                        <span dusk="fair-trade-percent">{{ $ftPercent }}% Hak Perajin</span>
                                        (Rp {{ number_format($artisanShareNominal, 0, ',', '.') }})
                                    </span>
                                    <span class="text-on-surface-variant flex items-center gap-1">
                                        <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                                        {{ $logisticPercent }}% Logistik &amp; Kurasi Mutu
                                    </span>
                                </div>
                            </div>

                            {{-- Transparency Interactive Accordion Details --}}
                            <details class="group mt-space-xs">
                                <summary class="list-none flex items-center justify-between cursor-pointer py-1 text-primary font-label-sm text-label-sm font-bold hover:text-primary-container">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[16px]">info</span>
                                        Transparansi Rincian Komponen Biaya
                                    </span>
                                    <span class="material-symbols-outlined text-[16px] transition-transform group-open:rotate-180">expand_more</span>
                                </summary>
                                <div class="pt-space-xs text-on-surface-variant font-body-sm text-body-sm space-y-1">
                                    <div class="flex justify-between py-0.5">
                                        <span>Upah Kerja Pengerjaan Manual ({{ $artisanName }}):</span>
                                        <span class="font-semibold text-on-surface">Rp {{ number_format((int) round($product->price * 0.60), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-0.5">
                                        <span>Bahan Mentah Alami Lokal:</span>
                                        <span class="font-semibold text-on-surface">Rp {{ number_format((int) round($product->price * ($ftPercent - 60) / 100), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between py-0.5">
                                        <span>Kurasi Mutu &amp; Kemasan Ramah Lingkungan:</span>
                                        <span class="font-semibold text-on-surface">Rp {{ number_format((int) round($product->price * $logisticPercent / 100), 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </details>
                        </div>
                    </div>

                    {{-- Product Description & Specs --}}
                    <div dusk="product-description" class="flex flex-col gap-space-sm">
                        <p class="font-body-md text-body-md text-on-surface leading-relaxed">
                            {{ $product->description ?: 'Mahakarya kriya nusantara yang dikerjakan secara autentik menggunakan material alami berkualitas tinggi.' }}
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-xs">
                            <div class="p-space-sm rounded-lg bg-surface-container-low flex flex-col">
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Material</span>
                                <span class="font-label-md text-label-md text-on-surface font-semibold mt-0.5">Bahan Alami Murni</span>
                            </div>
                            <div class="p-space-sm rounded-lg bg-surface-container-low flex flex-col">
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Teknik</span>
                                <span class="font-label-md text-label-md text-on-surface font-semibold mt-0.5">100% Buatan Tangan</span>
                            </div>
                            <div class="p-space-sm rounded-lg bg-surface-container-low flex flex-col">
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Asal Daerah</span>
                                <span class="font-label-md text-label-md text-on-surface font-semibold mt-0.5">{{ $regionName }}</span>
                            </div>
                            <div class="p-space-sm rounded-lg bg-surface-container-low flex flex-col">
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Ketersediaan</span>
                                <span class="font-label-md text-label-md text-primary font-semibold mt-0.5">Ready Stock</span>
                            </div>
                        </div>
                    </div>

                    {{-- Artisan Story Card (Blok Pengrajin) --}}
                    <div dusk="artisan-section" class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex items-start gap-space-md">
                        <img
                            class="w-14 h-14 rounded-full object-cover shrink-0 shadow-sm"
                            alt="{{ $artisanName }}"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXImsG44kiBVA241byoBpMEG-tkW2-r2MaMIyDqLpRBVljLv0grOuCLmbMol5dA--2xLfa0U9e8pRHYg1wR9xvZ1tF3xFX-fP9m5-ueERq7p6-BuqEMuCrAUrRi92hpduWlaaBFOajXcIJTQwMe5uY8tBhIkmV8YvqNIyFz6Sl3jxJUQ40xxPHZcZgl-lJ7hAu3iCjImepMyrz1sgNN8z3XTb90MxTZwXF7OZA7w"
                        >
                        <div class="flex flex-col gap-1 flex-1">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span dusk="artisan-name" class="font-label-lg text-label-lg text-on-surface font-bold">{{ $artisanName }}</span>
                                    <span class="font-label-sm text-label-sm text-secondary bg-secondary-fixed/50 px-2 py-0.5 rounded-full font-bold">Master Artisan</span>
                                </div>

                                @if ($isVerified)
                                    <span dusk="artisan-verified-badge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-primary-container/15 text-primary font-label-sm text-label-sm font-bold">
                                        <span class="material-symbols-outlined text-[14px]">verified</span>
                                        Pengrajin Terverifikasi
                                    </span>
                                @endif
                            </div>

                            <p class="font-label-sm text-label-sm text-on-surface-variant">
                                <span dusk="artisan-workshop">{{ $workshopName }}</span> • <span dusk="artisan-region">{{ $regionName }}</span>
                            </p>

                            <p dusk="artisan-story" class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed mt-1">
                                "{{ $artisanStory }}"
                            </p>
                        </div>
                    </div>

                    {{-- Action Buttons & CTA Section --}}
                    <div class="flex flex-col gap-space-sm pt-space-xs">
                        <div class="flex flex-col sm:flex-row items-stretch gap-space-sm">
                            {{-- Quantity Selector --}}
                            <div class="flex items-center justify-between sm:justify-center bg-surface-container-low rounded-xl px-2 py-1 min-w-[130px] shrink-0">
                                <button aria-label="Kurangi jumlah" class="w-10 h-10 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors" onclick="updateQty(-1)" type="button">
                                    <span class="material-symbols-outlined text-[18px]">remove</span>
                                </button>
                                <span class="font-label-lg text-label-lg font-bold text-on-surface px-4" id="qty-val">1</span>
                                <button aria-label="Tambah jumlah" class="w-10 h-10 rounded-lg flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors" onclick="updateQty(1)" type="button">
                                    <span class="material-symbols-outlined text-[18px]">add</span>
                                </button>
                            </div>

                            {{-- Primary Add to Cart CTA --}}
                            <button
                                type="button"
                                dusk="add-to-cart-btn"
                                onclick="triggerAddToCart(event)"
                                class="flex-1 bg-primary-container hover:bg-primary text-on-primary font-label-lg text-label-lg font-bold py-3.5 px-space-lg rounded-xl shadow-sm flex items-center justify-center gap-2 transition-all transform active:scale-[0.99]"
                            >
                                <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                                <span>Tambah ke Keranjang</span>
                            </button>

                            {{-- Wishlist Button (PBI #25) --}}
                            <form method="POST" action="{{ route('wishlist.toggle', $product->slug) }}" class="flex">
                                @csrf
                                <button
                                    type="submit"
                                    dusk="wishlist-toggle"
                                    data-wishlisted="{{ $isSaved ? 'true' : 'false' }}"
                                    aria-label="{{ $isSaved ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist' }}"
                                    class="w-full py-3.5 px-space-md rounded-xl {{ $isSaved ? 'bg-secondary-fixed text-secondary' : 'bg-surface-container-lowest text-secondary hover:bg-secondary-fixed/30' }} font-label-lg text-label-lg font-bold flex items-center justify-center gap-2 shadow-sm transition-colors"
                                >
                                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' {{ $isSaved ? 1 : 0 }};">
                                        {{ $isSaved ? 'favorite' : 'favorite_border' }}
                                    </span>
                                    <span>{{ $isSaved ? 'Tersimpan di Wishlist' : 'Simpan ke Wishlist' }}</span>
                                </button>
                            </form>
                        </div>

                        <div class="flex items-center justify-center gap-space-sm text-on-surface-variant font-label-sm text-label-sm py-1">
                            <span class="material-symbols-outlined text-[16px] text-tertiary">inventory</span>
                            <span>Kemasan eksklusif kotak anyaman besek lontar &amp; sertifikat keaslian fisik</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Section: Jaminan Etis UEMKAEM --}}
            <section class="mt-space-xl pt-space-xl">
                <div class="rounded-xl bg-surface-container-low p-space-lg lg:p-space-xl">
                    <div class="max-w-2xl mb-space-lg">
                        <span class="font-label-sm text-label-sm text-secondary uppercase font-bold tracking-widest block mb-1">Standar Etika Marketplace</span>
                        <h2 class="font-headline-md text-headline-md text-primary">Jaminan Etis UEMKAEM</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                            Setiap karya kriya bukan sekadar komoditas, melainkan penjaga ketahanan ekonomi keluarga perajin dan warisan nenek moyang nusantara.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-space-md">
                        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col gap-space-xs">
                            <div class="w-12 h-12 rounded-xl bg-primary-container/10 flex items-center justify-center text-primary-container mb-1">
                                <span class="material-symbols-outlined text-[26px]">verified_user</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Fair-Trade Certified Seal</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                Jaminan 100% transparansi harga. Sistem kami mengaudit pembayaran langsung ke rekening kelompok perajin tanpa pemotongan tengkulak.
                            </p>
                        </div>
                        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col gap-space-xs">
                            <div class="w-12 h-12 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary mb-1">
                                <span class="material-symbols-outlined text-[26px]">workspace_premium</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Garansi 100% Kriya Asli</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                Dikerjakan penuh secara autentik oleh tangan terampil perajin lokal disertai sertifikat keaslian daerah asal.
                            </p>
                        </div>
                        <div class="rounded-xl bg-surface-container-lowest p-space-md shadow-sm flex flex-col gap-space-xs">
                            <div class="w-12 h-12 rounded-xl bg-tertiary-container/15 flex items-center justify-center text-tertiary-container mb-1">
                                <span class="material-symbols-outlined text-[26px]">local_shipping</span>
                            </div>
                            <h3 class="font-headline-sm text-headline-sm text-on-surface font-bold">Pengiriman Aman &amp; Lestari</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                Pengemasan ramah lingkungan bebas plastik sekali pakai dengan asuransi penuh perlindungan perjalanan ke seluruh Indonesia.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <script>
        let currentQuantity = 1;
        const maxQuantity = {{ max(1, (int) ($product->stock ?? 10)) }};

        function updateQty(change) {
            currentQuantity += change;
            if (currentQuantity < 1) currentQuantity = 1;
            if (currentQuantity > maxQuantity) currentQuantity = maxQuantity;
            const qtyEl = document.getElementById('qty-val');
            if (qtyEl) qtyEl.innerText = currentQuantity;
        }

        function switchThumb(index) {
            const btns = document.querySelectorAll('.thumb-btn');
            btns.forEach((btn, idx) => {
                if (idx === index) {
                    btn.classList.add('ring-2', 'ring-primary', 'opacity-100');
                    btn.classList.remove('opacity-70');
                } else {
                    btn.classList.remove('ring-2', 'ring-primary', 'opacity-100');
                    btn.classList.add('opacity-70');
                }
            });
        }

        function triggerAddToCart(event) {
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">check</span><span>Berhasil Ditambahkan!</span>`;
            setTimeout(() => {
                btn.innerHTML = originalText;
            }, 1500);
        }
    </script>
@endsection
