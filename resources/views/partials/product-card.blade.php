@php
    $ft        = (int) ($product->fair_trade_percent ?? 90);
    $material  = (int) ($product->material_percent ?? 0);
    $logistic  = max(0, 100 - $ft - $material);
    $artisan   = $product->artisan_name ?? null;
    $workshop  = $product->workshop_name ?? null;
    $stock     = $product->stock ?? null;
    $hasImage  = !empty($product->image) && \Illuminate\Support\Facades\Route::has('products.image');
    $hasShow   = \Illuminate\Support\Facades\Route::has('products.show');
@endphp

<article class="card product-card">
    <div class="product-image">
        @if ($hasImage)
            <img src="{{ route('products.image', $product->id) }}" alt="{{ $product->name }}">
        @endif
        @if (!empty($product->badge))
            <span class="tag-left">{{ $product->badge }}</span>
        @endif
        <span class="badge badge-green">Fair-Trade {{ $ft }}%</span>
        @if (!empty($product->origin))
            <span class="origin">{{ $product->origin }}</span>
        @endif
    </div>

    <div class="product-body">
        @if ($artisan || $workshop)
            <small class="artisan">{{ $artisan }}@if ($artisan && $workshop) &bull; @endif{{ $workshop }}</small>
        @endif
        <h3>{{ $product->name }}</h3>

        <div class="alloc">
            <div class="alloc-head">
                <small>Alokasi Harga Transparan:</small>
                <b>{{ $ft }}% Hak Perajin</b>
            </div>
            <div class="bar">
                <i class="seg-green" style="width: {{ $ft }}%"></i>
                @if ($material) <i class="seg-orange" style="width: {{ $material }}%"></i> @endif
                @if ($logistic) <i class="seg-gray" style="width: {{ $logistic }}%"></i> @endif
            </div>
            <small class="alloc-note">Rp {{ number_format($product->price * $ft / 100, 0, ',', '.') }} ke {{ $artisan ?? 'perajin' }}</small>
        </div>

        <div class="price-row">
            <div>
                <small class="muted">Harga Fair-Trade:</small>
                <strong>Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
            </div>
            @if ($stock !== null)
                <span class="stock {{ $stock <= 3 ? 'low' : '' }}">Tersedia {{ $stock }} Pcs</span>
            @endif
        </div>

        <div class="actions">
            <a href="{{ $hasShow ? route('products.show', $product) : '#' }}" class="btn btn-outline">Kisah Artisan</a>
            <a href="{{ $hasShow ? route('products.show', $product) : '#' }}" class="btn btn-primary">Beli Langsung</a>
        </div>
    </div>
</article>