<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property array<int, mixed>|null $permissions
 */
class User extends Authenticatable implements AuthControllable, FilamentUser, HasEditablePermissions
{
    use HasRolesAndPermissions;
    use Notifiable;

    public static bool $canAccessPanel = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return static::$canAccessPanel;
    }

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @return iterable<int, Role>
     */
    public function getRoles(): iterable
    {
        return $this->roles;
    }

    public function getPermissions(): Collection
    {
        return (new Collection($this->permissions ?? []))
            ->filter(fn (mixed $permission): bool => is_string($permission))
            ->values();
    }

    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = $permissions->values()->all();
        $this->save();
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
        ];
    }
}
