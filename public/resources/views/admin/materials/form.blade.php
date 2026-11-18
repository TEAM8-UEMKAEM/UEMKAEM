@extends('layouts.admin')
@section('title', $isEdit ? 'Ubah Material' : 'Tambah Material')

@section('content')
    <section class="adm-head"><h1>{{ $isEdit ? 'Ubah Material' : 'Tambah Material' }}</h1></section>

    <form class="adm-form" method="POST"
          action="{{ $isEdit ? route('admin.materials.update', $item->id) : route('admin.materials.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <label>Nama Material *
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="255">
        </label>
        <label>Satuan
            <input type="text" name="unit" value="{{ old('unit', $item->unit) }}" maxlength="30">
            <small>Contoh: kg, meter, buah.</small>
        </label>
        <label>Deskripsi
            <textarea name="description">{{ old('description', $item->description) }}</textarea>
        </label>

        <div class="adm-actions">
            <button type="submit" class="dg-btn dg-btn-primary">Simpan</button>
            <a href="{{ route('admin.materials.index') }}" class="dg-btn dg-btn-outline">Batal</a>
        </div>
    </form>
@endsection
