<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'company_name',
        'name',
        'contact_number',
        'address',
        'email_address',
        'office_address',
        'owner',
        'office_contact_number',
        'office_email_address'
    ];

    public static function generateNextSupplierId(): string
    {
        $lastSupplier = self::withTrashed()->latest('id')->first();

        if (! $lastSupplier) {
            return 'OB-S-001';
        }

        $lastNumber = (int) str_replace('OB-S-', '', $lastSupplier->supplier_id);

        $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);

        return "OB-S-{$nextNumber}";
    }
}
