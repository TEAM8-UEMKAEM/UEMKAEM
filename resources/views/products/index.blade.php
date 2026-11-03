@extends('layouts.app')

@section('title', 'Jelajahi Produk - Katalog Kriya Nusantara')

@section('content')
    {{-- Breadcrumb --}}
    <div class="crumb-bar">
        <nav class="breadcrumb">
            <a href="{{ url('/') }}">Beranda</a>
            <span>&rsaquo;</span>
            <b>Jelajahi Produk Kriya</b>
        </nav>
        <span class="crumb-pill">
            <i class="dot"></i>{{ $products->total() }} Mahakarya Terkurasi
            <em>&bull;</em> SDG 8.3 &amp; 8.9 Bersertifikat
        </span>
    </div>

    {{-- Judul halaman --}}
    <section class="page-head">
        <div>
            <span class="badge badge-green">
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6 9 17l-5-5"/></svg>
                Katalog Kurasi Fair-Trade Nusantara
            </span>
            <h1>Mahakarya Kriya Asli, Langsung dari Sanggar Desa</h1>
            <p class="muted">Setiap produk menyertakan transparansi rantai pasok dan persentase upah yang mengalir langsung ke artisan pembuatnya, bebas dari eksploitasi tengkulak.</p>
        </div>
        <div class="card highlight">
            <div class="highlight-top">
                <small>RATA-RATA DAMPAK LANGSUNG</small>
                <small class="audit">Audit Q1 2025</small>
            </div>
            <strong>89.4%</strong>
            <small>dari harga kembali ke ekosistem perajin lokal</small>
        </div>
    </section>

    {{-- Filter --}}
    <form method="GET" action="{{ route('products.index') }}" class="card filters">
        <div class="filters-row">
            <label class="search-field">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari tenun ikat, gerabah kasongan, batik indigofera...">
            </label>
            <label class="sort-field">
                <span>Urutkan:</span>
                <select name="urut" onchange="this.form.submit()">
                    <option value="">Paling Populer</option>
                    <option value="termurah" @selected(request('urut') === 'termurah')>Harga Termurah</option>
                    <option value="fairtrade" @selected(request('urut') === 'fairtrade')>Fair-Trade Tertinggi</option>
                </select>
            </label>
            <a href="{{ route('products.index') }}" class="btn btn-outline">Reset Filter</a>
        </div>

        <div class="chips">
            <span class="chips-label">KATEGORI KRIYA:</span>
            <a href="{{ route('products.index') }}" class="chip {{ request('kategori') ? '' : 'active' }}">Semua Kriya ({{ $products->total() }})</a>
            @foreach ($categories as $category)
                <a href="{{ route('products.index', ['kategori' => $category->slug]) }}"
                   class="chip {{ request('kategori') === $category->slug ? 'active' : '' }}">{{ $category->name }}@isset($category->products_count) ({{ $category->products_count }})@endisset</a>
            @endforeach
        </div>

        <div class="filters-adv">
            <label class="field">
                <small>Asal Daerah Nusantara</small>
                <select name="provinsi" onchange="this.form.submit()">
                    <option value="">Semua Provinsi</option>
                    @foreach (($provinces ?? []) as $province)
                        <option value="{{ $province }}" @selected(request('provinsi') === $province)>{{ $province }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">
                <small>Sertifikasi Fair-Trade</small>
                <select name="sertifikasi" onchange="this.form.submit()">
                    <option value="">Semua Sertifikasi</option>
                    <option value="90" @selected(request('sertifikasi') === '90')>Fair-Trade 90% ke atas</option>
                    <option value="80" @selected(request('sertifikasi') === '80')>Fair-Trade 80% ke atas</option>
                </select>
            </label>
            <label class="field">
                <small>Rentang Nominal (IDR)</small>
                <select name="harga" onchange="this.form.submit()">
                    <option value="">Semua Harga</option>
                    <option value="0-250000" @selected(request('harga') === '0-250000')>Di bawah Rp 250.000</option>
                    <option value="250000-1000000" @selected(request('harga') === '250000-1000000')>Rp 250.000 - Rp 1.000.000</option>
                    <option value="1000000-" @selected(request('harga') === '1000000-')>Di atas Rp 1.000.000</option>
                </select>
            </label>
            <label class="field field-check">
                <small>Karya Master Artisan</small>
                <span>
                    <input type="checkbox" name="regenerasi" value="1" @checked(request('regenerasi')) onchange="this.form.submit()">
                    Khusus Regenerasi Budaya
                </span>
            </label>
        </div>
    </form>

    <div class="result-bar">
        <p class="muted">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="m8 12 3 3 5-6"/></svg>
            Menampilkan {{ $products->count() }} mahakarya terverifikasi
        </p>
        <div class="legend">
            <span><i class="lg lg-green"></i>% Hak Artisan</span>
            <span><i class="lg lg-orange"></i>% Material Alami</span>
            <span><i class="lg lg-gray"></i>% Logistik &amp; Kemasan</span>
        </div>
    </div>

    {{-- Daftar produk --}}
    <section class="grid grid-4">
        @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product])
        @empty
            <p>Produk tidak ditemukan.</p>
        @endforelse
    </section>

    <div class="pagination-wrap">
        <p class="pager-info">
            Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}
            <em>&bull;</em> Menampilkan {{ $products->count() }} dari {{ $products->total() }} total kriya nusantara
        </p>
        {{ $products->links('partials.pagination') }}
    </div>

    {{-- Layanan korporasi --}}
    <section class="cta-band">
        <div>
            <span class="badge badge-light">Layanan Korporasi &amp; Kemitraan Kriya</span>
            <h2>Ingin Pesanan Kustom untuk Perusahaan atau Cinderamata Kenegaraan?</h2>
            <p>Tim kurasi kami mendampingi perancangan kemasan kustom, sertifikasi ESG / SDG 8.3 dampak sosial, serta pelacakan langsung asal muasal sanggar bagi kebutuhan cinderamata korporasi Anda.</p>
            <ul class="cta-points">
                <li>Laporan Dampak ESG Lengkap</li>
                <li>Kapasitas Skala Menengah &amp; Besar</li>
                <li>Pengemasan Lestari Bebas Plastik</li>
            </ul>
        </div>
        <div class="cta-actions">
            <a href="#" class="btn btn-primary">Hubungi Tim Kurasi</a>
            <a href="#" class="btn btn-glass">Unduh Katalog B2B PDF</a>
        </div>
    </section>
@endsection