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
                                    @if ($isWide)
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l2.500 2.500M15.500 15.500L18 18M18 6l-2.500 2.500M8.500 15.500L6 18"/></svg>
                                    @else
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 19c0-9 5-14 15-14 0 10-5 15-14 15"/><path d="M5 19c3-5 6-8 10-10"/></svg>
                                    @endif
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

        {{-- ================= PETA SEBARAN SENTRA ================= --}}
        @php
            $sentra = [
                'sumatra' => [
                    'title' => 'Sumatera Barat & Sumatera Utara',
                    'lead' => 'Songket Pandai Sikek, Ulos Batak, Tenun Silungkang',
                    'desc' => 'Wastra benang emas Minangkabau dan Ulos Batak bernilai sakral, ditenun dengan alat tenun bukan mesin oleh sanggar turun-temurun.',
                    'mitra' => '340 Mitra',
                    'upah' => 'Rp 2.700.000 / bln',
                    'regen' => 'Sanggar Penenun Muda',
                ],
                'kalimantan' => [
                    'title' => 'Kalimantan Timur & Tengah',
                    'lead' => 'Anyaman Rotan Dayak, Manik Tradisi, Batu Mulia',
                    'desc' => 'Anyaman rotan dan serat Dayak, manik tradisi, serta olahan batu mulia lokal dari kelompok perajin adat.',
                    'mitra' => '210 Mitra',
                    'upah' => 'Rp 2.400.000 / bln',
                    'regen' => 'Kelompok Anyam Muda',
                ],
                'jawa' => [
                    'title' => 'D.I. Yogyakarta & Jawa Tengah',
                    'lead' => 'Batik Canting, Ukir Jepara, Gerabah Kasongan',
                    'desc' => 'Sentra kebudayaan adiluhung dengan kurasi canting tulis alami, seni tatah sungging kulit, tempa perak Kotagede, dan kerajinan mebel ukir Jepara.',
                    'mitra' => '720 Mitra',
                    'upah' => 'Rp 2.950.000 / bln',
                    'regen' => 'Sanggar Magang Pemuda',
                ],
                'nusra' => [
                    'title' => 'Bali & Nusa Tenggara',
                    'lead' => 'Tenun Ikat Sumba, Ukir Gianyar, Lontar Rote',
                    'desc' => 'Tenun ikat pewarna akar, ukiran arca Gianyar, dan anyaman lontar Rote Ndao yang dikerjakan secara komunal.',
                    'mitra' => '460 Mitra',
                    'upah' => 'Rp 2.600.000 / bln',
                    'regen' => 'Sanggar Tenun Remaja',
                ],
                'papua' => [
                    'title' => 'Sulawesi & Papua',
                    'lead' => 'Ukir Toraja, Ukiran Asmat, Noken Papua',
                    'desc' => "Panel Pa'tedong Toraja, ukiran leluhur Asmat, dan noken serat kulit kayu dengan jaminan hak cipta komunal.",
                    'mitra' => '190 Mitra',
                    'upah' => 'Rp 2.500.000 / bln',
                    'regen' => 'Sanggar Adat Muda',
                ],
            ];
            $defaultSentra = $sentra['jawa'];
        @endphp

        <section class="kt-panel" id="peta-sebaran" aria-labelledby="kt-map-title">
            <div class="kt-section-top">
                <div>
                    <span class="kt-eyebrow">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21s-6-5.2-6-10a6 6 0 0 1 12 0c0 4.800-6 10-6 10z"/><circle cx="12" cy="11" r="2"/></svg>
                        PETA GEOGRAFIS KRIYA
                    </span>
                    <h2 id="kt-map-title">Sebaran Sentra Artisan Nusantara</h2>
                    <p class="kt-section-desc">
                        Klik pin wilayah pada peta interaktif untuk melihat sanggar perajin terakreditasi,
                        ragam komoditas kriya unggulan, dan verifikasi upah layak langsung di daerah tersebut.
                    </p>
                </div>

                <div class="kt-chip-row kt-chip-row-wrap kt-map-chips" role="group" aria-label="Filter pulau">
                    <button type="button" class="kt-chip kt-map-chip is-active" data-island="all">Semua Pulau</button>
                    <button type="button" class="kt-chip kt-map-chip" data-island="sumatra">Sumatra</button>
                    <button type="button" class="kt-chip kt-map-chip" data-island="jawa">Jawa</button>
                    <button type="button" class="kt-chip kt-map-chip" data-island="nusra">Bali &amp; Nusa Tenggara</button>
                    <button type="button" class="kt-chip kt-map-chip" data-island="kalimantan">Kalimantan</button>
                    <button type="button" class="kt-chip kt-map-chip" data-island="papua">Sulawesi &amp; Papua</button>
                </div>
            </div>

            <div class="kt-map-layout">
                <div class="kt-map-card">
                    <div class="kt-map-toolbar">
                        <strong><i class="kt-live-dot"></i>Peta Satelit Sentra Kriya (Live Koordinat)</strong>
                        <span>38 Provinsi Terpetakan</span>
                    </div>

                    <div class="kt-map-frame">
                        <svg viewBox="0 0 800 380" role="img" aria-label="Peta sebaran sentra artisan di kepulauan Indonesia">
                            <g class="kt-land">
                                <path d="M60 95 L95 82 L205 185 L222 238 L182 244 L88 160 Z"/>
                                <path d="M290 125 C330 88 402 92 424 132 C434 178 392 218 342 218 C300 208 268 165 290 125 Z"/>
                                <path d="M252 262 C300 252 390 252 432 258 L440 276 C380 288 300 288 262 284 Z"/>
                                <path d="M470 118 L500 104 L522 158 L562 174 L546 192 L506 186 L492 232 L470 226 L482 170 Z"/>
                                <path d="M602 150 L722 134 L742 192 L690 232 L640 216 L612 190 Z"/>
                                <ellipse cx="462" cy="286" rx="16" ry="6"/>
                                <ellipse cx="500" cy="290" rx="18" ry="6"/>
                                <ellipse cx="548" cy="296" rx="20" ry="6"/>
                                <ellipse cx="590" cy="140" rx="10" ry="14"/>
                            </g>

                            @php
                                $pins = [
                                    ['sumatra', 'sumatra', 'green', 120, 150, 'Sumatera'],
                                    ['kalimantan', 'kalimantan', 'orange', 350, 150, 'Kalimantan'],
                                    ['jawa', 'jawa', 'orange', 330, 270, 'Yogyakarta'],
                                    ['jawa', 'jawa', 'green', 380, 266, 'Jepara'],
                                    ['nusra', 'nusra', 'green', 500, 290, 'Nusa Tenggara'],
                                    ['papua', 'papua', 'green', 670, 182, 'Papua'],
                                ];
                            @endphp

                            @foreach ($pins as [$key, $island, $color, $x, $y, $label])
                                <g class="kt-marker kt-marker-{{ $color }} {{ $label === 'Yogyakarta' ? 'is-active' : '' }}"
                                   data-region="{{ $key }}" data-island="{{ $island }}"
                                   transform="translate({{ $x }} {{ $y }})"
                                   tabindex="0" role="button" aria-label="Sentra {{ $label }}">
                                    <circle r="11"/>
                                    <path d="M-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" fill="#fff" stroke="none"/>
                                </g>
                            @endforeach
                        </svg>
                    </div>

                    <div class="kt-map-legend">
                        <span><i class="kt-legend-dot kt-dot-green"></i>Sentra Kriya Tekstil &amp; Kayu</span>
                        <span><i class="kt-legend-dot kt-dot-orange"></i>Sentra Logam, Keramik &amp; Mineral</span>
                        <span class="kt-legend-hint">Klik pin untuk meninjau data daerah</span>
                    </div>
                </div>

                <aside class="kt-region-card" id="kt-region" aria-live="polite">
                    <div class="kt-region-top">
                        <span class="kt-region-pill">Katalog Sentra Terverifikasi</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--kt-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.500 12.500l2.500 2.500 4.500-5"/></svg>
                    </div>

                    <h3 data-r="title">{{ $defaultSentra['title'] }}</h3>
                    <p class="kt-region-lead" data-r="lead">{{ $defaultSentra['lead'] }}</p>
                    <p data-r="desc">{{ $defaultSentra['desc'] }}</p>

                    <div class="kt-region-metrics">
                        <div><span>Jumlah Perajin:</span><strong data-r="mitra">{{ $defaultSentra['mitra'] }}</strong></div>
                        <div><span>Jaminan Upah Rerata:</span><strong class="kt-wage" data-r="upah">{{ $defaultSentra['upah'] }}</strong></div>
                        <div>
                            <span>Standar Regenerasi:</span>
                            <strong class="kt-status">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 19c0-9 5-14 15-14 0 10-5 15-14 15"/><path d="M5 19c3-5 6-8 10-10"/></svg>
                                <span data-r="regen">{{ $defaultSentra['regen'] }}</span>
                            </strong>
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}?daerah=jawa" id="kt-region-link" class="kt-button kt-button-primary kt-button-block kt-region-button">
                        Buka Katalog Produk Daerah Ini
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.200" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 17L17 7M8 7h9v9"/></svg>
                    </a>
                </aside>
            </div>
        </section>

        {{-- ================= STANDAR KURASI + PANDUAN PERAWATAN ================= --}}
        <section class="kt-info-grid" aria-label="Standar kurasi dan panduan perawatan">
            <div class="kt-info-card kt-info-plain">
                <span class="kt-eyebrow">PEDOMAN KURASI KRIYA</span>
                <h2>Standar Teknik Pembuatan Luhur</h2>
                <p class="kt-info-intro">
                    UEMKAEM hanya mengkurasi karya yang mematuhi protokol keaslian tradisi tanpa pemalsuan
                    cetak digital atau kimia sintetis berbahaya.
                </p>

                <div class="kt-standard-list">
                    <article class="kt-standard-item">
                        <span class="kt-number kt-number-green">1</span>
                        <div>
                            <h3>Bahan Baku Organik &amp; Kayu Tersertifikasi FSC/SVLK</h3>
                            <p>Setiap helai kapas dipintal manual (benang lawe), pewarna diekstrak dari daun nila dan akar mengkudu. Kayu jati, sono, dan ulin wajib mengantongi verifikasi legalitas kayu lestari SVLK.</p>
                        </div>
                    </article>
                    <article class="kt-standard-item">
                        <span class="kt-number kt-number-orange">2</span>
                        <div>
                            <h3>Pengerjaan Penuh Buatan Tangan (100% Hand-Crafted)</h3>
                            <p>Tidak menggunakan mesin printing atau cetak komputer massal. Tiap goresan canting, pukulan tatah ukir, dan putaran tanah liat mencerminkan waktu kerja 2 minggu hingga 6 bulan per lembar mahakarya.</p>
                        </div>
                    </article>
                    <article class="kt-standard-item">
                        <span class="kt-number kt-number-green">3</span>
                        <div>
                            <h3>Verifikasi Hak Kekayaan Budaya &amp; Kompensasi Adil</h3>
                            <p>Perlindungan hak cipta motif komunal adat. Minimal 65% dari harga jual produk ditransfer langsung ke rekening artisan desa tanpa potongan tengkulak gelap.</p>
                        </div>
                    </article>
                </div>
            </div>

            <div class="kt-info-card kt-info-tint">
                <span class="kt-eyebrow">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5M9 8h6"/></svg>
                    BUKU PANDUAN KOLEKTOR
                </span>
                <h2>Panduan Perawatan Kriya Bernilai Tinggi</h2>
                <p class="kt-info-intro">
                    Merawat kriya tradisional membutuhkan ketelatenan agar warna alami, serat kayu, dan kilau
                    perak tetap abadi hingga generasi berikutnya.
                </p>

                <div class="kt-care-list">
                    <details name="kt-care" open>
                        <summary>Perawatan Tenun &amp; Batik Pewarna Alami</summary>
                        <p>Cuci hanya dengan sari buah lerak atau air hangat tanpa detergen berbahan kimia keras. Jangan peras dengan memelintir kain, angin-anginkan di tempat teduh tanpa terkena matahari langsung.</p>
                    </details>
                    <details name="kt-care">
                        <summary>Merawat Ukiran Kayu Jati &amp; Sonokeling</summary>
                        <p>Bersihkan debu dengan kuas lembut dan oleskan lilin lebah tipis setiap beberapa bulan. Hindari ruangan lembap dan paparan AC atau sinar matahari langsung agar kayu tidak retak.</p>
                    </details>
                    <details name="kt-care">
                        <summary>Perak Filigree &amp; Logam Kuningan</summary>
                        <p>Lap dengan kain mikrofiber kering setelah dipegang. Simpan dalam kantong kain anti-kusam dan hindari bahan pembersih berbasis asam atau pemutih.</p>
                    </details>
                    <details name="kt-care">
                        <summary>Gerabah Terakota &amp; Keramik Tanah</summary>
                        <p>Bersihkan dengan kain lembap tanpa sabun keras. Hindari perubahan suhu mendadak dan benturan, terutama pada bagian bibir dan pegangan.</p>
                    </details>
                </div>

                <div class="kt-ebook">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5"/></svg>
                        Unduh E-Book Perawatan (PDF)
                    </span>
                    <a href="#" class="kt-care-link">Unduh Gratis</a>
                </div>
            </div>
        </section>

        {{-- ================= CTA ================= --}}
        <section class="kt-cta" aria-labelledby="kt-cta-title">
            <div>
                <span class="kt-eyebrow kt-eyebrow-mint">REGENERASI BUDAYA TAK BOLEH BERHENTI</span>
                <h2 id="kt-cta-title">Jadilah Bagian dari Rantai Perlindungan Seni Kriya</h2>
                <p>
                    Setiap pembelian kriya asli menjamin biaya pendidikan anak perajin dan regenerasi
                    keterampilan leluhur kepada generasi muda di desa-desa adat.
                </p>
            </div>

            <div class="kt-cta-actions">
                <a href="{{ route('products.index') }}" class="kt-button kt-button-orange">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10h18l-2 9a2 2 0 0 1-2 1.500H7A2 2 0 0 1 5 19z"/><path d="M8 10l3-6M16 10l-3-6"/></svg>
                    Beli Karya Artisan
                </a>
                <a href="#" class="kt-button kt-button-glass">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 21l-8-8a4.500 4.500 0 0 1 6.400-6.400L12 8.200l1.600-1.600A4.500 4.500 0 0 1 20 13z"/><path d="M9 12l3 3"/></svg>
                    Daftarkan Sanggar Kriya
                </a>
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

        (function () {
            var data = @json($sentra);
            var card = document.getElementById('kt-region');
            var link = document.getElementById('kt-region-link');
            var markers = document.querySelectorAll('.kt-marker');
            var chips = document.querySelectorAll('.kt-map-chip');
            if (!card) { return; }

            var base = link.getAttribute('href').split('?')[0];

            function show(key, marker) {
                var d = data[key];
                if (!d) { return; }
                card.querySelectorAll('[data-r]').forEach(function (el) { el.textContent = d[el.dataset.r]; });
                link.setAttribute('href', base + '?daerah=' + key);
                markers.forEach(function (m) { m.classList.toggle('is-active', m === marker); });
            }

            markers.forEach(function (m) {
                m.addEventListener('click', function () { show(m.dataset.region, m); });
                m.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); show(m.dataset.region, m); }
                });
            });

            chips.forEach(function (chip) {
                chip.addEventListener('click', function () {
                    var island = chip.dataset.island, first = null;
                    chips.forEach(function (c) { c.classList.toggle('is-active', c === chip); });
                    markers.forEach(function (m) {
                        var visible = island === 'all' || m.dataset.island === island;
                        m.style.display = visible ? '' : 'none';
                        if (visible && !first) { first = m; }
                    });
                    if (first && island !== 'all') { show(first.dataset.region, first); }
                });
            });
        })();
    </script>
@endsection