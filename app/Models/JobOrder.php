<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'jo_number',
        'client_id',
        'receive_date',
        'project_cost',
    ];

    /**
     * Auto-generate JO Number on Creation
     */
    protected static function booted()
    {
        static::creating(function ($jobOrder) {
            // Get the last record to determine the next number
            $lastOrder = self::orderBy('id', 'desc')->first();

            if (!$lastOrder) {
                $nextNumber = 1;
            } else {
                // Extract the numeric part from 'OB-JO-001'
                $lastNumber = (int) str_replace('OB-JO-', '', $lastOrder->jo_number);
                $nextNumber = $lastNumber + 1;
            }

            // Pad with zeros to maintain the 001 format
            $jobOrder->jo_number = 'OB-JO-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }

    /**
     * Relationship: A Job Order belongs to a Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relationship: A Job Order has many Installations
     */
    // public function installation(): HasMany
    // {
    //     return $this->hasMany(Installation::class);
    // }

}