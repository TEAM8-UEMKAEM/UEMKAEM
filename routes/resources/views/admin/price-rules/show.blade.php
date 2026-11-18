@extends('layouts.admin')
@section('title', 'Aturan Harga')

@section('content')
    <section class="adm-head">
        <div>
            <h1>{{ $item->name }}</h1>
            <span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        @include('admin.partials.actions', ['base' => 'admin.price-rules', 'item' => $item, 'label' => 'aturan harga'])
    </section>

    <dl class="adm-dl">
        <dt>Kategori</dt><dd>{{ $item->category->name }}</dd>
        <dt>Material</dt><dd>{{ $item->material->name }}</dd>
        <dt>Harga minimum</dt><dd>Rp {{ number_format($item->min_price, 0, ',', '.') }}</dd>
        <dt>Harga maksimum</dt><dd>Rp {{ number_format($item->max_price, 0, ',', '.') }}</dd>
    </dl>
    <p style="margin-top:16px;"><a href="{{ route('admin.price-rules.index') }}">&larr; Kembali ke daftar</a></p>
@endsection
