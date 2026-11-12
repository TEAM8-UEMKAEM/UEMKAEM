
<?php
    // ---------- Small icon helper (so we don't repeat SVG code) ----------
    $paths = [
        'grid'     => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
        'box'      => '<path d="M20 7l-8-4-8 4v10l8 4 8-4V7z"/><path d="M4 7l8 4 8-4M12 11v10"/>',
        'check'    => '<circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>',
        'truck'    => '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/>',
        'chart'    => '<path d="M4 20V10M10 20V4M16 20v-8M22 20H2"/>',
        'store'    => '<path d="M3 9l1.5-5h15L21 9M3 9v11h18V9"/><path d="M3 9c0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0 0 2 3 2 3 0"/>',
        'bell'     => '<path d="M6 16v-5a6 6 0 1112 0v5l2 2H4l2-2zM10 21h4"/>',
        'user'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>',
        'search'   => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/>',
        'download' => '<path d="M12 4v11M7 11l5 5 5-5M5 20h14"/>',
        'plus'     => '<path d="M12 5v14M5 12h14"/>',
        'map'      => '<path d="M3 6l6-2 6 2 6-2v14l-6 2-6-2-6 2zM9 4v14M15 6v14"/>',
        'arrow'    => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'filter'   => '<path d="M4 6h16M7 12h10M10 18h4"/>',
    ];
    $icon = fn ($name, $size = 18) =>
        '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">'.$paths[$name].'</svg>';

    // ---------- Sidebar menu ----------
    $menu = [
        ['label' => 'Ringkasan Operasional', 'icon' => 'grid',  'active' => true],
        ['label' => 'Katalog & Inventaris Kriya', 'icon' => 'box', 'active' => false],
        ['label' => 'Verifikasi Fair-Trade', 'icon' => 'check', 'active' => false],
        ['label' => 'Logistik & Penjemputan', 'icon' => 'truck', 'active' => false],
        ['label' => 'Laporan Dampak SDG 8', 'icon' => 'chart', 'active' => false],
        ['label' => 'Kembali ke Etalase', 'icon' => 'store', 'active' => false],
    ];

    // ---------- Top statistic cards ----------
    $stats = [
        ['label' => 'Volume Transaksi', 'badge' => '+18.4% YoY', 'value' => 'Rp 4.285.900.000', 'note' => 'Nilai kriya terbit per Maret 2025', 'type' => ''],
        ['label' => 'Hak Bersih Artisan', 'badge' => '89.4% Disalurkan', 'value' => 'Rp 3.831.594.600', 'note' => 'Rekening perajin tanpa potongan makelar', 'type' => 'dg-stat-dark'],
        ['label' => 'Sanggar Binaan', 'badge' => '28 Provinsi', 'value' => '148 Sanggar', 'note' => '100% lulus audit keaslian kriya', 'type' => ''],
        ['label' => 'Mahakarya Terbit', 'badge' => 'QC 92.5%', 'value' => '1.420 Karya', 'note' => 'Tersertifikasi Paspor Digital Kriya', 'type' => ''],
        ['label' => 'Kepatuhan SDG 8', 'badge' => 'Grade A+', 'value' => '98.2% Sesuai', 'note' => 'Standar Upah Layak & Bahan Ramah Hayati', 'type' => 'dg-stat-warn'],
    ];

    // ---------- Curation queue (table) ----------
    $curations = [
        ['name' => 'Songket Silungkang Motif Bungo Duo', 'studio' => 'Sanggar Siti Rahma', 'category' => 'Wastra Tenun', 'origin' => 'Sawahlunto, Sumbar', 'status' => 'Uji Benang Emas Tejadwal', 'status_note' => 'Lab Uji Tekstil Padang', 'wage' => 'Rp 4.750.000', 'wage_note' => '91.5% Hak Penenun', 'action' => 'Minta Sampel', 'primary' => false, 'color' => '#7a3b2e'],
        ['name' => 'Patung Asmat Kayu Besi Mbis', 'studio' => 'Sanggar Otsjanep', 'category' => 'Ukiran Kayu', 'origin' => 'Asmat, Papua Selatan', 'status' => 'SVLK Legal No. 448/KLHK', 'status_note' => 'Kayu Ulin Terbarukan Bersertifikat', 'wage' => 'Rp 8.500.000', 'wage_note' => '88.0% Hak Pemahat', 'action' => 'Setujui & Terbitkan', 'primary' => true, 'color' => '#8c7a68'],
        ['name' => 'Guci Terakota Abu Vulkanik', 'studio' => 'Padepokan Kasongan', 'category' => 'Gerabah Alami', 'origin' => 'Bantul, D.I. Yogyakarta', 'status' => 'Food-Grade SNI Lolos', 'status_note' => 'Bebas Timbal & Glazur Kimia', 'wage' => 'Rp 1.850.000', 'wage_note' => '90.2% Hak Perajin', 'action' => 'Setujui & Terbitkan', 'primary' => true, 'color' => '#6b6258'],
    ];

    // ---------- Regions ----------
    $regions = [
        ['name' => 'Jawa Tengah', 'count' => 41, 'pct' => 82, 'left' => 'Batik Tulis & Ukir Jepara', 'right' => '100% Upah Layak'],
        ['name' => 'Nusa Tenggara Timur', 'count' => 32, 'pct' => 64, 'left' => 'Tenun Ikat Pewarna Alami Sumba & Alor', 'right' => 'Pewarna 100% Organik'],
        ['name' => 'D.I. Yogyakarta', 'count' => 25, 'pct' => 50, 'left' => 'Gerabah Kasongan & Kulit Tatah Sungging', 'right' => '98% Lolos QC'],
        ['name' => 'Sumatera Barat', 'count' => 18, 'pct' => 36, 'left' => 'Songket Silungkang & Sulam Pandai Sikek', 'right' => 'Warisan Budaya Hidup'],
        ['name' => 'Papua & Pegunungan', 'count' => 14, 'pct' => 28, 'left' => 'Noken Serat Kayu Mahkota & Ukiran Asmat', 'right' => '100% Hak Ulayat'],
    ];

    // ---------- Escrow split ----------
    $escrow = [
        ['title' => 'Porsi Langsung Pengrajin', 'value' => '89.4%', 'text' => 'Transfer instan via BPD / Rekening Sanggar Desa', 'color' => 'var(--green)'],
        ['title' => 'Packaging & Cold-Chain Kriya', 'value' => '6.2%', 'text' => 'Besek bambu, silika alami, & asuransi kurir terlatih', 'color' => 'var(--terracotta)'],
        ['title' => 'Audit Kepatuhan SDG Lapangan', 'value' => '4.4%', 'text' => 'Biaya inspektur laboratorium & sertifikasi SVLK', 'color' => '#14806a'],
        ['title' => 'Biaya Calo / Perantara', 'value' => '0.0%', 'text' => 'Terkunci secara protokol fair-trade', 'color' => '#b9b3a5'],
    ];

    // ---------- Audit log ----------
    $logs = [
        ['title' => 'Verifikasi Pewarna Indigofera Selesai', 'time' => '14m lalu', 'text' => 'Kurator Budi Santoso menyelesaikan uji fermentasi daun tarum di Sanggar Giriloyo, Imogiri. Kadar sintesis 0%.', 'color' => 'var(--green)'],
        ['title' => 'Penyaluran Beasiswa ATBM', 'time' => '1j lalu', 'text' => 'Dana apresiasi regenerasi penenun disalurkan ke 12 siswa magang sanggar di Desa Prailiu, Sumba Timur.', 'color' => 'var(--green)'],
        ['title' => 'Sertifikasi SVLK Sanggar Otsjanep', 'time' => '3j lalu', 'text' => 'Dokumen legalitas kayu ulin gelondong adat dinyatakan valid oleh auditor independen KLHK.', 'color' => 'var(--green)'],
        ['title' => 'Peringatan QC Kemasan Bambu', 'time' => 'Kemarin', 'text' => 'Permintaan perbaikan peredam goncangan alami untuk batch tembikar Kasongan tujuan ekspor Belgia.', 'color' => '#b9b3a5'],
    ];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dasbor Tata Kelola — Kriya Nusantara</title>

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>

<div class="dg-shell">

    
    <aside class="dg-sidebar">
        <a href="/" class="dg-brand">
            <span class="dg-logo">K</span>
            <span>
                <strong>KRIYA NUSANTARA</strong>
                <small>PANEL MANAJEMEN UMKM</small>
            </span>
        </a>

        <div class="dg-roles">
            <a href="#">Artisan</a>
            <a href="#" class="is-active">Admin</a>
            <a href="#">Kurir</a>
        </div>

        <nav class="dg-nav">
            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="#" class="<?php echo e($item['active'] ? 'is-active' : ''); ?>">
                    <?php echo $icon($item['icon']); ?>

                    <span><?php echo e($item['label']); ?></span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </nav>

        <div class="dg-side-foot">
            <div class="dg-side-card">
                <strong>SDG 8.3 &amp; 8.9 Verified</strong>
                <small>Sistem Kurasi Etis Aktif</small>
            </div>
            <div class="dg-side-card dg-side-user">
                <span class="dg-avatar-sm"><?php echo $icon('user', 16); ?></span>
                <div>
                    <strong>Sentra Kriya Jepara</strong>
                    <small>Mode Artisan Aktif</small>
                </div>
            </div>
        </div>
    </aside>

    
    <div class="dg-body">

        
        <header class="dg-topbar">
            <label class="dg-search">
                <?php echo $icon('search', 16); ?>

                <input type="search" placeholder="Cari ID perajin, batch wastra, resi pengiriman...">
            </label>
            <div class="dg-topbar-right">
                <span class="dg-wage-badge"><i class="dg-dot"></i> Standar Upah Layak</span>
                <button type="button" class="dg-icon-btn" aria-label="Notifikasi"><?php echo $icon('bell', 18); ?></button>
                <button type="button" class="dg-icon-btn dg-icon-btn-dark" aria-label="Akun"><?php echo $icon('user', 18); ?></button>
            </div>
        </header>

        <main class="dg-content">

            
            <section class="dg-head">
                <div class="dg-head-text">
                    <div class="dg-tags">
                        <span class="dg-tag">TATA KELOLA PUSAT</span>
                        <span class="dg-tag dg-tag-live"><i class="dg-dot"></i> Audit Ledger Aktif • Real-Time Q1 2025</span>
                    </div>
                    <p class="dg-version">Integritas Smart-Contract v2.8</p>
                    <h1>Dasbor Tata Kelola &amp; Pengawasan Ekosistem UEMKAEM</h1>
                    <p class="dg-lead">
                        Instrumen pengawasan desentralisasi ekonomi kreatif, validasi kepatuhan Fair-Trade,
                        serta pelacakan realisasi upah berkeadilan (SDG 8.3 &amp; 8.9) untuk sanggar binaan kriya se-Nusantara.
                    </p>
                </div>

                <div class="dg-head-actions">
                    <div class="dg-period">
                        <button type="button">Bulan Ini</button>
                        <button type="button" class="is-active">Kuartal Ini</button>
                        <button type="button">Tahun 2025</button>
                    </div>
                    <button type="button" class="dg-btn dg-btn-soft"><?php echo $icon('download', 16); ?> Unduh SDG</button>
                    <button type="button" class="dg-btn dg-btn-primary"><?php echo $icon('plus', 16); ?> Tambah Sanggar</button>
                </div>
            </section>

            
            <section class="dg-stats">
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="dg-stat <?php echo e($s['type']); ?>">
                        <div class="dg-stat-top">
                            <span class="dg-stat-label"><?php echo e($s['label']); ?></span>
                            <span class="dg-stat-badge"><?php echo e($s['badge']); ?></span>
                        </div>
                        <strong class="dg-stat-value"><?php echo e($s['value']); ?></strong>
                        <small class="dg-stat-note"><?php echo e($s['note']); ?></small>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>

            
            <div class="dg-main-grid">

                
                <div class="dg-col">

                    
                    <section class="dg-panel">
                        <div class="dg-panel-head">
                            <div>
                                <h2><i class="dg-dot dg-dot-orange"></i> Antrean Kurasi &amp; Validasi Paspor Kriya</h2>
                                <p class="muted">Karya baru diajukan oleh sanggar binaan memerlukan verifikasi orisinalitas bahan, SVLK, dan ketertelusuran.</p>
                            </div>
                            <span class="dg-todo">3 Perlu Tindakan <?php echo $icon('filter', 14); ?></span>
                        </div>

                        <div class="dg-table-wrap">
                            <div class="dg-table">
                                <div class="dg-row dg-row-head">
                                    <span>KARYA &amp; SANGGAR</span>
                                    <span>KATEGORI &amp; ASAL</span>
                                    <span>STATUS VERIFIKASI</span>
                                    <span>REKOMENDASI UPAH</span>
                                    <span>KEPUTUSAN</span>
                                </div>

                                <?php $__currentLoopData = $curations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="dg-row">
                                        <div class="dg-work">
                                            <span class="dg-thumb" style="background: <?php echo e($c['color']); ?>"></span>
                                            <div>
                                                <strong><?php echo e($c['name']); ?></strong>
                                                <small><?php echo e($c['studio']); ?></small>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="dg-chip"><?php echo e($c['category']); ?></span>
                                            <strong class="dg-origin"><?php echo e($c['origin']); ?></strong>
                                        </div>
                                        <div class="dg-verify">
                                            <strong><?php echo e($c['status']); ?></strong>
                                            <small><?php echo e($c['status_note']); ?></small>
                                        </div>
                                        <div class="dg-wage">
                                            <strong><?php echo e($c['wage']); ?></strong>
                                            <small><?php echo e($c['wage_note']); ?></small>
                                        </div>
                                        <div>
                                            <button type="button" class="dg-btn <?php echo e($c['primary'] ? 'dg-btn-primary' : 'dg-btn-outline'); ?> dg-btn-sm">
                                                <?php echo e($c['action']); ?>

                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="dg-panel-foot">
                            <span>Semua kurasi dilindungi QR Paspor Kriya dengan sertifikasi hak cipta komunal (Indikasi Geografis).</span>
                            <a href="#">Lihat 12 Arsip Terkurasi Lengkap <?php echo $icon('arrow', 14); ?></a>
                        </div>
                    </section>

                    
                    <section class="dg-panel">
                        <div class="dg-panel-head">
                            <div>
                                <h2>Distribusi Aliran Escrow Fair-Trade</h2>
                                <p class="muted">Dekomposisi transparan tiap Rp 100.000 pembelanjaan kriya melalui ekosistem.</p>
                            </div>
                            <span class="dg-pill-mint">Direct Artisan Ledger</span>
                        </div>

                        <div class="dg-bar">
                            <span style="width: 89.4%; background: var(--green)">89.4%</span>
                            <span style="width: 6.2%; background: var(--terracotta)">6.2%</span>
                            <span style="width: 4.4%; background: #14806a">4.4%</span>
                        </div>

                        <div class="dg-escrow-grid">
                            <?php $__currentLoopData = $escrow; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="dg-escrow-item">
                                    <span class="dg-escrow-title"><i class="dg-dot" style="background: <?php echo e($e['color']); ?>"></i> <?php echo e($e['title']); ?></span>
                                    <strong><?php echo e($e['value']); ?></strong>
                                    <small><?php echo e($e['text']); ?></small>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="dg-status">
                            <span><?php echo $icon('check', 20); ?></span>
                            <p><strong>Status Escrow Pekan Ini: Sehat &amp; Tervalidasi</strong><br>
                               Total Rp 312.450.000 dialirkan otomatis dalam 24 jam terakhir ke 19 sanggar terverifikasi.</p>
                            <button type="button" class="dg-btn dg-btn-dark dg-btn-sm">Audit Ledger Escrow</button>
                        </div>
                    </section>
                </div>

                
                <div class="dg-col">

                    
                    <section class="dg-panel">
                        <div class="dg-panel-head">
                            <div>
                                <h2>Sebaran Sentra Binaan</h2>
                                <p class="muted">28 Provinsi Terjangkau</p>
                            </div>
                            <span class="dg-map-ico"><?php echo $icon('map', 18); ?></span>
                        </div>

                        <div class="dg-regions">
                            <?php $__currentLoopData = $regions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="dg-region">
                                    <div class="dg-region-top">
                                        <strong><?php echo e($r['name']); ?></strong>
                                        <span><?php echo e($r['count']); ?> Sanggar</span>
                                    </div>
                                    <div class="dg-progress"><span style="width: <?php echo e($r['pct']); ?>%"></span></div>
                                    <div class="dg-region-bottom">
                                        <small><?php echo e($r['left']); ?></small>
                                        <small><?php echo e($r['right']); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="dg-expansion">
                            <div>
                                <strong>Ekspansi Wilayah Q2</strong>
                                <small>Target: Kalimantan Barat &amp; Sulawesi Selatan</small>
                            </div>
                            <b>+12 Sanggar Rintisan</b>
                        </div>
                    </section>

                    
                    <section class="dg-panel">
                        <div class="dg-panel-head">
                            <h2><?php echo $icon('check', 20); ?> Log Audit Kurator Lapangan</h2>
                            <span class="dg-latest">Terkini</span>
                        </div>

                        <ul class="dg-log">
                            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <i class="dg-dot" style="background: <?php echo e($log['color']); ?>"></i>
                                    <div>
                                        <div class="dg-log-top">
                                            <strong><?php echo e($log['title']); ?></strong>
                                            <small><?php echo e($log['time']); ?></small>
                                        </div>
                                        <p><?php echo e($log['text']); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>

                        <button type="button" class="dg-btn dg-btn-soft dg-btn-block">Unduh Berita Acara Audit Lengkap</button>
                    </section>
                </div>
            </div>
        </main>
    </div>
</div>

</body>
</html><?php /**PATH C:\Users\user\Documents\GitHub\UEMKAEM\resources\views/dashboard.blade.php ENDPATH**/ ?>