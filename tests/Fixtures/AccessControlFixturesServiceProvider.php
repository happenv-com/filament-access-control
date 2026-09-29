<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures;

use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\LaravelAccessControl\PermissionRegistry;
use Happenv\LaravelAccessControl\VoterRegistry;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\ServiceProvider;

/**
 * The catalogue the tests run against. Registered before anything boots, because the library
 * defines one gate per registered permission while it boots.
 */
class AccessControlFixturesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->make(PermissionRegistry::class)->register([
            ProductPermission::class,
            CategoryPermission::class,
            RolePermission::class,
            UserPermission::class,
        ]);

        $this->loadViewsFrom(__DIR__ . '/views', 'filament-access-control-tests');

        $this->app->make(VoterRegistry::class)->register(
            RolePermission::Delete,
            fn (Authenticatable $user, mixed $role = null): Response => $role instanceof Role && $role->users()->exists()
                ? Response::deny('This role is assigned to users and cannot be deleted.')
                : Response::allow(),
        );
    }
}
