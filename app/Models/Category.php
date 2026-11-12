<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name','slug','tagline','description','image','badge','group','regions','tags','artisans_count'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
