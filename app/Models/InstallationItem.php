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
        'price',
        'description',
        'is_inverter',
    ];

    protected $casts = [
        'is_inverter' => 'integer', // or 'boolean'
    ];

    protected static function booted(): void
    {
        static::saving(function ($record) {
            // Check if product_id was provided or changed
            if ($record->product_id) {
                $product = Product::find($record->product_id);

                if ($product) {
                    // Automatically set the model_name from the related Product
                    $record->model_name = $product->model_name;
                }
            }
        });
    }

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
