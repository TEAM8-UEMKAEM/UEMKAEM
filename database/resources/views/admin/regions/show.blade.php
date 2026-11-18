@extends('layouts.admin')
@section('title', $item->name)

@section('content')
    <section class="adm-head">
        <div>
            <h1>{{ $item->name }}</h1>
            <span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        @include('admin.partials.actions', ['base' => 'admin.regions', 'item' => $item, 'label' => 'wilayah'])
    </section>

    <dl class="adm-dl">
        <dt>Slug</dt><dd>{{ $item->slug }}</dd>
        <dt>Provinsi</dt><dd>{{ $item->province ?: '—' }}</dd>
        <dt>Jumlah Produk</dt><dd>{{ $item->products_count }}</dd>
    </dl>
    <p style="margin-top:16px;"><a href="{{ route('admin.regions.index') }}">&larr; Kembali ke daftar</a></p>
@endsection
