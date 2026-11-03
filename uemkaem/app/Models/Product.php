<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'artisan_name', 'origin',
        'price', 'fair_trade_percent', 'stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Format harga: 1450000 -> "Rp 1.450.000"
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
