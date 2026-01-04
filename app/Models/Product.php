<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Brand;
use App\Models\Installation;
use Illuminate\Support\Str;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id', 'model_name', 'slug', 'hp_capacity', 
        'type', 'is_inverter', 'description', 'srp'
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product) {
            if ($product->isDirty('model_name')) {
                $product->slug = Str::slug($product->model_name);
            }
        });
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    // public function installations(): HasMany
    // {
    //     return $this->hasMany(Installation::class);
    // }
    
}
