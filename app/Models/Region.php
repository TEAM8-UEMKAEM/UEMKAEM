<?php

namespace App\Models;

use Database\Factories\RegionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    /** @use HasFactory<RegionFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'province',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function artisans()
    {
        return $this->hasMany(Artisan::class);
    }
}
