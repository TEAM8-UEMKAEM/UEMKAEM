<?php

namespace App\Models;

use Database\Factories\ArtisanFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artisan extends Model
{
    /** @use HasFactory<ArtisanFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'region_id',
        'name',
        'slug',
        'workshop_name',
        'specialty',
        'story',
        'location',
        'photo',
        'is_verified',
        'verification_status',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
