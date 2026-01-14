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
        'downpayment',
        'balance', // Add this
        'remarks', // Add this
        'payment_status',
        'task_status',
    ];

    protected static function booted(): void
    {
        static::saving(function ($model) {
            // Automatically calculate balance before database insertion/update
            $model->balance = (float) $model->project_cost - (float) $model->downpayment;
        });
    }

    /**
     * Auto-generate JO Number on Creation
     */

    public static function generateNextJobOrder(): string
    {
        $year = date('y'); // Returns "26"

        // Find the last record that ends with the current year suffix
        $lastJobOrder = self::withTrashed()
            ->where('jo_number', 'like', "%-{$year}")
            ->latest('id')
            ->first();

        if (! $lastJobOrder) {
            // If no record exists for this year, start at 0001
            return "JO-0001-{$year}";
        }

        // Split the string by '-' (e.g., "JO-0001-26" becomes ["JO", "0001", "26"])
        $segments = explode('-', $lastJobOrder->jo_number);

        // The middle segment (index 1) is our incrementing number
        $lastNumber = isset($segments[1]) ? (int) $segments[1] : 0;

        // Pad to 4 digits as requested
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "JO-{$nextNumber}-{$year}";
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
