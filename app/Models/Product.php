<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id',
        'region_id',
        'artisan_id',
        'name',
        'slug',
        'description',
        'artisan_name',
        'origin',
        'price',
        'fair_trade_percent',
        'stock',
        'image',
        'status',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if (empty($product->artisan_name) && $product->artisan_id) {
                $product->artisan_name = Artisan::find($product->artisan_id)?->name ?? 'Perajin Nusantara';
            }
            if (empty($product->origin) && $product->region_id) {
                $product->origin = Region::find($product->region_id)?->name ?? 'Nusantara';
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function artisan()
    {
        return $this->belongsTo(Artisan::class);
    }

    public function fairPrice()
    {
        return $this->hasOne(FairPrice::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Format harga: 1450000 -> "Rp 1.450.000"
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
