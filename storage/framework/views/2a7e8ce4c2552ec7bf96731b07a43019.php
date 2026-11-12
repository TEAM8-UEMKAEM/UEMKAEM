<?php
    $ft        = (int) ($product->fair_trade_percent ?? 90);
    $material  = (int) ($product->material_percent ?? 0);
    $logistic  = max(0, 100 - $ft - $material);
    $artisan   = $product->artisan_name ?? null;
    $workshop  = $product->workshop_name ?? null;
    $stock     = $product->stock ?? null;
    $hasImage  = !empty($product->image) && \Illuminate\Support\Facades\Route::has('products.image');
    $hasShow   = \Illuminate\Support\Facades\Route::has('products.show');
?>

<article class="card product-card">
    <div class="product-image">
        <?php if($hasImage): ?>
            <img src="<?php echo e(route('products.image', $product->id)); ?>" alt="<?php echo e($product->name); ?>">
        <?php endif; ?>
        <?php if(!empty($product->badge)): ?>
            <span class="tag-left"><?php echo e($product->badge); ?></span>
        <?php endif; ?>
        <span class="badge badge-green">Fair-Trade <?php echo e($ft); ?>%</span>
        <?php if(!empty($product->origin)): ?>
            <span class="origin"><?php echo e($product->origin); ?></span>
        <?php endif; ?>
    </div>

    <div class="product-body">
        <?php if($artisan || $workshop): ?>
            <small class="artisan"><?php echo e($artisan); ?><?php if($artisan && $workshop): ?> &bull; <?php endif; ?><?php echo e($workshop); ?></small>
        <?php endif; ?>
        <h3><?php echo e($product->name); ?></h3>

        <div class="alloc">
            <div class="alloc-head">
                <small>Alokasi Harga Transparan:</small>
                <b><?php echo e($ft); ?>% Hak Perajin</b>
            </div>
            <div class="bar">
                <i class="seg-green" style="width: <?php echo e($ft); ?>%"></i>
                <?php if($material): ?> <i class="seg-orange" style="width: <?php echo e($material); ?>%"></i> <?php endif; ?>
                <?php if($logistic): ?> <i class="seg-gray" style="width: <?php echo e($logistic); ?>%"></i> <?php endif; ?>
            </div>
            <small class="alloc-note">Rp <?php echo e(number_format($product->price * $ft / 100, 0, ',', '.')); ?> ke <?php echo e($artisan ?? 'perajin'); ?></small>
        </div>

        <div class="price-row">
            <div>
                <small class="muted">Harga Fair-Trade:</small>
                <strong>Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></strong>
            </div>
            <?php if($stock !== null): ?>
                <span class="stock <?php echo e($stock <= 3 ? 'low' : ''); ?>">Tersedia <?php echo e($stock); ?> Pcs</span>
            <?php endif; ?>
        </div>

        <div class="actions">
            <a href="<?php echo e($hasShow ? route('products.show', $product) : '#'); ?>" class="btn btn-outline">Kisah Artisan</a>
            <a href="<?php echo e($hasShow ? route('products.show', $product) : '#'); ?>" class="btn btn-primary">Beli Langsung</a>
        </div>
    </div>
</article><?php /**PATH C:\Users\user\Documents\GitHub\UEMKAEM\resources\views/partials/product-card.blade.php ENDPATH**/ ?>