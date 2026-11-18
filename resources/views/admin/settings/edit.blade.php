@extends('layouts.admin')
@section('title', 'Pengaturan Upah')

@section('content')
    <section class="adm-head">
        <div>
            <h1>Tarif Upah per Jam</h1>
            <p class="muted">Dipakai Panduan Harga Artisan untuk menghitung biaya tenaga kerja. Nilai ini sepenuhnya ditetapkan administrator dan tidak ada nilai bawaan.</p>
        </div>
    </section>

    @if ($rate === null)
        <div class="adm-alert err">Tarif belum ditetapkan. Panduan Harga belum dapat menghitung sampai tarif diisi.</div>
    @endif

    <form class="adm-form" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <label>Tarif upah per jam (Rp) *
            <input type="number" name="labor_hourly_rate" min="1" step="1" required value="{{ old('labor_hourly_rate', $rate) }}">
            <small>Tentukan berdasarkan kebijakan Anda, misalnya mengacu pada upah minimum daerah. Setiap perubahan tercatat di Riwayat Perubahan.</small>
        </label>
        <div class="adm-actions"><button type="submit" class="dg-btn dg-btn-primary">Simpan</button></div>
    </form>
@endsection
