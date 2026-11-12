
<?php
    /* ---------- Tiny helper: draws an inline SVG icon ---------- */
    $icon = fn (string $paths, int $size = 16) =>
        '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths.'</svg>';

    $i = [
        'search'   => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'cart'     => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
        'bell'     => '<path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'check'    => '<path d="M20 6 9 17l-5-5"/>',
        'shield'   => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>',
        'box'      => '<path d="M21 8 12 3 3 8v8l9 5 9-5z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
        'wallet'   => '<path d="M20 12V8H6a2 2 0 0 1 0-4h12v4"/><path d="M4 6v12a2 2 0 0 0 2 2h14v-4"/><path d="M18 12a2 2 0 0 0 0 4h4v-4z"/>',
        'home'     => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M9 22V12h6v10"/>',
        'pin'      => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'truck'    => '<path d="M10 17h4V5H2v12h3"/><path d="M14 8h4l4 4v5h-3"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/>',
        'drop'     => '<path d="M12 22a7 7 0 0 0 7-7c0-4-7-13-7-13S5 11 5 15a7 7 0 0 0 7 7z"/>',
        'download' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/><path d="M12 15V3"/>',
        'gear'     => '<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M2 12h3M19 12h3M4.9 19.1 7 17M17 7l2.1-2.1"/>',
        'star'     => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8-6.2-3.3-6.2 3.3L8 14.2 3 9.3l6.9-1z"/>',
    ];

    /* ---------- Data (later you can replace these with database data) ---------- */
    $user = [
        'name'     => 'Citra Lestari',
        'id'       => 'KRY-ID-88204',
        'level'    => 'Kurator Pelindung Budaya • Tingkat Madya (Lv 3)',
        'joined'   => 'Bergabung Sejak Maret 2023',
        'city'     => 'Jakarta Selatan, DKI Jakarta',
        'progress' => 8,
        'target'   => 10,
        'photo'    => asset('images/citra-lestari.jpg'),
    ];

    $stats = [
        ['label' => 'Mahakarya Dimiliki',       'badge' => 'Terdaftar',          'badgeClass' => '',        'icon' => 'box',    'value' => '8',          'unit' => 'Karya Seni Master', 'note' => 'Tenun Sumba, Liat, Gerabah Kasongan & Perak'],
        ['label' => 'Dampak Finansial Langsung','badge' => '100% Direct Fair',   'badgeClass' => 'is-warm', 'icon' => 'wallet', 'value' => 'Rp 7,85 juta','unit' => '',                  'note' => 'Diterima tangan perajin tanpa potongan makelar'],
        ['label' => 'Sanggar Binaan Terbantu',  'badge' => 'Regenerasi Budaya',  'badgeClass' => '',        'icon' => 'home',   'value' => '5',          'unit' => 'Desa Tradisional',  'note' => 'Dukungan bahan baku organik & tali tenun ATBM'],
        ['label' => 'Skor Keaslian Paspor',     'badge' => 'Valid & Orisinil',   'badgeClass' => '',        'icon' => 'shield', 'value' => '100%',       'unit' => 'Kriptografi & Fisik','note' => 'Sertifikat digital tervalidasi kurator ahli'],
    ];

    $tabs = [
        ['Koleksi & Paspor Kriya',            '#koleksi',  true,  false],
        ['Status Logistik Khusus',            '#logistik', false, true],
        ['Sanggar Berlangganan & Akses Awal', '#sanggar',  false, false],
        ['Dana Abadi & Regenerasi Tenun',     '#dana',     false, false],
        ['Pengaturan Akun',                   '#pengaturan', false, false],
    ];

    $steps = [
        ['state' => 'done',    'icon' => 'check', 'title' => 'QC Sanggar & Kurator',      'time' => '18 Okt • 09:30 WIB',        'desc' => 'Lolos uji keaslian benang perak & tembang tua.'],
        ['state' => 'done',    'icon' => 'check', 'title' => 'Penyegelan Besek Alami',    'time' => '19 Okt • 14:15 WIB',        'desc' => 'Dikemas daun pisang kering & silica gel alami.'],
        ['state' => 'current', 'icon' => 'truck', 'title' => 'Transit Hub Budaya Padang', 'time' => '20 Okt • 11:00 WIB (Terkini)', 'desc' => 'Penerbangan jalur kargo iklim terkendali ke Soekarno-Hatta.'],
        ['state' => 'pending', 'icon' => 'pin',   'title' => 'Tiba di Kediaman Kolektor', 'time' => 'Estimasi 21 Okt • Siang',   'desc' => 'Penyerahan fisik sertifikat siap disimpan.'],
    ];

    $collection = [
        [
            'img' => asset('images/kain-tenun-marapu.jpg'), 'tag' => '100% Indigofera & Mengkudu', 'id' => 'PAS-SMB-0442',
            'category' => 'Wastra Tenun Tradisional', 'title' => 'Kain Tenun Ikat Motif Kuda Marapu',
            'desc' => 'Simbol kasta, kekuatan spiritual, dan kepemimpinan bangsawan Sumba.',
            'rows' => [
                'Maestro Artisan' => 'Mama Maria Hebi',
                'Sentra Kriya'    => 'Desa Prai Liu, Sumba Timur, NTT',
                'Waktu Pengerjaan'=> '14 Bulan Pintal Tangan',
            ],
            'alloc' => '92% dari Nilai Karya',
        ],
        [
            'img' => asset('images/teko-kasongan.jpg'), 'tag' => 'Bakar Sekam 900°C', 'id' => 'PAS-KSO-1109',
            'category' => 'Gerabah & Tanah Liat Studio', 'title' => 'Teko Gerabah Liat Bakar Kasongan',
            'desc' => 'Kendi ergonomis air minum tradisional dengan bentuk khas perajin Kasongan, kuat dan aman dipakai.',
            'rows' => [
                'Maestro Artisan'   => 'Pak Slamet Raharjo',
                'Sentra Kriya'      => 'Kasongan, Bantul, D.I. Yogyakarta',
                'Kandungan Material'=> '100% Liat Merah Alami Food Grade',
            ],
            'alloc' => '88% dari Nilai Karya',
        ],
        [
            'img' => asset('images/piring-bayat.jpg'), 'tag' => 'Teknik Putaran Miring Purba', 'id' => 'PAS-BYT-2091',
            'category' => 'Tembikar Purba Nusantara', 'title' => 'Piring Saji Terakota Bakar Bayat',
            'desc' => 'Dibuat menggunakan roda putar miring tradisional, warisan teknik tembikar yang diturunkan antargenerasi.',
            'rows' => [
                'Maestro Artisan' => 'Mbah Marto Utomo',
                'Sentra Kriya'    => 'Paseban, Bayat, Klaten, Jawa Tengah',
                'Penyelesaian'    => 'Asap Daun Asem Organik',
            ],
            'alloc' => '90% dari Nilai Karya',
        ],
    ];

    $sanggar = [
        ['init' => 'PL', 'name' => 'Sanggar Prai Liu',       'place' => 'Sumba Timur • NTT',                'desc' => 'Spesialis pewarna alam Indigofera & tenun rajut.', 'date' => '28 Oktober 2025'],
        ['init' => 'PJ', 'name' => 'Padepokan Ukir Jepara',  'place' => 'Jepara • Kriya Ukir Kayu Jati',    'desc' => 'Ukiran relief tembus motif lung-lungan dedaunan.', 'date' => '05 November 2025'],
        ['init' => 'TL', 'name' => 'Batik Tiga Negeri Lasem','place' => 'Lasem, Rembang • Batik Tulis',     'desc' => 'Harmoni merah gethih pitik, biru dan sogan.',       'date' => '12 November 2025'],
    ];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Kolektor – Kriya Nusantara</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>


<header class="ac-topbar">
    <a href="/" class="ac-brand">
        <span class="ac-brand-mark">K</span>
        <span class="ac-brand-text">UEMKAEM<small>KRIYA NUSANTARA</small></span>
    </a>

    <div class="ac-search-slot">
        <label class="search-bar">
            <span class="search-icon"><?php echo $icon($i['search'], 16); ?></span>
            <input type="text" placeholder="Cari tenun ikat, gerabah, anyaman bambu...">
        </label>
    </div>

    <div class="ac-topbar-right">
        <span class="ac-pill ac-pill-hide-sm">SDG 8, 3 &amp; 8,9</span>
        <span class="ac-pill ac-pill-green"><i class="ac-dot"></i> Mode Pembeli</span>
        <a href="#" class="ac-icon-btn" aria-label="Keranjang"><?php echo $icon($i['cart'], 18); ?></a>
        <a href="#" class="ac-icon-btn" aria-label="Notifikasi"><?php echo $icon($i['bell'], 18); ?><i class="ac-notif"></i></a>
        <a href="<?php echo e(url('/account')); ?>" class="ac-avatar-sm" aria-label="Akun saya"><?php echo $icon($i['user'], 16); ?></a>
    </div>
</header>

<nav class="ac-subnav" aria-label="Navigasi utama">
    <div class="ac-subnav-main">
        <a href="/" class="is-active">Beranda</a>
        <a href="#">Jelajahi Produk</a>
        <a href="#">Kategori Kriya</a>
        <a href="#">Cerita Artisan</a>
        <a href="#">Fair-Trade Impact</a>
    </div>
    <div class="ac-subnav-modes">
        <a href="#">Mode Pembeli</a>
        <a href="#">Mode Artisan</a>
        <a href="#">Mode Admin</a>
        <a href="#">Mode Kurir</a>
    </div>
</nav>


<section class="ac-hero">
    <div class="ac-wrap ac-hero-inner">
        <div class="ac-avatar">
            <img src="<?php echo e($user['photo']); ?>" alt="Foto <?php echo e($user['name']); ?>" onerror="this.style.display='none'">
            <span class="ac-verified"><?php echo $icon($i['shield'], 14); ?></span>
        </div>

        <div class="ac-hero-info">
            <div class="ac-name-row">
                <h1><?php echo e($user['name']); ?></h1>
                <span class="ac-level"><?php echo $icon($i['star'], 12); ?> <?php echo e($user['level']); ?></span>
                <span class="ac-meta"><?php echo $icon($i['pin'], 12); ?> <?php echo e($user['joined']); ?></span>
            </div>
            <p class="ac-meta"><?php echo e($user['city']); ?></p>
            <p class="ac-collector-id">ID Kolektor: <b><?php echo e($user['id']); ?></b></p>

            <div class="ac-progress">
                <div class="ac-progress-head">
                    <span>Menuju Gelar 'Pelindung Utama'</span>
                    <b><?php echo e($user['progress']); ?> / <?php echo e($user['target']); ?> Mahakarya</b>
                </div>
                <div class="ac-progress-bar"><i style="width: <?php echo e(($user['progress'] / $user['target']) * 100); ?>%"></i></div>
            </div>
            <p class="ac-hint">Koleksi <?php echo e($user['target'] - $user['progress']); ?> mahakarya berikutnya lagi untuk membuka kurasi &amp; koridor artisan tersisa.</p>
        </div>

        <div class="ac-hero-actions">
            <a href="#pengaturan" class="ac-btn ac-btn-light">Ubah Profil</a>
            <a href="#" class="ac-btn ac-btn-dark">Kurator Privat Saya</a>
            <a href="#" class="ac-btn ac-btn-light">Alamat Tersimpan (2)</a>
        </div>
    </div>
</section>

<main class="ac-wrap ac-main">

    
    <section class="ac-stats">
        <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <article class="ac-stat">
                <div class="ac-stat-top">
                    <span class="ac-stat-icon"><?php echo $icon($i[$s['icon']], 16); ?></span>
                    <span class="ac-stat-badge <?php echo e($s['badgeClass']); ?>"><?php echo e($s['badge']); ?></span>
                </div>
                <small class="ac-stat-label"><?php echo e($s['label']); ?></small>
                <strong class="ac-stat-value"><?php echo e($s['value']); ?> <?php if($s['unit']): ?><span><?php echo e($s['unit']); ?></span><?php endif; ?></strong>
                <p class="ac-stat-note"><?php echo e($s['note']); ?></p>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </section>

    
    <nav class="ac-tabs" aria-label="Bagian akun">
        <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $href, $active, $dot]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e($href); ?>" class="ac-tab <?php echo e($active ? 'is-active' : ''); ?>">
                <?php echo e($label); ?> <?php if($dot): ?><i class="ac-dot ac-dot-orange"></i><?php endif; ?>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </nav>

    
    <section id="logistik" class="ac-ship">
        <div class="ac-ship-head">
            <div class="ac-ship-icon"><?php echo $icon($i['box'], 22); ?></div>
            <div class="ac-ship-title">
                <span class="ac-ship-status">Dalam Perjalanan Aman</span>
                <small>Resi: <b>KRY-EXP-9971H-SUM</b></small>
                <h2>Selendang Songket Pandai Sikek Benang Tembaga</h2>
                <p class="muted">Dari Sanggar Pusako Tuo, Kab. Tanah Datar, Sumatra Barat</p>
            </div>
            <div class="ac-ship-actions">
                <span class="ac-sensor"><?php echo $icon($i['drop'], 14); ?> Sensor Kelembaban Bale: 54% (Optimal)</span>
                <a href="#" class="ac-btn ac-btn-dark ac-btn-sm">Rincian Kurir Budaya</a>
            </div>
        </div>

        <ol class="ac-steps">
            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="ac-step is-<?php echo e($step['state']); ?>">
                    <span class="ac-step-dot"><?php echo $icon($i[$step['icon']], 14); ?></span>
                    <strong><?php echo e($step['title']); ?></strong>
                    <small class="ac-step-time"><?php echo e($step['time']); ?></small>
                    <p><?php echo e($step['desc']); ?></p>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </section>

    
    <section id="koleksi" class="ac-section">
        <div class="ac-section-top">
            <div>
                <span class="ac-eyebrow"><i class="ac-dot ac-dot-dark"></i> BRANKAS DIGITAL PELINDUNG WARISAN</span>
                <h2>Koleksi Terverifikasi &amp; Paspor Kriya</h2>
                <p class="ac-section-desc">Setiap karya dilindungi identitas kriptografis, riwayat pewarnaan alami, serta nama maestro pembuatnya.</p>
            </div>
            <div class="ac-section-actions">
                <a href="#" class="ac-btn ac-btn-light ac-btn-sm">Semua Kriya (8)</a>
                <a href="#" class="ac-btn ac-btn-light ac-btn-sm">Unduh Semua Sertifikat</a>
            </div>
        </div>

        <div class="ac-collection">
            <?php $__currentLoopData = $collection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="ac-work">
                    <div class="ac-work-media">
                        <img src="<?php echo e($item['img']); ?>" alt="<?php echo e($item['title']); ?>" onerror="this.style.display='none'">
                        <span class="ac-work-tag"><?php echo e($item['tag']); ?></span>
                        <span class="ac-work-id">ID: <?php echo e($item['id']); ?></span>
                    </div>
                    <div class="ac-work-body">
                        <span class="ac-work-cat"><?php echo e(strtoupper($item['category'])); ?></span>
                        <h3><?php echo e($item['title']); ?></h3>
                        <p class="ac-work-desc"><?php echo e($item['desc']); ?></p>

                        <dl class="ac-work-rows">
                            <?php $__currentLoopData = $item['rows']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><dt><?php echo e($label); ?>:</dt><dd><?php echo e($value); ?></dd></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="ac-work-alloc"><dt>Alokasi ke Perajin:</dt><dd><?php echo e($item['alloc']); ?></dd></div>
                        </dl>

                        <div class="ac-work-actions">
                            <a href="#" class="ac-btn ac-btn-dark ac-btn-block"><?php echo $icon($i['download'], 14); ?> Unduh Sertifikat PDF</a>
                            <a href="#" class="ac-btn ac-btn-light ac-btn-square" aria-label="Pengaturan karya"><?php echo $icon($i['gear'], 14); ?></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    
    <section id="sanggar" class="ac-subs">
        <div class="ac-section-top">
            <div>
                <span class="ac-eyebrow ac-eyebrow-orange"><?php echo $icon($i['star'], 12); ?> HAK ISTIMEWA KOLEKTOR MADYA</span>
                <h2>Sanggar Langganan &amp; Akses Rilis Privat (Early Access 48 Jam)</h2>
                <p class="ac-section-desc">Anda mendapatkan prioritas memesan karya terbatas sebelum dirilis ke publik luas.</p>
            </div>
            <a href="#" class="ac-btn ac-btn-light ac-btn-sm">Kelola 6 Langganan Sanggar</a>
        </div>

        <div class="ac-subs-grid">
            <?php $__currentLoopData = $sanggar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="ac-sub">
                    <div class="ac-sub-top">
                        <span class="ac-sub-init"><?php echo e($s['init']); ?></span>
                        <div>
                            <small><?php echo e($s['place']); ?></small>
                            <h3><?php echo e($s['name']); ?></h3>
                        </div>
                    </div>
                    <p><?php echo e($s['desc']); ?></p>
                    <div class="ac-sub-foot">
                        <div><small>Rilis Mahakarya Berikutnya</small><b><?php echo e($s['date']); ?></b></div>
                        <a href="#" class="ac-btn ac-btn-terra ac-btn-xs">Lihat Profil</a>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    
    <section id="dana" class="ac-fund">
        <div class="ac-fund-text">
            <span class="ac-fund-tag">Inisiatif Regenerasi Maestro 2025 • SDG 8 &amp; 8.9</span>
            <h2>Dana Abadi Budaya &amp; Beasiswa Magang Generasi Muda Tenun</h2>
            <p>Dari setiap apresiasi karya yang Anda koleksi, sebagian disalurkan untuk uang saku 12 pemuda desa yang sedang berguru teknik ikat purba kepada para empu sepuh di Flores dan Sumba. Bersama kita mencegah kepunahan wastra adiluhung Nusantara.</p>

            <div class="ac-fund-stats">
                <div><strong>12</strong><small>Siswa Penerima Beasiswa Penuh</small></div>
                <div><strong>4</strong><small>Hektar Kebun Indigofera Lestari</small></div>
                <div><strong>100%</strong><small>Transparan Laporan Audit Tiap Kuartal</small></div>
            </div>
        </div>

        <aside class="ac-fund-card">
            <small>KONTRIBUSI TERAKUMULASI ANDA</small>
            <strong>Rp 1.450.000</strong>
            <p>Menjamin biaya alat tenun bagi 1 siswa magang selama 6 bulan.</p>
            <a href="#" class="ac-btn ac-btn-terra ac-btn-block">Tambah Dukungan Dana Abadi</a>
            <a href="#" class="ac-fund-link">Lihat Cerita Profil 12 Siswa Magang</a>
        </aside>
    </section>

    
    <span id="pengaturan"></span>
</main>


<footer class="footer">
    <div class="footer-inner">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo"><span class="footer-wordmark">KRIYA NUSANTARA</span></div>
                <p>Pemberdayaan terverifikasi UMKM perajin kriya tradisional dukungan perkotaan sesuai Indonesia sesuai SDG 8, 3 &amp; 8,9.</p>
            </div>
            <div class="footer-col">
                <h4>Kategori Warisan</h4>
                <a href="#">Wastra &amp; Tenun Ikat</a>
                <a href="#">Gerabah &amp; Keramik Studio</a>
                <a href="#">Ukiran Kayu Jati &amp; Rotan</a>
                <a href="#">Kriya Logam &amp; Perak Bali</a>
            </div>
            <div class="footer-col">
                <h4>Transparansi &amp; Etika</h4>
                <a href="#">Upah Layak Pengrajin</a>
                <a href="#">Pelacakan Bijak Alam</a>
                <a href="#">Sertifikasi Bahan Alami</a>
                <a href="#">Kebijakan Kemitraan Desa</a>
            </div>
            <div class="footer-col">
                <h4>Kanal Akses</h4>
                <a href="#">Pendaftaran Dukungan UMKM</a>
                <a href="#">Logistik &amp; Kurir Dingin</a>
                <a href="#">Portal Verifikator Dampak</a>
                <a href="#">Dukungan Kurasi Ekspor</a>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="footer-legal">
                <span>© 2025 UEMKAEM Kriya Nusantara. Pelestarian Budaya &amp; Pemberdayaan Berkelanjutan.</span>
            </div>
            <div class="footer-links">
                <span>IDR (Rp)</span>
                <span>Bahasa Indonesia</span>
            </div>
        </div>
    </div>
</footer>

</body>
</html><?php /**PATH C:\Users\user\Documents\GitHub\UEMKAEM\resources\views/account.blade.php ENDPATH**/ ?>