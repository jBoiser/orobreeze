<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InstallationItem extends Model
{
    protected $fillable = [
        'installation_id',
        'brand_id',
        'product_id',
        'model_name',
        'unit_type',
        'refrigerant_type',
        'hp_capacity',
        'outdoor_model',
        'srp',
        'quantity',
        'discount',
        'price'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

     public function installation()
    {
        return $this->belongsTo(installation::class);
    }
}
