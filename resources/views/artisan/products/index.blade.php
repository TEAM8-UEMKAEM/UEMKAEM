@extends('layouts.artisan')
@section('title', 'Produk Saya')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Katalog Produk Artisan</h1>
            <p class="muted">Kelola daftar kriya Anda, pantau stok, periksa kepatuhan harga adil, dan atur status publikasi.</p>
        </div>
        <a href="{{ route('artisan.products.create') }}" class="dg-btn dg-btn-primary">+ Tambah Produk</a>
    </section>

    <div class="art-tabs">
        @php
            $tabs = [
                'all' => 'Semua',
                'published' => 'Dipublikasikan',
                'draft' => 'Draf',
                'archived' => 'Diarsipkan',
            ];
        @endphp
        @foreach ($tabs as $key => $label)
            <a href="{{ route('artisan.products.index', array_filter(['status' => $key === 'all' ? null : $key, 'q' => $search ?: null])) }}"
               class="art-tab {{ $status === $key ? 'is-active' : '' }}">
                <span>{{ $label }}</span>
                <span>({{ $counts[$key] ?? 0 }})</span>
            </a>
        @endforeach
    </div>

    <form method="GET" action="{{ route('artisan.products.index') }}" style="display:flex; gap:8px; margin-bottom:16px; max-width:420px;">
        @if ($status !== 'all')
            <input type="hidden" name="status" value="{{ $status }}">
        @endif
        <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama atau deskripsi produk..." style="flex:1; padding:8px 12px; border:1px solid #d1d5db; border-radius:8px;">
        <button type="submit" class="dg-btn dg-btn-outline dg-btn-sm">Cari</button>
    </form>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead>
                <tr>
                    <th>PRODUK</th>
                    <th>KATEGORI &amp; MATERIAL</th>
                    <th>HARGA</th>
                    <th>STOK</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($products as $product)
                @php
                    $check = $priceChecks[$product->id] ?? null;
                    $thumb = $product->images->firstWhere('is_primary', true)?->path ?? $product->image;
                @endphp
                <tr class="{{ $product->status === 'archived' ? 'is-off' : '' }}">
                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            @if ($thumb)
                                <img src="{{ $thumb }}" alt="{{ $product->name }}" style="width:48px; height:48px; object-fit:cover; border-radius:6px; background:#f3f4f6;">
                            @else
                                <div style="width:48px; height:48px; border-radius:6px; background:#f3f4f6; display:flex; align-items:center; justify-content:center; font-size:11px; color:#6b7280;">No Foto</div>
                            @endif
                            <div>
                                <strong>{{ $product->name }}</strong>
                                <br><small class="muted">{{ $product->images->count() }} foto</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span>{{ $product->category?->name ?? '-' }}</span>
                        <br><small class="muted">{{ $product->material?->name ?? 'Belum dipilih' }}</small>
                    </td>
                    <td>
                        <strong>{{ $product->formatted_price }}</strong>
                        @if ($check && $check['valid'] === false)
                            <br><span class="adm-badge is-warn" title="{{ $check['message'] }}">Di luar harga adil</span>
                        @elseif ($check && $check['valid'] === true)
                            <br><span class="adm-badge is-on">Harga adil</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $product->stock }}</strong>
                        @if ($product->stock === 0)
                            <br><small class="muted" style="color:#b91c1c;">Habis</small>
                        @elseif ($product->stock <= 3)
                            <br><small class="muted" style="color:#b45309;">Menipis</small>
                        @endif
                    </td>
                    <td>
                        @if ($product->status === 'published')
                            <span class="adm-badge is-on">Dipublikasikan</span>
                        @elseif ($product->status === 'archived')
                            <span class="adm-badge is-off">Diarsipkan</span>
                        @else
                            <span class="adm-badge is-draft">Draf</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            <a href="{{ route('artisan.products.edit', $product->id) }}" class="dg-btn dg-btn-outline dg-btn-sm">Ubah</a>

                            @if ($product->status !== 'published')
                                <form method="POST" action="{{ route('artisan.products.publish', $product->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="dg-btn dg-btn-primary dg-btn-sm">Publikasikan</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('artisan.products.unpublish', $product->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="dg-btn dg-btn-soft dg-btn-sm">Tarik ke Draf</button>
                                </form>
                            @endif

                            @if ($product->status !== 'archived')
                                <form method="POST" action="{{ route('artisan.products.archive', $product->id) }}" data-confirm="Arsipkan produk ini dari etalase?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="dg-btn dg-btn-outline dg-btn-sm">Arsipkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada produk pada filter ini. <a href="{{ route('artisan.products.create') }}">Tambah produk pertama Anda</a>.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        {{ $products->links() }}
    </div>
@endsection
