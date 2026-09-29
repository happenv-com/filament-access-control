<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures;

use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
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
use SensitiveParameter;

/**
 * @property int $id
 * @property array<int, mixed>|null $permissions
 * @property string|null $app_authentication_secret
 */
class User extends Authenticatable implements AuthControllable, FilamentUser, HasAppAuthentication, HasEditablePermissions
{
    use HasRolesAndPermissions;
    use Notifiable;

    public static bool $canAccessPanel = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'permissions',
        'app_authentication_secret',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return static::$canAccessPanel;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(#[SensitiveParameter] ?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
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
