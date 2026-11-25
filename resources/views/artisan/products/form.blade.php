@extends('layouts.artisan')
@section('title', $isEdit ? 'Ubah Produk' : 'Tambah Produk')

@section('content')
    <section class="adm-head">
        <div>
            <h1>{{ $isEdit ? 'Ubah Produk: ' . $product->name : 'Tambah Produk Baru' }}</h1>
            <p class="muted">Lengkapi detail karya, foto produk, stok tersedia, dan pastikan harga sesuai panduan harga adil.</p>
        </div>
        <a href="{{ route('artisan.products.index') }}" class="dg-btn dg-btn-outline">Kembali</a>
    </section>

    <form class="adm-form" method="POST" enctype="multipart/form-data"
          action="{{ $isEdit ? route('artisan.products.update', $product->id) : route('artisan.products.store') }}">
        @csrf
        @if ($isEdit) @method('PUT') @endif

        <label>Nama Produk *
            <input type="text" name="name" required maxlength="255"
                   value="{{ old('name', $product->name) }}"
                   placeholder="Contoh: Kain Tenun Ikat Motif Sumba">
        </label>

        <label>Deskripsi Produk *
            <textarea name="description" rows="4" required maxlength="5000"
                      placeholder="Ceritakan teknik pembuatan, motif, ukuran, dan keunikan karya Anda...">{{ old('description', $product->description) }}</textarea>
        </label>

        <div class="adm-grid-2">
            <label>Kategori Kriya *
                <select name="category_id" id="category_id" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" @selected((int) old('category_id', $product->category_id) === $c->id)>
                            {{ $c->name }}@unless($c->is_active) (Nonaktif)@endunless
                        </option>
                    @endforeach
                </select>
            </label>

            <label>Material Utama *
                <select name="material_id" id="material_id" required>
                    <option value="">Pilih material</option>
                    @foreach ($materials as $m)
                        <option value="{{ $m->id }}" @selected((int) old('material_id', $product->material_id) === $m->id)>
                            {{ $m->name }}@unless($m->is_active) (Nonaktif)@endunless
                        </option>
                    @endforeach
                </select>
            </label>
        </div>

        <div class="adm-grid-2">
            <label>Wilayah Asal
                <select name="region_id">
                    <option value="">Pilih wilayah (opsional)</option>
                    @foreach ($regions as $r)
                        <option value="{{ $r->id }}" @selected((int) old('region_id', $product->region_id) === $r->id)>
                            {{ $r->name }}@if($r->province), {{ $r->province }}@endif @unless($r->is_active)(Nonaktif)@endunless
                        </option>
                    @endforeach
                </select>
            </label>

            <label>Hak Perajin (%)
                <input type="number" name="fair_trade_percent" min="0" max="100" step="1"
                       value="{{ old('fair_trade_percent', $product->fair_trade_percent ?? 90) }}">
            </label>
        </div>

        <div class="adm-grid-2">
            <label>Harga Jual (Rp) *
                <input type="number" name="price" id="price" min="0" step="1" required
                       value="{{ old('price', $product->price) }}"
                       placeholder="Contoh: 250000">
            </label>

            <label>Jumlah Stok (Pcs) *
                <input type="number" name="stock" min="0" max="65535" step="1" required
                       value="{{ old('stock', $product->stock ?? 1) }}">
            </label>
        </div>

        {{-- Peringatan Harga Adil (PBI #17) --}}
        <div id="fair-price-box"
             class="art-price-box {{ ($priceCheck && $priceCheck['valid'] === true) ? 'is-valid' : (($priceCheck && $priceCheck['valid'] === false) ? 'is-warning' : '') }}"
             role="status" aria-live="polite">
            <strong id="fair-price-title">
                @if ($priceCheck && $priceCheck['valid'] === false)
                    Peringatan Harga Adil
                @elseif ($priceCheck && $priceCheck['valid'] === true)
                    Harga Sesuai Panduan Harga Adil
                @else
                    Pemeriksaan Harga Adil
                @endif
            </strong>
            <div id="fair-price-message">
                {{ $priceCheck['message'] ?? 'Pilih kategori, material, dan masukkan harga untuk memeriksa rentang harga adil.' }}
            </div>
            <div style="margin-top:6px;">
                <a href="{{ route('price-guide') }}" target="_blank" rel="noopener" style="font-weight:600; color:#003631;">
                    Buka Panduan &amp; Kalkulator Harga Artisan &rarr;
                </a>
            </div>
        </div>

        {{-- Foto Produk (PBI #16) --}}
        <div style="margin-top:16px;">
            <label>Unggah Foto Produk (Maks. 5 foto, JPG/PNG/WEBP, maks. 2MB/foto)
                <input type="file" name="photos[]" id="photos-input" accept="image/jpeg,image/png,image/webp" multiple>
            </label>
            <div id="new-photos-preview" class="art-photo-grid" hidden></div>

            @if ($isEdit && $product->images->isNotEmpty())
                <div style="margin-top:14px;">
                    <strong>Foto Produk Saat Ini</strong>
                    <div class="art-photo-grid">
                        @foreach ($product->images as $img)
                            <div class="art-photo-card">
                                <img src="{{ $img->path }}" alt="{{ $product->name }}">
                                <label style="display:flex; align-items:center; gap:6px; margin:0; font-weight:600;">
                                    <input type="radio" name="primary_image_id" value="{{ $img->id }}" @checked($img->is_primary)>
                                    Foto Utama
                                </label>
                                <label style="display:flex; align-items:center; gap:6px; margin:0; color:#b91c1c;">
                                    <input type="checkbox" name="remove_images[]" value="{{ $img->id }}">
                                    Hapus foto
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        @unless ($isEdit)
            <label style="margin-top:16px;">Status Awal
                <select name="status">
                    <option value="draft" @selected(old('status', 'draft') === 'draft')>Simpan sebagai Draf</option>
                    <option value="published" @selected(old('status') === 'published')>Langsung Publikasikan</option>
                </select>
            </label>
        @endunless

        <div class="adm-actions" style="margin-top:20px;">
            <button type="submit" class="dg-btn dg-btn-primary">{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Produk' }}</button>
            <a href="{{ route('artisan.products.index') }}" class="dg-btn dg-btn-outline">Batal</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
(function () {
    var categoryEl = document.getElementById('category_id');
    var materialEl = document.getElementById('material_id');
    var priceEl = document.getElementById('price');
    var box = document.getElementById('fair-price-box');
    var titleEl = document.getElementById('fair-price-title');
    var msgEl = document.getElementById('fair-price-message');
    var checkUrl = @json(route('api.price-check'));
    var timer = null;

    function checkPrice() {
        var cat = categoryEl ? categoryEl.value : '';
        var mat = materialEl ? materialEl.value : '';
        var price = priceEl ? priceEl.value : '';

        if (!cat || !mat || price === '') {
            box.className = 'art-price-box';
            titleEl.textContent = 'Pemeriksaan Harga Adil';
            msgEl.textContent = 'Pilih kategori, material, dan masukkan harga untuk memeriksa rentang harga adil.';
            return;
        }

        var url = checkUrl + '?category_id=' + encodeURIComponent(cat)
            + '&material_id=' + encodeURIComponent(mat)
            + '&price=' + encodeURIComponent(price);

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.valid === false) {
                    box.className = 'art-price-box is-warning';
                    titleEl.textContent = 'Peringatan Harga Adil';
                } else if (data.valid === true) {
                    box.className = 'art-price-box is-valid';
                    titleEl.textContent = 'Harga Sesuai Panduan Harga Adil';
                } else {
                    box.className = 'art-price-box';
                    titleEl.textContent = 'Informasi Harga Adil';
                }
                msgEl.textContent = data.message || '';
            })
            .catch(function () {
                // Abaikan error jaringan sementara; validasi server tetap berjalan saat simpan
            });
    }

    function scheduleCheck() {
        clearTimeout(timer);
        timer = setTimeout(checkPrice, 250);
    }

    if (categoryEl) categoryEl.addEventListener('change', scheduleCheck);
    if (materialEl) materialEl.addEventListener('change', scheduleCheck);
    if (priceEl) priceEl.addEventListener('input', scheduleCheck);

    var photosInput = document.getElementById('photos-input');
    var previewWrap = document.getElementById('new-photos-preview');
    if (photosInput && previewWrap) {
        photosInput.addEventListener('change', function () {
            previewWrap.innerHTML = '';
            var files = Array.prototype.slice.call(photosInput.files || []);
            if (!files.length) {
                previewWrap.hidden = true;
                return;
            }
            previewWrap.hidden = false;
            files.forEach(function (file) {
                var card = document.createElement('div');
                card.className = 'art-photo-card';
                var img = document.createElement('img');
                img.alt = file.name;
                img.src = URL.createObjectURL(file);
                var name = document.createElement('small');
                name.textContent = file.name;
                card.appendChild(img);
                card.appendChild(name);
                previewWrap.appendChild(card);
            });
        });
    }
})();
</script>
@endpush
