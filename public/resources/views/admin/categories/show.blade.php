@extends('layouts.admin')
@section('title', $item->name)

@section('content')
    <section class="adm-head">
        <div>
            <h1>{{ $item->name }}</h1>
            <span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        @include('admin.partials.actions', ['base' => 'admin.categories', 'item' => $item, 'label' => 'kategori'])
    </section>

    <dl class="adm-dl">
        <dt>Slug</dt><dd>{{ $item->slug }}</dd>
        <dt>Tagline</dt><dd>{{ $item->tagline ?: '—' }}</dd>
        <dt>Deskripsi</dt><dd>{{ $item->description ?: '—' }}</dd>
        <dt>Grup</dt><dd>{{ $item->group ?: '—' }}</dd>
        <dt>Lencana</dt><dd>{{ $item->badge ?: '—' }}</dd>
        <dt>Sentra / Daerah</dt><dd>{{ $item->regions ?: '—' }}</dd>
        <dt>Tag</dt><dd>{{ $item->tags ?: '—' }}</dd>
        <dt>Jumlah Artisan</dt><dd>{{ $item->artisans_count ?? '—' }}</dd>
        <dt>Jumlah Produk</dt><dd>{{ $item->products_count }} <a href="{{ route('products.index', ['kategori' => $item->slug]) }}">(lihat di katalog)</a></dd>
        <dt>Gambar</dt>
        <dd>@if ($item->image)<img src="{{ route('categories.image', $item->id) }}" alt="{{ $item->name }}" style="max-width:240px;border-radius:10px;">@else — @endif</dd>
    </dl>
    <p style="margin-top:16px;"><a href="{{ route('admin.categories.index') }}">&larr; Kembali ke daftar</a></p>
@endsection
