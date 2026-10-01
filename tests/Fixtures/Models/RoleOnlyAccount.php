<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Models;

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * An account on the `users` table that holds permissions through its roles only — no direct grants,
 * so not {@see HasEditablePermissions}: what an application granting through roles alone has.
 *
 * @property int $id
 */
class RoleOnlyAccount extends Authenticatable implements AuthControllable
{
    use HasRoles;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
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
}
