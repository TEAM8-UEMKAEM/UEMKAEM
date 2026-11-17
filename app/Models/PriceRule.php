<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PriceRule extends Model
{
    protected $fillable = ['category_id', 'material_id', 'min_price', 'max_price', 'is_active'];

    protected $casts = [
        'min_price' => 'integer',
        'max_price' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Nama yang mudah dibaca, dipakai di konfirmasi dan riwayat perubahan
    public function getNameAttribute(): string
    {
        return ($this->category?->name ?? '?') . ' × ' . ($this->material?->name ?? '?');
    }
}
