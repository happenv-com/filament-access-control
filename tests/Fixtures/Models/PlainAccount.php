<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Models;

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\LaravelAccessControl\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

/**
 * An account on the `users` table that exposes `getRoles()` — as {@see HasRoles}
 * does — without using any of the library's traits: {@see RecordPermissions::roleStored()}
 * falls back to reading its roles' own stored grants instead of the library's `AccessControl::roleGrantsOf()`.
 *
 * @property int $id
 * @property array<int, mixed>|null $permissions
 */
class PlainAccount extends Authenticatable implements HasEditablePermissions
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
    ];

    /**
     * @return BelongsToMany<Role, $this>
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
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
            'permissions' => 'array',
        ];
    }
}
