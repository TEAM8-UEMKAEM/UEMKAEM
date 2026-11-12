<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'UEMKAEM Kriya Nusantara'); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
</head>
<body>

    
    <header class="navbar">
        <a href="<?php echo e(route('home')); ?>" class="brand"><img src="/assets/Logo.png" alt="Logo"></a>
        <nav class="search-nav">
            <form method="GET" action="<?php echo e(route('products.index')); ?>" class="search-bar">

            <!-- Search Icon -->
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg"
                    width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round">
                    <circle cx="11" cy="11" r="7"></circle>
                    <path d="m20 20-4-4"></path>
                </svg>

            <!-- Search Input -->
                <input
                    type="text"
                    name="q"
                    value="<?php echo e(request('q')); ?>"
                    placeholder="Cari tenun, tembikar..."
                    autocomplete="off">

            </form>
        </nav>


        <nav>
            <a href="<?php echo e(route('home')); ?>" class="<?php echo e(request()->routeIs('home') ? 'active' : ''); ?>">Beranda</a>
            <a href="<?php echo e(route('products.index')); ?>" class="<?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">Jelajahi Produk</a>
            <a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">Kategori Kriya</a>
            <a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('story.*') ? 'active' : ''); ?>">Cerita Artisan</a>
            <a href="<?php echo e(route('categories.index')); ?>" class="<?php echo e(request()->routeIs('impact.*') ? 'active' : ''); ?>">Fair-Trade Impact</a>

            
            <div class="account-menu" id="account-menu">
                <button type="button" class="account-toggle" id="account-toggle"
                        aria-label="Menu akun" aria-haspopup="true" aria-expanded="false" aria-controls="account-dropdown">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M5 20c0-3.500 3-6 7-6s7 2.500 7 6z"></path>
                    </svg>
                </button>

                <div class="account-dropdown" id="account-dropdown" hidden>
                    <a href="<?php echo e(route('dashboard')); ?>">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="9" rx="1.500"></rect><rect x="14" y="3" width="7" height="5" rx="1.500"></rect><rect x="14" y="12" width="7" height="9" rx="1.500"></rect><rect x="3" y="16" width="7" height="5" rx="1.500"></rect></svg>
                        Dashboard
                    </a>
                    <a href="<?php echo e(route('account')); ?>">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.500"></circle><path d="M5 20c0-3.500 3-6 7-6s7 2.500 7 6z"></path></svg>
                        Account
                    </a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        (function () {
            var toggle = document.getElementById('account-toggle');
            var menu = document.getElementById('account-dropdown');
            var wrap = document.getElementById('account-menu');
            if (!toggle || !menu) { return; }

            function setOpen(open) {
                menu.hidden = !open;
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                setOpen(menu.hidden);
            });

            document.addEventListener('click', function (e) {
                if (!wrap.contains(e.target)) { setOpen(false); }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') { setOpen(false); toggle.focus(); }
            });
        })();
    </script>
</body>
</html><?php /**PATH C:\Users\user\Documents\GitHub\UEMKAEM\resources\views/layouts/app.blade.php ENDPATH**/ ?>