@extends('layouts.admin')
@section('title', $isEdit ? 'Ubah Aturan Harga' : 'Tambah Aturan Harga')

@section('content')
    <section class="adm-head"><h1>{{ $isEdit ? 'Ubah Aturan Harga' : 'Tambah Aturan Harga' }}</h1></section>

    <form class="adm-form" method="POST"
          action="{{ $isEdit ? route('admin.price-rules.update', $item->id) : route('admin.price-rules.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        @if ($isEdit)
            <p><b>{{ $item->category->name }}</b> &times; <b>{{ $item->material->name }}</b><br>
               <small>Kombinasi tidak dapat diubah. Untuk kombinasi lain, buat aturan baru.</small></p>
        @else
            <div class="adm-grid-2">
                <label>Kategori *
                    <select name="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $c)
                            <option value="{{ $c->id }}" @selected(old('category_id') == $c->id)>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <small>Hanya kategori aktif yang ditampilkan.</small>
                </label>
                <label>Material *
                    <select name="material_id" required>
                        <option value="">Pilih material</option>
                        @foreach ($materials as $m)
                            <option value="{{ $m->id }}" @selected(old('material_id') == $m->id)>{{ $m->name }}</option>
                        @endforeach
                    </select>
                    <small>Hanya material aktif yang ditampilkan.</small>
                </label>
            </div>
        @endif

        <div class="adm-grid-2">
            <label>Harga minimum (Rp) *
                <input type="number" name="min_price" min="0" step="1" required value="{{ old('min_price', $item->min_price) }}">
            </label>
            <label>Harga maksimum (Rp) *
                <input type="number" name="max_price" min="0" step="1" required value="{{ old('max_price', $item->max_price) }}">
            </label>
        </div>

        <div class="adm-actions">
            <button type="submit" class="dg-btn dg-btn-primary">Simpan</button>
            <a href="{{ route('admin.price-rules.index') }}" class="dg-btn dg-btn-outline">Batal</a>
        </div>
    </form>
@endsection
