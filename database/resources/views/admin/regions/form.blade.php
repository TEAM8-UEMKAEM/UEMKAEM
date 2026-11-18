@extends('layouts.admin')
@section('title', $isEdit ? 'Ubah Wilayah' : 'Tambah Wilayah')

@section('content')
    <section class="adm-head"><h1>{{ $isEdit ? 'Ubah Wilayah' : 'Tambah Wilayah' }}</h1></section>

    <form class="adm-form" method="POST"
          action="{{ $isEdit ? route('admin.regions.update', $item->id) : route('admin.regions.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <label>Nama Wilayah *
            <input type="text" name="name" value="{{ old('name', $item->name) }}" required maxlength="255">
            <small>Contoh: Sumba Timur, Kasongan Bantul.</small>
        </label>
        <label>Provinsi
            <input type="text" name="province" value="{{ old('province', $item->province) }}" maxlength="255">
        </label>

        <div class="adm-actions">
            <button type="submit" class="dg-btn dg-btn-primary">Simpan</button>
            <a href="{{ route('admin.regions.index') }}" class="dg-btn dg-btn-outline">Batal</a>
        </div>
    </form>
@endsection
