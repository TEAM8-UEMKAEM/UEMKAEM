@extends('layouts.app')

@section('title', 'Panduan Harga Artisan - UEMKAEM')

@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp
<style>
    .pg-wrap { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1fr); gap:24px; align-items:start; }
    @media (max-width: 860px) { .pg-wrap { grid-template-columns:1fr; } }
    .pg-card { padding:24px; display:grid; gap:14px; }
    .pg-card label { display:grid; gap:6px; font-weight:600; font-size:.9rem; }
    .pg-card input, .pg-card select { font:inherit; padding:11px 12px; border:1px solid var(--border, #e5dfd2); border-radius:10px; background:#fff; }
    .pg-card small { font-weight:400; color:var(--muted, #6b6b60); }
    .pg-error { background:#fbe9e3; color:var(--terracotta, #b4532a); padding:10px 14px; border-radius:10px; }
    .pg-row { display:flex; justify-content:space-between; gap:12px; padding:10px 0; border-bottom:1px solid var(--border, #e5dfd2); }
    .pg-row.total { font-weight:800; font-size:1.1rem; border-bottom:0; }
    .pg-status { padding:14px 16px; border-radius:12px; font-weight:600; }
    .pg-status.ok { background:var(--green-light, #e6f3ec); color:var(--green, #2f6b4f); }
    .pg-status.warn { background:#fbe9e3; color:var(--terracotta, #b4532a); }
    .pg-status.info { background:#f3efe4; color:var(--muted, #6b6b60); }
</style>

<section class="page-head">
    <div>
        <span class="badge badge-green">Alat Bantu Artisan</span>
        <h1>Panduan Harga Adil untuk Karya Anda</h1>
        <p class="muted">Isi biaya bahan dan lama pengerjaan. Kami menghitung perkiraan harga dan memeriksanya terhadap batas harga adil yang berlaku.</p>
    </div>
</section>

<div class="pg-wrap">
    <form method="GET" action="{{ route('price-guide') }}" class="card pg-card">
        <h2>1. Isi data karya</h2>

        @if ($guideErrors)
            <div class="pg-error"><ul style="margin:0 0 0 18px;">@foreach ($guideErrors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <label>Jenis kriya
            <select name="category_id" required>
                <option value="">Pilih jenis kriya</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </label>

        <label>Bahan utama
            <select name="material_id" required>
                <option value="">Pilih bahan</option>
                @foreach ($materials as $m)
                    <option value="{{ $m->id }}" @selected(request('material_id') == $m->id)>{{ $m->name }}</option>
                @endforeach
            </select>
        </label>

        <label>Total biaya bahan (Rp)
            <input type="number" name="material_cost" min="0" step="1" required value="{{ request('material_cost') }}" placeholder="contoh: 150000">
            <small>Jumlah seluruh biaya bahan untuk satu karya.</small>
        </label>

        <label>Lama pengerjaan (jam)
            <input type="number" name="labor_hours" min="0.1" step="0.1" required value="{{ request('labor_hours') }}" placeholder="contoh: 6">
            <small>Total jam kerja untuk menyelesaikan satu karya. Boleh desimal, misalnya 2,5.</small>
        </label>

        <button type="submit" class="btn btn-primary">Hitung Perkiraan Harga</button>
    </form>

    <div class="card pg-card">
        <h2>2. Hasil perhitungan</h2>

        @if ($result === null)
            <p class="muted">Hasil akan muncul di sini setelah Anda mengisi data di sebelah kiri.</p>
            <p><small>Rumus: <b>harga perkiraan = biaya bahan + (jam kerja × tarif upah per jam)</b>.
                @if ($laborRate > 0) Tarif upah per jam saat ini: <b>{{ $rp($laborRate) }}</b>. @endif</small></p>
        @elseif (! $result['ready'])
            <div class="pg-status info">{{ $result['message'] }}</div>
        @else
            @php $check = $result['check']; @endphp
            <p class="muted">{{ $category->name }} &middot; {{ $material->name }}</p>

            <div>
                <div class="pg-row"><span>Biaya bahan</span><b>{{ $rp($result['material_cost']) }}</b></div>
                <div class="pg-row">
                    <span>Biaya tenaga kerja<br><small>{{ rtrim(rtrim(number_format($result['labor_hours'], 1, ',', '.'), '0'), ',') }} jam × {{ $rp($result['labor_rate']) }}</small></span>
                    <b>{{ $rp($result['labor_cost']) }}</b>
                </div>
                <div class="pg-row total"><span>Perkiraan harga</span><span>{{ $rp($result['estimated_price']) }}</span></div>
            </div>

            @if ($check['rule'])
                <div class="pg-row"><span>Batas minimum harga adil</span><b>{{ $rp($check['rule']['min_price']) }}</b></div>
                <div class="pg-row"><span>Batas maksimum harga adil</span><b>{{ $rp($check['rule']['max_price']) }}</b></div>
            @endif

            <div class="pg-status {{ $check['valid'] === true ? 'ok' : ($check['valid'] === false ? 'warn' : 'info') }}">
                @if ($check['valid'] === true) &#10003; Sesuai aturan harga adil. @elseif ($check['valid'] === false) &#9888; Tidak sesuai aturan harga adil. @else Belum dapat divalidasi. @endif
                <br><span style="font-weight:400;">{{ $check['message'] }}</span>
            </div>
        @endif
    </div>
</div>
@endsection
