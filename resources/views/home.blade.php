@extends('layouts.app')

@section('title', 'Beranda - UEMKAEM Kriya Nusantara')

@section('content')
    {{-- Hero --}}
    <section class="hero">
        <div class="hero-content">
            <span class="badge badge-gray">
                <i class="dot"></i>
                Dukungan Resmi Terhadap SDG 8.3 &amp; 8.9: Pekerjaan Layak &amp; Pariwisata Berkelanjutan
            </span>
            <h1>Merawat Warisan Nusantara, Menghubungkan Karya Pengrajin Lokal Langsung ke Rumah Anda</h1>
            <p>
                Marketplace kriya terkurasi yang menjamin harga adil (<span class="text-accent">fair-trade</span>)
                bagi perajin batik, tenun, gerabah, dan ukiran di seluruh pelosok Indonesia.
                Setiap helai benang dan lempung tanah menghidupi keluarga artisan nusantara.
            </p>

            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/></svg>
                    Jelajahi Koleksi Kriya
                </a>
                <a href="#dampak" class="btn btn-outline">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 9h8M8 13h5"/></svg>
                    Pelajari Cerita Artisan
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat-mini">
                    <span class="stat-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg></span>
                    <div><strong>100% Asli</strong><small>Buatan Tangan Perajin</small></div>
                </div>
                <div class="stat-mini">
                    <span class="stat-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v18M7 8h7a3 3 0 0 1 0 6H8a3 3 0 0 0 0 6h9"/></svg></span>
                    <div><strong>Fair-Price</strong><small>Transparan &amp; Bermartabat</small></div>
                </div>
                <div class="stat-mini">
                    <span class="stat-icon stat-icon-accent"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="8" r="3"/><circle cx="17" cy="9" r="2.5"/><path d="M3 20c0-3.3 2.7-6 6-6s6 2.7 6 6M15 14.5c3 0 6 1.5 6 5.5"/></svg></span>
                    <div><strong>1.420+ Mitra</strong><small>Perajin Diberdayakan</small></div>
                </div>
            </div>
        </div>

        <div class="hero-image">
            <img src="{{ asset('assets/image1.jpg') }}" alt="Kriya Nusantara">
            <span class="hero-tag">100% Pewarna Alami</span>
            <div class="hero-caption">
                <span class="hero-caption-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
                <div>
                    <strong>Kriya Nusantara</strong>
                    <small>Terverifikasi Asal Daerah &amp; Upah Hayati Adil</small>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori unggulan --}}
    <section>
        <small class="eyebrow">JELAJAHI BERDASARKAN RAGAM WARISAN</small>
        <div class="section-head">
            <h2>Kategori Unggulan Kriya</h2>
            <a href="{{ route('categories.index') }}">Lihat Semua Kategori &rarr;</a>
        </div>
        <div class="grid grid-5">
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['kategori' => $category->slug]) }}" class="card category-card">
                    <div class="category-image">
                        @if ($category->image)
                            <img
                                src="{{ route('categories.image', $category->id) }}"
                                alt="{{ $category->name }}"
                            >
                        @endif
                        @if (!empty($category->region))
                            <span class="category-tag">{{ $category->region }}</span>
                        @endif
                    </div>
                    <div class="category-body">
                        <h3>{{ $category->name }}</h3>
                        <p class="muted">{{ $category->description }}</p>
                        <div class="category-foot">
                            <small>{{ $category->products_count }} Produk</small>
                            <span class="arrow">&rarr;</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Dampak --}}
    <section class="impact" id="dampak">
        <div>
            <span class="badge badge-light">Transparansi Nyata Fair-Trade</span>
            <h2>Dampak Langsung Belanja Anda untuk Keluarga Artisan</h2>
            <p>Setiap transaksi tercatat terbuka di buku kas digital mitra. Sebagian besar nilai produk diterima langsung oleh perajin tanpa perantara berlebih.</p>
        </div>
        <div class="impact-stats">
            <div>
                <strong>Rp 4.2M<span>+</span></strong>
                <b>Total Pembayaran Adil Terverifikasi</b>
                <small>100% dikirimkan artisan tepat waktu</small>
            </div>
            <div>
                <strong>28</strong>
                <b>Wilayah Aktif &amp; Sentra Binaan</b>
                <small>Dari Sabang sampai Pegunungan Papua</small>
            </div>
            <div>
                <strong>94.8%</strong>
                <b>Bahan Hayati Alami</b>
                <small>Bersertifikat ramah lingkungan</small>
            </div>
        </div>
    </section>
@endsection