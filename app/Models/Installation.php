<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installation extends Model
{
    use softDeletes;

    protected $fillable = [
        'job_order_id',
        'total_price',
        'description',
        'start_date',
        'end_date',
        'service_by',
        'status',
        'remarks',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function jobOrder(): BelongsTo
    {
        return $this->belongsTo(JobOrder::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(brand::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(client::class);
    }

        public function items()
    {
        return $this->hasMany(InstallationItem::class);
    }

    
}
