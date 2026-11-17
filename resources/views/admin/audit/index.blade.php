@extends('layouts.admin')
@section('title', 'Riwayat Perubahan')

@section('content')
    @php
        $typeLabels = ['Category' => 'Kategori', 'Material' => 'Material', 'Region' => 'Wilayah', 'PriceRule' => 'Aturan Harga', 'Setting' => 'Pengaturan'];
        $actionLabels = ['created' => 'Dibuat', 'updated' => 'Diubah', 'activated' => 'Diaktifkan', 'deactivated' => 'Dinonaktifkan'];
        $fieldLabels = ['name' => 'Nama', 'slug' => 'Slug', 'is_active' => 'Status', 'tagline' => 'Tagline', 'description' => 'Deskripsi',
                        'badge' => 'Lencana', 'group' => 'Grup', 'regions' => 'Sentra', 'tags' => 'Tag', 'artisans_count' => 'Jumlah artisan',
                        'unit' => 'Satuan', 'category_id' => 'ID kategori', 'material_id' => 'ID material', 'labor_hourly_rate' => 'Tarif upah per jam', 'province' => 'Provinsi', 'min_price' => 'Harga minimum', 'max_price' => 'Harga maksimum'];
        $fmt = function ($k, $v) {
            if ($k === 'is_active') { return $v ? 'Aktif' : 'Nonaktif'; }
            if ($v === null || $v === '') { return '—'; }
            if (in_array($k, ['min_price', 'max_price', 'labor_hourly_rate'], true)) { return 'Rp ' . number_format((int) $v, 0, ',', '.'); }
            return is_array($v) ? json_encode($v) : $v;
        };
    @endphp

    <section class="adm-head">
        <div>
            <h1>Riwayat Perubahan</h1>
            <p class="muted">Catatan siapa mengubah apa dan kapan. Riwayat ini hanya bisa dibaca dan tidak dapat diubah atau dihapus.</p>
        </div>
    </section>

    <form method="GET" class="adm-filter">
        <select name="type" onchange="this.form.submit()">
            <option value="">Semua jenis data</option>
            @foreach ($typeLabels as $key => $text)
                <option value="{{ $key }}" @selected(request('type') === $key)>{{ $text }}</option>
            @endforeach
        </select>
        <select name="action" onchange="this.form.submit()">
            <option value="">Semua tindakan</option>
            @foreach ($actionLabels as $key => $text)
                <option value="{{ $key }}" @selected(request('action') === $key)>{{ $text }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.audit-logs.index') }}" class="dg-btn dg-btn-outline dg-btn-sm">Reset</a>
    </form>

    <div class="adm-table-wrap">
        <table class="adm-table">
            <thead><tr><th>WAKTU</th><th>ADMIN</th><th>DATA</th><th>TINDAKAN</th><th>PERUBAHAN</th></tr></thead>
            <tbody>
            @forelse ($logs as $log)
                <tr>
                    <td style="white-space:nowrap;">{{ $log->created_at->format('d M Y H:i') }}</td>
                    <td>{{ $log->user_name }}</td>
                    <td>{{ $typeLabels[$log->auditable_type] ?? $log->auditable_type }}@if ($log->auditable_id) #{{ $log->auditable_id }}@endif</td>
                    <td>{{ $actionLabels[$log->action] ?? $log->action }}</td>
                    <td class="adm-change">
                        @foreach (($log->new_values ?? []) as $k => $new)
                            @if ($k === 'name' && ! array_key_exists('name', $log->old_values ?? []) && $log->action !== 'created')
                                <div><b>{{ $new }}</b></div>
                                @continue
                            @endif
                            <div>
                                {{ $fieldLabels[$k] ?? $k }}:
                                @if ($log->old_values !== null && array_key_exists($k, $log->old_values))
                                    <s>{{ $fmt($k, $log->old_values[$k]) }}</s> &rarr;
                                @endif
                                {{ $fmt($k, $new) }}
                            </div>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada riwayat.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination-wrap">{{ $logs->links('partials.pagination') }}</div>
@endsection
