@extends('layouts.buyer')

@section('title', 'Masuk ke Akun - UEMKAEM')

@section('content')
    <div class="max-w-7xl mx-auto px-margin py-space-xl">
        <section dusk="login-page" class="bg-surface-container-lowest max-w-md mx-auto rounded-2xl p-8 text-center shadow-sm">
            <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-primary-container/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-[28px]">lock</span>
            </div>
            <h1 class="font-headline-md text-headline-md text-on-surface">Masuk ke Akun UEMKAEM</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-2 mb-6">
                Silakan masuk terlebih dahulu untuk menyimpan dan mengelola daftar produk kriya favorit Anda di Wishlist.
            </p>
            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-primary-container text-on-primary font-label-md text-label-md font-bold hover:bg-primary transition-colors">
                Kembali ke Katalog
            </a>
        </section>
    </div>
@endsection
