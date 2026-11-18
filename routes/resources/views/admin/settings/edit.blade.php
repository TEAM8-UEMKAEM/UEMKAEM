@extends('layouts.admin')
@section('title', 'Tarif Upah per Jam')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Tarif Upah per Jam</h1>
            <p class="muted">Dipakai Panduan Harga untuk menghitung biaya tenaga kerja artisan. Belum ada nilai bawaan: Anda yang menetapkannya.</p>
        </div>
    </section>

    <form class="adm-form" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')

        <p>Tarif saat ini:
            <b>{{ $rate !== null ? 'Rp '.number_format((int) $rate, 0, ',', '.') . ' per jam' : 'belum ditetapkan' }}</b></p>

        <label>Tarif upah per jam (Rp) *
            <input type="number" name="labor_hourly_rate" min="1" step="1" required value="{{ old('labor_hourly_rate', $rate) }}">
            <small>Tetapkan sesuai kebijakan Anda (misalnya mengacu pada upah minimum daerah yang dipilih). Setiap perubahan tercatat di Riwayat Perubahan.</small>
        </label>

        <div class="adm-actions">
            <button type="submit" class="dg-btn dg-btn-primary">Simpan</button>
        </div>
    </form>
@endsection
