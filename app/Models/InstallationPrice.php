<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class InstallationPrice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'installation_type',
        'hp_capacity',
        'price',
    ];

    public function installation(): BelongsTo
    {
        return $this->belongsTo(Installation::class);
    }
}
