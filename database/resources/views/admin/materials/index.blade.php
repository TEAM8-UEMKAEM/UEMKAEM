@extends('layouts.admin')
@section('title', 'Material')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Material</h1>
            <p class="muted">Bahan baku yang dipakai pada aturan harga dan panduan harga artisan.</p>
        </div>
        <a href="{{ route('admin.materials.create') }}" class="dg-btn dg-btn-primary">+ Tambah Material</a>
    </section>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead><tr><th>NAMA</th><th>SATUAN</th><th>PRODUK</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="{{ $item->is_active ? '' : 'is-off' }}">
                    <td><strong>{{ $item->name }}</strong><br><small class="muted">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</small></td>
                    <td>{{ $item->unit ?: '—' }}</td>
                    <td>{{ $item->products_count }}</td>
                    <td><span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>@include('admin.partials.actions', ['base' => 'admin.materials', 'item' => $item, 'label' => 'material'])</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada material.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
