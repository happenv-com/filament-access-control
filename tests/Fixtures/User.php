<?php

declare(strict_types=1);

namespace VendorName\Skeleton\Tests\Fixtures;

// @filament-start
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
// @filament-end
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// @filament-start
class User extends Authenticatable implements FilamentUser
// @filament-end
// @laravel-start
class User extends Authenticatable
// @laravel-end
{
    use Notifiable;

    // @filament-start
    public static bool $canAccessPanel = true;

    // @filament-end
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    // @filament-start

    public function canAccessPanel(Panel $panel): bool
    {
        return static::$canAccessPanel;
    }
    // @filament-end

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
