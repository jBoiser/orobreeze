<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_id',
        'name',
        'address',
        'email',
        'phone_number',
        'company'
    ];

    public static function generateNextClientId(): string
    {
        $lastClient = self::withTrashed()->latest('id')->first();

        if (! $lastClient) {
            return 'OB-C-001';
        }

        $lastNumber = (int) str_replace('OB-C-', '', $lastClient->client_id);

        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "OB-C-{$nextNumber}";
    }
}
