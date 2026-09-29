<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Models;

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Traits\HasPermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property array<int, mixed>|null $permissions
 */
class Role extends Model implements AuthControllable, HasEditablePermissions
{
    use HasPermissions;

    public const string ADMINISTRATOR = 'administrator';

    protected $fillable = [
        'code',
        'name',
        'permissions',
    ];

    public function getPermissions(): Collection
    {
        return new Collection($this->permissions ?? [])
            ->filter(fn (mixed $permission): bool => is_string($permission))
            ->values();
    }

    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = $permissions->values()->all();
        $this->save();
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
        ];
    }
}
