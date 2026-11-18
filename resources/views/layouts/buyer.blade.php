<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="web_standard" name="shell-type">
    <title>@yield('title', 'UEMKAEM Kriya Nusantara')</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; font-family: 'Plus Jakarta Sans', sans-serif; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-tertiary-container": "#83c1a1",
                        "surface-tint": "#316761",
                        "surface-dim": "#dcdad3",
                        "surface-container-high": "#ebe8e1",
                        "surface-container-highest": "#e5e2db",
                        "surface-variant": "#e5e2db",
                        "tertiary-fixed": "#b1f0ce",
                        "primary-container": "#134e48",
                        "error-container": "#ffdad6",
                        "on-surface": "#1c1c18",
                        "surface": "#fcf9f2",
                        "inverse-primary": "#9ad1c9",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed": "#3a0a00",
                        "on-tertiary": "#ffffff",
                        "tertiary-container": "#0d5037",
                        "outline": "#707977",
                        "on-secondary-container": "#6b1a00",
                        "primary": "#003631",
                        "secondary-container": "#fb7b54",
                        "outline-variant": "#bfc8c6",
                        "on-primary-container": "#87beb6",
                        "tertiary-fixed-dim": "#95d4b3",
                        "inverse-surface": "#31312c",
                        "on-secondary": "#ffffff",
                        "on-error": "#ffffff",
                        "surface-container-low": "#f6f3ec",
                        "secondary-fixed-dim": "#ffb59f",
                        "secondary": "#a53c19",
                        "tertiary": "#003724",
                        "background": "#fcf9f2",
                        "primary-fixed": "#b5ede5",
                        "surface-bright": "#fcf9f2",
                        "on-error-container": "#93000a",
                        "error": "#ba1a1a",
                        "inverse-on-surface": "#f3f0e9",
                        "on-primary-fixed": "#00201d",
                        "secondary-fixed": "#ffdbd1",
                        "on-primary": "#ffffff",
                        "on-tertiary-fixed": "#002114",
                        "on-tertiary-fixed-variant": "#0e5138",
                        "surface-container": "#f1eee7",
                        "on-primary-fixed-variant": "#154f49",
                        "on-background": "#1c1c18",
                        "on-surface-variant": "#404947",
                        "primary-fixed-dim": "#9ad1c9",
                        "on-secondary-fixed-variant": "#842503"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "space-sm": "0.5rem",
                        "space-xs": "0.25rem",
                        "gutter": "1.5rem",
                        "space-lg": "1.5rem",
                        "space-md": "1rem",
                        "space-xl": "2.5rem",
                        "margin": "2rem"
                    },
                    "fontFamily": {
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-xl-mobile": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-xl": ["Plus Jakarta Sans", "sans-serif"],
                        "body-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    "fontSize": {
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "700" }],
                        "headline-xl-mobile": ["30px", { "lineHeight": "38px", "letterSpacing": "-0.01em", "fontWeight": "800" }],
                        "headline-xl": ["44px", { "lineHeight": "52px", "letterSpacing": "-0.02em", "fontWeight": "800" }],
                        "body-sm": ["12px", { "lineHeight": "18px", "fontWeight": "400" }],
                        "body-lg": ["16px", { "lineHeight": "26px", "fontWeight": "400" }],
                        "body-md": ["14px", { "lineHeight": "22px", "fontWeight": "400" }],
                        "label-lg": ["14px", { "lineHeight": "20px", "fontWeight": "600" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.015em", "fontWeight": "700" }],
                        "label-md": ["12px", { "lineHeight": "16px", "fontWeight": "600" }],
                        "label-sm": ["11px", { "lineHeight": "14px", "letterSpacing": "0.02em", "fontWeight": "600" }],
                        "headline-sm": ["16px", { "lineHeight": "24px", "fontWeight": "700" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased">
    @php
        $headerWishlistCount = auth()->check()
            ? \App\Models\Wishlist::where('user_id', auth()->id())->count()
            : 0;
    @endphp

    {{-- Stitch Fixed Top Header --}}
    <header class="fixed top-0 left-0 w-full z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
        <div class="h-20 max-w-7xl mx-auto px-margin flex items-center justify-between gap-gutter">
            <a href="{{ route('home') }}" class="flex items-center gap-space-md shrink-0">
                <div class="flex flex-col">
                    <span class="font-headline-sm text-headline-sm tracking-tight text-primary leading-tight">UEMKAEM</span>
                    <span class="font-label-sm text-label-sm tracking-widest uppercase text-on-surface-variant">Kriya Nusantara</span>
                </div>
            </a>

            <div class="flex-1 max-w-xs xl:max-w-sm hidden md:block">
                <form method="GET" action="{{ route('products.index') }}" class="relative flex items-center w-full">
                    <span class="material-symbols-outlined absolute left-3.5 text-outline pointer-events-none text-[20px]">search</span>
                    <input
                        name="q"
                        value="{{ request('q') }}"
                        class="w-full pl-10 pr-12 py-2 bg-surface-container rounded-full font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest transition-colors"
                        placeholder="Cari tenun, tembikar, batik..."
                        type="text"
                    >
                    <span class="absolute right-3 px-1.5 py-0.5 rounded bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm tracking-wide">⌘K</span>
                </form>
            </div>

            <nav class="hidden lg:flex items-center gap-space-lg">
                <a class="font-label-md text-label-md {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }} transition-colors" href="{{ route('home') }}">Beranda</a>
                <a class="font-label-md text-label-md {{ request()->routeIs('products.*', 'buyer.products.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }} transition-colors" href="{{ route('products.index') }}">Jelajahi Produk</a>
                <a class="font-label-md text-label-md {{ request()->routeIs('categories.*') ? 'text-primary font-bold' : 'text-on-surface-variant hover:text-on-surface' }} transition-colors" href="{{ route('categories.index') }}">Kategori Kriya</a>
                <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('categories.index') }}">Cerita Artisan</a>
                <a class="font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors" href="{{ route('home') }}#dampak">Fair-Trade Impact</a>
            </nav>

            <div class="flex items-center gap-space-md shrink-0">
                <a
                    href="{{ route('wishlist.index') }}"
                    dusk="nav-wishlist"
                    title="Wishlist Saya"
                    class="relative w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low text-secondary hover:bg-surface-container transition-colors"
                >
                    <span class="material-symbols-outlined text-[22px]">favorite</span>
                    <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-secondary text-on-secondary font-label-sm text-[10px] leading-none flex items-center justify-center font-bold">
                        {{ $headerWishlistCount }}
                    </span>
                </a>

                <a
                    href="{{ route('account') }}"
                    title="Akun Pembeli"
                    class="flex items-center gap-space-sm pl-space-xs"
                >
                    <div class="w-9 h-9 rounded-full bg-primary text-on-primary flex items-center justify-center font-label-md font-bold">
                        {{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'U' }}
                    </div>
                    <span class="hidden sm:inline font-label-md text-label-md text-on-surface font-semibold">
                        {{ auth()->check() ? auth()->user()->name : 'Tamu' }}
                    </span>
                </a>
            </div>
        </div>
    </header>

    <main class="w-full pt-20 bg-surface min-h-screen">
        @yield('content')
    </main>

    {{-- Stitch Footer --}}
    <footer class="w-full bg-surface-container-low mt-space-xl shadow-[0_-1px_8px_rgba(0,0,0,0.02)]">
        <div class="max-w-7xl mx-auto px-margin pt-space-xl pb-space-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-space-xl mb-space-xl">
                <div class="lg:col-span-2 flex flex-col gap-space-md">
                    <div class="flex items-center gap-space-sm">
                        <span class="font-headline-md text-headline-md text-primary font-bold">UEMKAEM</span>
                        <span class="font-label-sm text-label-sm uppercase tracking-widest text-secondary font-bold">Kriya Nusantara</span>
                    </div>
                    <p class="font-body-md text-body-md text-on-surface-variant max-w-sm">
                        Menghubungkan perajin kriya tradisional pelosok Nusantara langsung ke tangan Anda dengan prinsip perdagangan berkeadilan (Fair-Trade), transparansi asal bahan, dan pelestarian warisan budaya adiluhung.
                    </p>
                    <div class="flex items-center gap-space-sm flex-wrap">
                        <span class="px-3 py-1 rounded-full bg-surface-container-high font-label-sm text-label-sm text-primary font-medium flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">verified</span>Fair-Price Certified
                        </span>
                        <span class="px-3 py-1 rounded-full bg-surface-container-high font-label-sm text-label-sm text-primary font-medium flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px]">eco</span>100% Bahan Alami
                        </span>
                    </div>
                </div>

                <div class="flex flex-col gap-space-sm">
                    <span class="font-label-lg text-label-lg text-on-surface font-bold">Kategori Kriya</span>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">Tenun &amp; Wastra</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">Batik Cap &amp; Tulis</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">Tembikar Kasongan</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">Anyaman Rotan</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('products.index') }}">Ukiran Kayu Jati</a>
                </div>

                <div class="flex flex-col gap-space-sm">
                    <span class="font-label-lg text-label-lg text-on-surface font-bold">Komitmen Kami</span>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('home') }}#dampak">Ekosistem Fair-Trade</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('categories.index') }}">Profil Perajin Daerah</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('home') }}#dampak">Dana Komunitas Desa</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="{{ route('home') }}">Jaminan Keaslian Kriya</a>
                </div>

                <div class="flex flex-col gap-space-sm">
                    <span class="font-label-lg text-label-lg text-on-surface font-bold">Bantuan &amp; Kontak</span>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Panduan Pembeli</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Pengiriman &amp; Ekspedisi</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Daftar Jadi Mitra Kriya</a>
                    <a class="font-body-md text-body-md text-on-surface-variant hover:text-primary transition-colors" href="#">Kebijakan Privasi</a>
                </div>
            </div>

            <div class="pt-space-lg flex flex-col sm:flex-row items-center justify-between gap-space-md border-t border-outline-variant/30">
                <p class="font-body-sm text-body-sm text-on-surface-variant">
                    &copy; {{ date('Y') }} UEMKAEM Kriya Nusantara. Seluruh Hak Cipta Dilindungi. Mendukung Kemandirian Perajin Indonesia.
                </p>
                <div class="flex items-center gap-space-md">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Didukung oleh Koperasi Kriya Mandiri &amp; Komunitas Wastra Nusantara</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
