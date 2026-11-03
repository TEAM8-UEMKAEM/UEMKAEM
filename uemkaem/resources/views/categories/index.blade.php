@extends('layouts.app')

@section('title', 'Kategori Kriya - Ragam Warisan Budaya')

@section('content')
    <section class="page-head">
        <div>
            <span class="badge badge-green">Beragam Budaya &amp; Sanggar Terkurasi</span>
            <h1>Warisan Seni Kriya Nusantara yang Hidup &amp; Berdaya</h1>
            <p class="muted">Kategori kriya terkurasi dari sanggar dan perajin di seluruh Nusantara.</p>
        </div>
    </section>

    <section class="grid grid-2">
        @foreach ($categories as $category)
            <article class="card category-feature">
                <div class="category-image large">
                    @if ($category->image)
                        <img
                            src="{{ route('categories.image', $category->id) }}"
                            alt="{{ $category->name }}"
                        >
                    @endif
                </div>
                <small class="eyebrow">{{ $category->products_count }} PRODUK AKTIF</small>
                <h2>{{ $category->tagline ?? $category->name }}</h2>
                <p class="muted">{{ $category->description }}</p>
                <a href="{{ route('products.index', ['kategori' => $category->slug]) }}" class="btn btn-primary">Lihat Produk</a>
            </article>
        @endforeach
    </section>
@endsection
