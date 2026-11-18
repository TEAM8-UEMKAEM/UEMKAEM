@extends('layouts.admin')
@section('title', $isEdit ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
    <section class="adm-head"><h1>{{ $isEdit ? 'Ubah Kategori' : 'Tambah Kategori' }}</h1></section>

    <form class="adm-form" method="POST" enctype="multipart/form-data"
          action="{{ $isEdit ? route('admin.categories.update', $item->id) : route('admin.categories.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <label>Nama Kategori *
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="255">
        </label>

        @if ($isEdit)
            <p><small>Slug: <b>{{ $item->slug }}</b> (tidak dapat diubah karena dipakai pada tautan filter produk)</small></p>
        @endif

        <label>Tagline
            <input type="text" name="tagline" value="{{ old('tagline', $item->tagline) }}" maxlength="255">
        </label>

        <label>Deskripsi
            <textarea name="description">{{ old('description', $item->description) }}</textarea>
        </label>

        <div class="adm-grid-2">
            <label>Grup
                <input type="text" name="group" value="{{ old('group', $item->group) }}" maxlength="255">
                <small>Dipakai sebagai filter di halaman kategori.</small>
            </label>
            <label>Lencana (badge)
                <input type="text" name="badge" value="{{ old('badge', $item->badge) }}" maxlength="255">
            </label>
        </div>

        <div class="adm-grid-2">
            <label>Sentra / Daerah
                <input type="text" name="regions" value="{{ old('regions', $item->regions) }}" maxlength="255">
            </label>
            <label>Jumlah Artisan
                <input type="number" name="artisans_count" min="0" value="{{ old('artisans_count', $item->artisans_count) }}">
            </label>
        </div>

        <label>Tag
            <input type="text" name="tags" value="{{ old('tags', $item->tags) }}">
            <small>Pisahkan dengan koma, contoh: Indigo, Ikat, Sumba</small>
        </label>

        <label>Gambar
            <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
            <small>JPG / PNG / WEBP, maks. 2 MB.{{ $isEdit ? ' Kosongkan jika tidak ingin mengganti gambar.' : '' }}</small>
        </label>

        <div class="adm-actions">
            <button type="submit" class="dg-btn dg-btn-primary">Simpan</button>
            <a href="{{ route('admin.categories.index') }}" class="dg-btn dg-btn-outline">Batal</a>
        </div>
    </form>
@endsection
