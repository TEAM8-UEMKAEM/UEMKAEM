@extends('layouts.admin')
@section('title', 'Aturan Harga Adil')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Aturan Harga Adil</h1>
            <p class="muted">Batas harga minimum dan maksimum untuk setiap kombinasi kategori dan material.</p>
        </div>
        <a href="{{ route('admin.price-rules.create') }}" class="dg-btn dg-btn-primary">+ Tambah Aturan</a>
    </section>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead><tr><th>KATEGORI</th><th>MATERIAL</th><th>MINIMUM</th><th>MAKSIMUM</th><th>STATUS</th><th>AKSI</th></tr></thead>
            <tbody>
            @forelse ($items as $item)
                <tr class="{{ $item->is_active ? '' : 'is-off' }}">
                    <td><strong>{{ $item->category->name }}</strong></td>
                    <td>{{ $item->material->name }}</td>
                    <td>Rp {{ number_format($item->min_price, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->max_price, 0, ',', '.') }}</td>
                    <td><span class="adm-badge {{ $item->is_active ? 'is-on' : 'is-off' }}">{{ $item->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                    <td>@include('admin.partials.actions', ['base' => 'admin.price-rules', 'item' => $item, 'label' => 'aturan harga'])</td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada aturan harga.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
