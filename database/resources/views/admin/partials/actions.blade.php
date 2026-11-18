{{-- Tombol Lihat / Ubah / Aktifkan-Nonaktifkan. Butuh: $base (mis. admin.categories), $item, $label --}}
<div class="adm-actions">
    <a href="{{ route($base.'.show', $item->id) }}" class="dg-btn dg-btn-outline dg-btn-sm">Lihat</a>
    <a href="{{ route($base.'.edit', $item->id) }}" class="dg-btn dg-btn-outline dg-btn-sm">Ubah</a>
    <form method="POST" action="{{ route($base.'.toggle', $item->id) }}"
          data-confirm="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }} {{ $label }} &quot;{{ $item->name }}&quot;?{{ $item->is_active ? ' Data lama tetap tersimpan dan produk yang ada tidak terhapus.' : '' }}">
        @csrf
        @method('PATCH')
        <button type="submit" class="dg-btn dg-btn-sm {{ $item->is_active ? 'dg-btn-soft' : 'dg-btn-primary' }}">
            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
        </button>
    </form>
</div>
