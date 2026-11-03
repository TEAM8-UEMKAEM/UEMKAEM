<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'UEMKAEM Kriya Nusantara')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

    {{-- Navbar --}}
    <header class="navbar">
        <a href="{{ route('home') }}" class="brand"><img src="/assets/Logo.png" alt="Logo"></a>
        <nav class="search-nav">
            <form method="GET" action="{{ route('products.index') }}" class="search-bar">

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
                    value="{{ request('q') }}"
                    placeholder="Cari tenun, tembikar..."
                    autocomplete="off">

            </form>
        </nav>


        <nav>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Jelajahi Produk</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori Kriya</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('story.*') ? 'active' : '' }}">Cerita Artisan</a>
            <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('impact.*') ? 'active' : '' }}">Fair-Trade Impact</a>
        </nav>
    </header>

    <main class="container">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')
</body>
</html>
