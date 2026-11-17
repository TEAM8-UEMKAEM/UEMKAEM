<?php

namespace App\Models;

use Database\Factories\FairPriceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FairPrice extends Model
{
    /** @use HasFactory<FairPriceFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id',
        'category_id',
        'region_id',
        'min_price',
        'max_price',
        'recommended_price',
        'material_cost',
        'labor_cost',
        'artisan_share_percent',
        'notes',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
