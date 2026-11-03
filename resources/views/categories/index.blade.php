@extends('layouts.app')

@section('title', 'Kategori Kriya - Ragam Warisan Budaya')

@section('content')
    @php
        use Illuminate\Support\Str;

        // Dua kategori pertama tampil sebagai kartu besar (kartu pertama lebih lebar).
        $featuredCount = 2;

        // Grup untuk filter chip (kolom opsional `group` pada tabel categories).
        $groups = $categories->pluck('group')->filter()->unique()->values();

        // Angka ringkasan. Kirim $summary dari controller untuk menimpa nilai bawaan.
        $branches = $summary['branches'] ?? 16;
        $artisans = $summary['artisans'] ?? '1.625+';
        $families = $summary['families'] ?? '1.625';
        $provinces = $summary['provinces'] ?? 38;
        $womenShare = $summary['women'] ?? '78%';
        $quarter = $summary['quarter'] ?? 'Q1 2025';
    @endphp

    <div class="kt-page">

        {{-- ================= HERO ================= --}}
        <section class="kt-hero" aria-labelledby="kt-hero-title">
            <div>
                <span class="kt-eyebrow kt-eyebrow-pill">
                    <i class="kt-dot"></i>ENSIKLOPEDI &amp; KURASI {{ $branches }} RAGAM TRADISI KRIYA
                </span>

                <h1 id="kt-hero-title">Warisan Seni Kriya Nusantara yang Hidup &amp; Berdaya</h1>

                <p class="kt-intro">
                    Dari tenun ikat berpewarna indigo alami di lembah NTT hingga ukiran kayu pusaka leluhur
                    Toraja dan Asmat. Jelajahi {{ $branches }} disiplin kriya etnik terverifikasi berkeadilan
                    upah (fair-trade) yang memberdayakan lebih dari {{ $families }} keluarga perajin di
                    pelosok nusantara.
                </p>

                <div class="kt-trust-row">
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 4.5-3.2 8.2-8 9-4.8-.8-8-4.5-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg>
                        100% Sertifikasi Keaslian Adat
                    </span>
                    <span class="kt-trust-orange">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 18h4v3H3z"/><path d="M7 19l5 1 7-3a2 2 0 0 0-2-3l-4 1"/><circle cx="14" cy="6" r="3"/><path d="M8 9h1M18 9h1"/></svg>
                        SDG 8.3 Upah Layak Terbuka
                    </span>
                    <span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 19c0-9 5-14 15-14 0 10-5 15-14 15"/><path d="M5 19c3-5 6-8 10-10"/></svg>
                        Bahan Alami &amp; Nir-Limbah
                    </span>
                </div>
            </div>

            <aside class="kt-summary" aria-label="Ringkasan ekosistem kriya">
                <div class="kt-summary-head">
                    <strong>Ringkasan Ekosistem Kriya</strong>
                    <span class="kt-quarter">{{ $quarter }}</span>
                </div>

                <div class="kt-summary-grid">
                    <div class="kt-summary-tile">
                        <strong>{{ $branches }}</strong>
                        <span>Cabang Tradisi Adat</span>
                    </div>
                    <div class="kt-summary-tile">
                        <strong>{{ $artisans }}</strong>
                        <span>Artisan Terverifikasi</span>
                    </div>
                    <div class="kt-summary-tile">
                        <strong class="kt-orange">{{ $provinces }}</strong>
                        <span>Provinsi Terjangkau</span>
                    </div>
                    <div class="kt-summary-tile">
                        <strong>{{ $womenShare }}</strong>
                        <span>Pengrajin Perempuan</span>
                    </div>
                </div>

                <a href="#peta-sebaran" class="kt-button kt-button-primary kt-button-block">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-6-5.2-6-10a6 6 0 0 1 12 0c0 1.6-.6 3.200-1.600 4.600"/><circle cx="12" cy="11" r="2"/><circle cx="17" cy="17" r="3"/><path d="M19.200 19.200L21 21"/></svg>
                    Buka Peta Sebaran Kriya
                </a>
            </aside>
        </section>

        {{-- ================= KATALOG KATEGORI ================= --}}
        <section aria-labelledby="kt-catalog-title">
            <div class="kt-section-top">
                <div>
                    <span class="kt-eyebrow">KURASI KOLEKSI</span>
                    <h2 id="kt-catalog-title">{{ $categories->count() }} Kategori Warisan Utama</h2>
                </div>

                <div class="kt-chip-row" role="group" aria-label="Filter kategori">
                    <button type="button" class="kt-chip is-active" data-filter="all">
                        Semua Kategori ({{ $categories->count() }})
                    </button>
                    @foreach ($groups as $group)
                        <button type="button" class="kt-chip" data-filter="{{ Str::slug($group) }}">{{ $group }}</button>
                    @endforeach
                </div>
            </div>

            <div class="kt-category-grid" id="kt-category-grid">
                @forelse ($categories as $category)
                    @php
                        $isFeatured = $loop->index < $featuredCount;
                        $isWide = $loop->first;

                        $tags = $category->tags ?? [];
                        if (! is_array($tags)) {
                            $tags = array_filter(array_map('trim', explode(',', (string) $tags)));
                        }

                        $artisanCount = $category->artisans_count ?? null;
                        $countNumber = $artisanCount ?? $category->products_count;
                        $url = route('products.index', ['kategori' => $category->slug]);
                    @endphp

                    <article
                        class="kt-category-card {{ $isFeatured ? 'kt-category-featured' : '' }} {{ $isWide ? 'kt-category-wide' : '' }}"
                        data-group="{{ Str::slug($category->group ?? '') }}"
                    >
                        <div class="kt-category-media">
                            @if ($category->image)
                                <img
                                    src="{{ route('categories.image', $category->id) }}"
                                    alt="{{ $category->name }}"
                                    loading="{{ $isFeatured ? 'eager' : 'lazy' }}"
                                >
                            @else
                                <div class="kt-image-fallback">{{ $category->name }}</div>
                            @endif

                            <div class="kt-image-shade"></div>

                            @if ($isFeatured && ! empty($category->badge))
                                <span class="kt-category-tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.500 2.500M15.500 15.500L18 18M18 6l-2.500 2.500M8.500 15.500L6 18"/></svg>
                                    {{ $category->badge }}
                                </span>
                            @endif

                            <div class="kt-image-title">
                                @if (! empty($category->regions))
                                    <small>SENTRA: {{ Str::upper($category->regions) }}</small>
                                @endif
                                <h3>{{ $category->tagline ?? $category->name }}</h3>
                            </div>
                        </div>

                        <div class="kt-category-content">
                            <p>{{ $category->description }}</p>

                            @if ($isFeatured && count($tags))
                                <div class="kt-tag-row">
                                    @foreach ($tags as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <div class="kt-category-footer">
                                @if ($isFeatured)
                                    <span class="kt-count">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.500"/><path d="M3 20c0-3.500 2.700-6 6-6s6 2.500 6 6"/><path d="M15 14.500c3 0 6 1.500 6 5"/></svg>
                                        <span><b>{{ $countNumber }}</b> {{ $artisanCount !== null ? 'Artisan Mitra Aktif' : 'Produk Aktif' }}</span>
                                    </span>

                                    <a href="{{ $url }}"
                                       class="kt-button kt-button-sm {{ $isWide ? 'kt-button-orange' : 'kt-button-primary' }}">
                                        {{ $isWide ? 'Jelajahi Koleksi' : 'Jelajahi' }}
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.200" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                                    </a>
                                @else
                                    <span class="kt-count">
                                        {{ $countNumber }} {{ $artisanCount !== null ? 'Mitra Pengrajin' : 'Produk Aktif' }}
                                    </span>

                                    <a href="{{ $url }}" class="kt-view-link">
                                        Lihat Ragam
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.500" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="kt-empty">Belum ada kategori kriya yang aktif.</p>
                @endforelse

                <p class="kt-empty" id="kt-filter-empty" hidden>Tidak ada kategori pada kelompok ini.</p>
            </div>
        </section>
    </div>

    <script>
        (function () {
            var chips = document.querySelectorAll('.kt-chip[data-filter]');
            var cards = document.querySelectorAll('.kt-category-card');
            var empty = document.getElementById('kt-filter-empty');

            chips.forEach(function (chip) {
                chip.addEventListener('click', function () {
                    var filter = chip.dataset.filter;
                    var visible = 0;

                    chips.forEach(function (c) { c.classList.toggle('is-active', c === chip); });

                    cards.forEach(function (card) {
                        var show = filter === 'all' || card.dataset.group === filter;
                        card.hidden = !show;
                        if (show) { visible++; }
                    });

                    if (empty) { empty.hidden = visible !== 0; }
                });
            });
        })();
    </script>
@endsection