<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id', 'model', 'unit_capacity', 
        'unit_type', 'srp', 'discount', 'price'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    
}
