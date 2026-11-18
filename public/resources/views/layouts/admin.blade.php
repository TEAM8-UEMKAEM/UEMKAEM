<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel Admin') — UEMKAEM</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
@php
    $nav = [
        ['Dasbor Tata Kelola', 'dashboard', 'dashboard'],
        ['Kategori Kriya', 'admin.categories.index', 'admin.categories.*'],
        ['Material', 'admin.materials.index', 'admin.materials.*'],
        ['Wilayah', 'admin.regions.index', 'admin.regions.*'],
        ['Riwayat Perubahan', 'admin.audit-logs.index', 'admin.audit-logs.*'],
        ['Kembali ke Etalase', 'home', 'none'],
    ];
@endphp
<div class="dg-shell">
    <aside class="dg-sidebar">
        <a href="{{ route('home') }}" class="dg-brand">
            <span class="dg-logo">K</span>
            <span><strong>KRIYA NUSANTARA</strong><small>PANEL ADMIN</small></span>
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
                <strong>{{ auth()->user()->name }}</strong>
                <small>Administrator</small>
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
    // Konfirmasi sebelum mengubah status (form dengan atribut data-confirm)
    document.addEventListener('submit', function (e) {
        var msg = e.target.getAttribute('data-confirm');
        if (msg && !window.confirm(msg)) { e.preventDefault(); }
    });
</script>
</body>
</html>
