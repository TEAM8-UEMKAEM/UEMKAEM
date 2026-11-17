@extends('layouts.admin')
@section('title', 'Kategori Kriya')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Kategori Kriya</h1>
            <p class="muted">Kategori yang dinonaktifkan hilang dari katalog publik dan pilihan baru, tetapi produknya tetap aman.</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="dg-btn dg-btn-primary">+ Tambah Kategori</a>
    </section>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead><tr><th>NAMA</th><th>GRUP</th><th>PRODUK</th><th>GAMBAR</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="{{ $item->is_active ? '' : 'is-off' }}">
                    <td><strong>{{ $item->name }}</strong><br><small class="muted">{{ $item->slug }}</small></td>
                    <td>{{ $item->group ?: '—' }}</td>
                    <td>{{ $item->products_count }}</td>
                    <td>{{ $item->has_image ? 'Ada' : '—' }}</td>
                    <td><span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>@include('admin.partials.actions', ['base' => 'admin.categories', 'item' => $item, 'label' => 'kategori'])</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
