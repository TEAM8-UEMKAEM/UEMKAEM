@extends('layouts.admin')
@section('title', $item->name)

@section('content')
    <section class="adm-head">
        <div>
            <h1>{{ $item->name }}</h1>
            <span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span>
        </div>
        @include('admin.partials.actions', ['base' => 'admin.materials', 'item' => $item, 'label' => 'material'])
    </section>

    <dl class="adm-dl">
        <dt>Slug</dt><dd>{{ $item->slug }}</dd>
        <dt>Satuan</dt><dd>{{ $item->unit ?: '—' }}</dd>
        <dt>Deskripsi</dt><dd>{{ $item->description ?: '—' }}</dd>
        <dt>Jumlah Produk</dt><dd>{{ $item->products_count }}</dd>
    </dl>
    <p style="margin-top:16px;"><a href="{{ route('admin.materials.index') }}">&larr; Kembali ke daftar</a></p>
@endsection
