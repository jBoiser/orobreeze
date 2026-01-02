<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser; // Add this
use Filament\Panel; // Add this
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser // Add 'implements FilamentUser'
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Required for Filament access in production
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow all users to access for now; you can restrict this to specific emails later
        return (bool) $this->is_admin;
    }
}