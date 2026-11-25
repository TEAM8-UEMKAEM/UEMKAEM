<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal Artisan') — UEMKAEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .adm-alert.warn {
            background: #fffbeb;
            border: 1px solid #f59e0b;
            color: #92400e;
        }
        .adm-badge.is-warn {
            background: #fef3c7;
            color: #92400e;
        }
        .adm-badge.is-draft {
            background: #e5e7eb;
            color: #374151;
        }
        .art-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }
        .art-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .art-tab.is-active {
            background: #003631;
            border-color: #003631;
            color: #fff;
        }
        .art-price-box {
            margin-top: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            line-height: 1.45;
            border: 1px solid #d1d5db;
            background: #f9fafb;
            color: #374151;
        }
        .art-price-box.is-valid {
            background: #ecfdf5;
            border-color: #10b981;
            color: #065f46;
        }
        .art-price-box.is-warning {
            background: #fffbeb;
            border-color: #f59e0b;
            color: #92400e;
        }
        .art-photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 10px;
        }
        .art-photo-card {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px;
            background: #fff;
            display: flex;
            flex-direction: column;
            gap: 6px;
            font-size: 12px;
        }
        .art-photo-card img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 6px;
            background: #f3f4f6;
        }
    </style>
</head>
<body>
@php
    $artisan = auth()->user()?->artisan;
    $nav = [
        ['Produk Saya', 'artisan.products.index', 'artisan.products.index'],
        ['Tambah Produk', 'artisan.products.create', 'artisan.products.create'],
        ['Panduan Harga', 'price-guide', 'price-guide'],
        ['Kembali ke Etalase', 'home', 'none'],
    ];
@endphp
<div class="dg-shell">
    <aside class="dg-sidebar">
        <a href="{{ route('home') }}" class="dg-brand">
            <span class="dg-logo">K</span>
            <span><strong>KRIYA NUSANTARA</strong><small>PORTAL ARTISAN</small></span>
        </a>

        <nav class="dg-nav">
            @foreach ($nav as [$label, $route, $pattern])
                <a href="{{ route($route) }}" class="{{ request()->routeIs($pattern) ? 'is-active' : '' }}">
                    <span>{{ $label }}</span>
                </a>
            @endforeach
        </nav>

        <div class="dg-side-foot">
            <div class="dg-side-card">
                <strong>{{ $artisan?->name ?? auth()->user()->name }}</strong>
                <small>{{ $artisan?->workshop_name ?? 'Artisan Terverifikasi' }}</small>
                <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
                    @csrf
                    <button type="submit" class="dg-btn dg-btn-soft dg-btn-sm">Keluar</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="dg-body">
        <main class="dg-content">
            @if (session('success'))
                <div class="adm-alert ok" role="status">{{ session('success') }}</div>
            @endif
            @if (session('warning'))
                <div class="adm-alert warn" role="alert">{{ session('warning') }}</div>
            @endif
            @if (session('error'))
                <div class="adm-alert err" role="alert">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="adm-alert err" role="alert">
                    Periksa kembali isian Anda:
                    <ul>@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) { e.preventDefault(); }
    });
</script>
@stack('scripts')
</body>
</html>
