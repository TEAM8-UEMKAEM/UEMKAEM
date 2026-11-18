@extends('layouts.admin')
@section('title', 'Wilayah')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Wilayah</h1>
            <p class="muted">Daerah asal produk. Kolom asal (origin) pada produk lama tidak diubah.</p>
        </div>
        <a href="{{ route('admin.regions.create') }}" class="dg-btn dg-btn-primary">+ Tambah Wilayah</a>
    </section>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead><tr><th>NAMA</th><th>PROVINSI</th><th>PRODUK</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="{{ $item->is_active ? '' : 'is-off' }}">
                    <td><strong>{{ $item->name }}</strong><br><small class="muted">{{ $item->slug }}</small></td>
                    <td>{{ $item->province ?: '—' }}</td>
                    <td>{{ $item->products_count }}</td>
                    <td><span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>@include('admin.partials.actions', ['base' => 'admin.regions', 'item' => $item, 'label' => 'wilayah'])</td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada wilayah.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
