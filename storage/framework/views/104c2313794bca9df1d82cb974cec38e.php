<?php $__env->startSection('title', 'Jelajahi Produk - Katalog Kriya Nusantara'); ?>

<?php $__env->startSection('content'); ?>
    
    <div class="crumb-bar">
        <nav class="breadcrumb">
            <a href="<?php echo e(url('/')); ?>">Beranda</a>
            <span>&rsaquo;</span>
            <b>Jelajahi Produk Kriya</b>
        </nav>
        <span class="crumb-pill">
            <i class="dot"></i><?php echo e($products->total()); ?> Mahakarya Terkurasi
            <em>&bull;</em> SDG 8.3 &amp; 8.9 Bersertifikat
        </span>
    </div>

    
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

    
    <form method="GET" action="<?php echo e(route('products.index')); ?>" class="card filters">
        <div class="filters-row">
            <label class="search-field">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Cari tenun ikat, gerabah kasongan, batik indigofera...">
            </label>
            <label class="sort-field">
                <span>Urutkan:</span>
                <select name="urut" onchange="this.form.submit()">
                    <option value="">Paling Populer</option>
                    <option value="termurah" <?php if(request('urut') === 'termurah'): echo 'selected'; endif; ?>>Harga Termurah</option>
                    <option value="fairtrade" <?php if(request('urut') === 'fairtrade'): echo 'selected'; endif; ?>>Fair-Trade Tertinggi</option>
                </select>
            </label>
            <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline">Reset Filter</a>
        </div>

        <div class="chips">
            <span class="chips-label">KATEGORI KRIYA:</span>
            <a href="<?php echo e(route('products.index')); ?>" class="chip <?php echo e(request('kategori') ? '' : 'active'); ?>">Semua Kriya (<?php echo e($products->total()); ?>)</a>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('products.index', ['kategori' => $category->slug])); ?>"
                   class="chip <?php echo e(request('kategori') === $category->slug ? 'active' : ''); ?>"><?php echo e($category->name); ?><?php if(isset($category->products_count)): ?> (<?php echo e($category->products_count); ?>)<?php endif; ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="filters-adv">
            <label class="field">
                <small>Asal Daerah Nusantara</small>
                <select name="provinsi" onchange="this.form.submit()">
                    <option value="">Semua Provinsi</option>
                    <?php $__currentLoopData = ($provinces ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $province): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($province); ?>" <?php if(request('provinsi') === $province): echo 'selected'; endif; ?>><?php echo e($province); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </label>
            <label class="field">
                <small>Sertifikasi Fair-Trade</small>
                <select name="sertifikasi" onchange="this.form.submit()">
                    <option value="">Semua Sertifikasi</option>
                    <option value="90" <?php if(request('sertifikasi') === '90'): echo 'selected'; endif; ?>>Fair-Trade 90% ke atas</option>
                    <option value="80" <?php if(request('sertifikasi') === '80'): echo 'selected'; endif; ?>>Fair-Trade 80% ke atas</option>
                </select>
            </label>
            <label class="field">
                <small>Rentang Nominal (IDR)</small>
                <select name="harga" onchange="this.form.submit()">
                    <option value="">Semua Harga</option>
                    <option value="0-250000" <?php if(request('harga') === '0-250000'): echo 'selected'; endif; ?>>Di bawah Rp 250.000</option>
                    <option value="250000-1000000" <?php if(request('harga') === '250000-1000000'): echo 'selected'; endif; ?>>Rp 250.000 - Rp 1.000.000</option>
                    <option value="1000000-" <?php if(request('harga') === '1000000-'): echo 'selected'; endif; ?>>Di atas Rp 1.000.000</option>
                </select>
            </label>
            <label class="field field-check">
                <small>Karya Master Artisan</small>
                <span>
                    <input type="checkbox" name="regenerasi" value="1" <?php if(request('regenerasi')): echo 'checked'; endif; ?> onchange="this.form.submit()">
                    Khusus Regenerasi Budaya
                </span>
            </label>
        </div>
    </form>

    <div class="result-bar">
        <p class="muted">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="m8 12 3 3 5-6"/></svg>
            Menampilkan <?php echo e($products->count()); ?> mahakarya terverifikasi
        </p>
        <div class="legend">
            <span><i class="lg lg-green"></i>% Hak Artisan</span>
            <span><i class="lg lg-orange"></i>% Material Alami</span>
            <span><i class="lg lg-gray"></i>% Logistik &amp; Kemasan</span>
        </div>
    </div>

    
    <section class="grid grid-4">
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p>Produk tidak ditemukan.</p>
        <?php endif; ?>
    </section>

    <div class="pagination-wrap">
        <p class="pager-info">
            Halaman <?php echo e($products->currentPage()); ?> dari <?php echo e($products->lastPage()); ?>

            <em>&bull;</em> Menampilkan <?php echo e($products->count()); ?> dari <?php echo e($products->total()); ?> total kriya nusantara
        </p>
        <?php echo e($products->links('partials.pagination')); ?>

    </div>

    
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\user\Documents\GitHub\UEMKAEM\resources\views/products/index.blade.php ENDPATH**/ ?>