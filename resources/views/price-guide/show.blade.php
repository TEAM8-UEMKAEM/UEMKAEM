@extends('layouts.app')

@section('title', 'Panduan Harga Adil - UEMKAEM Kriya Nusantara')

@section('content')
    <style>
        .pg-form { display:grid; gap:16px; padding:24px; margin-bottom:24px; }
        .pg-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        .pg-form label { display:grid; gap:6px; font-weight:600; font-size:.88rem; }
        .pg-form select, .pg-form input { font:inherit; padding:10px 12px; border:1px solid var(--border); border-radius:10px; background:#fff; }
        .pg-form small { color:var(--muted); font-weight:400; }
        .pg-err { color:var(--terracotta); }
        .pg-notice { padding:14px 18px; border-radius:12px; background:#fbf3e4; border:1px solid #ecd9ae; margin-bottom:20px; }
        .pg-result { padding:24px; display:grid; gap:12px; }
        .pg-row { display:flex; justify-content:space-between; gap:16px; padding:10px 0; border-bottom:1px solid var(--border); flex-wrap:wrap; }
        .pg-row:last-of-type { border-bottom:0; }
        .pg-total { font-size:1.5rem; }
        .pg-status { padding:14px 18px; border-radius:12px; font-weight:600; }
        .pg-status.ok { background:var(--green-light); color:var(--green); }
        .pg-status.bad { background:#fbe9e3; color:var(--terracotta); }
        .pg-status.info { background:#f3efe4; color:var(--muted); }
        @media (max-width:640px) { .pg-grid { grid-template-columns:1fr; } }
    </style>

    @php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); @endphp

    <div class="crumb-bar">
        <nav class="breadcrumb">
            <a href="{{ url('/') }}">Beranda</a><span>&rsaquo;</span><b>Panduan Harga</b>
        </nav>
    </div>

    <section class="page-head">
        <div>
            <span class="badge badge-green">Panduan Harga Adil untuk Artisan</span>
            <h1>Hitung Harga Jual yang Adil untuk Karya Anda</h1>
            <p class="muted">Isi biaya bahan dan lama pengerjaan. Kami menghitung biaya tenaga kerja dan membandingkan hasilnya dengan batas harga adil.</p>
        </div>
    </section>

    @if ($hourlyRate === null)
        <div class="pg-notice">Tarif upah per jam belum ditetapkan oleh administrator, sehingga perhitungan belum dapat dilakukan. Silakan coba lagi nanti.</div>
    @endif

    <form method="GET" action="{{ route('price-guide') }}" class="card pg-form">
        <div class="pg-grid">
            <label>Kategori kriya
                <select name="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected(old('category_id', request('category_id')) == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <small class="pg-err">{{ $message }}</small> @enderror
            </label>
            <label>Material utama
                <select name="material_id" required>
                    <option value="">Pilih material</option>
                    @foreach ($materials as $m)
                        <option value="{{ $m->id }}" @selected(old('material_id', request('material_id')) == $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
                @error('material_id') <small class="pg-err">{{ $message }}</small> @enderror
            </label>
        </div>
        <div class="pg-grid">
            <label>Total biaya bahan (Rp)
                <input type="number" name="material_cost" min="0" step="1" required value="{{ old('material_cost', request('material_cost')) }}">
                <small>Seluruh biaya bahan untuk satu produk.</small>
                @error('material_cost') <small class="pg-err">{{ $message }}</small> @enderror
            </label>
            <label>Lama pengerjaan (jam)
                <input type="number" name="labor_hours" min="0" step="0.25" required value="{{ old('labor_hours', request('labor_hours')) }}">
                <small>Boleh desimal, contoh 2,5 ditulis 2.5</small>
                @error('labor_hours') <small class="pg-err">{{ $message }}</small> @enderror
            </label>
        </div>
        <div><button type="submit" class="btn btn-primary" @disabled($hourlyRate === null)>Hitung Harga</button></div>
    </form>

    @if ($result)
        @php $c = $result['check']; @endphp
        <section class="card pg-result">
            <h2>Hasil Perhitungan</h2>

            <div class="pg-row"><span>Biaya bahan</span><strong>{{ $rp($result['material_cost']) }}</strong></div>
            <div class="pg-row">
                <span>Biaya tenaga kerja<br><small class="muted">{{ $result['labor_hours'] }} jam &times; {{ $rp($result['hourly_rate']) }} per jam (tarif ditetapkan administrator)</small></span>
                <strong>{{ $rp($result['labor_cost']) }}</strong>
            </div>
            <div class="pg-row"><span>Estimasi harga jual</span><strong class="pg-total">{{ $rp($result['estimated_price']) }}</strong></div>

            @if ($c['rule'])
                <div class="pg-row"><span>Batas minimum harga adil</span><strong>{{ $rp($c['rule']['min_price']) }}</strong></div>
                <div class="pg-row"><span>Batas maksimum harga adil</span><strong>{{ $rp($c['rule']['max_price']) }}</strong></div>
            @endif

            @php
                $labels = ['within_range' => 'Sesuai harga adil', 'below_minimum' => 'Di bawah batas minimum',
                           'above_maximum' => 'Melebihi batas maksimum', 'no_rule' => 'Belum ada aturan harga', 'rule_inactive' => 'Aturan harga nonaktif'];
                $tone = ['within_range' => 'ok', 'below_minimum' => 'bad', 'above_maximum' => 'bad'][$c['status']] ?? 'info';
            @endphp
            <div class="pg-status {{ $tone }}">{{ $labels[$c['status']] }}<br><span style="font-weight:400;">{{ $c['message'] }}</span></div>

            <small class="muted">Rumus: estimasi harga = biaya bahan + (jam kerja &times; tarif upah per jam). Ini panduan, bukan harga final.</small>
        </section>
    @endif
@endsection
