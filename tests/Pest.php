<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Happenv\FilamentAccessControl\Tests\TestCase;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;
use Happenv\LaravelAccessControl\PermissionRegistry;
use Illuminate\Database\Eloquent\Model;

use function Pest\Laravel\actingAs;

pest()->extend(TestCase::class)->in('Unit', 'Feature');

/**
 * @param  list<string>  $permissions
 */
function createUser(string $email = 'jane@example.com', array $permissions = []): User
{
    return User::query()->create([
        'name' => 'Jane',
        'email' => $email,
        'password' => 'password',
        'permissions' => $permissions,
    ]);
}

/**
 * @param  list<string>  $permissions
 */
function createRole(string $code, array $permissions = [], ?string $name = null): Role
{
    return Role::query()->create([
        'code' => $code,
        'name' => $name ?? ucfirst($code),
        'permissions' => $permissions,
    ]);
}

/**
 * Signs in, on the admin panel, somebody allowed to manage roles and users.
 *
 * @param  list<string>|null  $permissions  what the operator holds; everything role- and user-related by default
 */
function signInOperator(?array $permissions = null): User
{
    Filament::setCurrentPanel('admin');

    $operator = createUser(uniqid('operator-') . '@example.com', $permissions ?? [
        ...array_column(RolePermission::cases(), 'value'),
        UserPermission::Update->value,
    ]);

    actingAs($operator);

    return $operator;
}

function holderKey(Model $holder): string
{
    return (string) $holder->getKey();
}

function plugin(): FilamentAccessControlPlugin
{
    Filament::setCurrentPanel('admin');

    return FilamentAccessControlPlugin::get();
}

/**
 * Register permission enums for one test — the fixtures with rules and conditions stay out of the
 * catalogue every other test draws.
 *
 * @param  class-string  ...$enums
 */
function registerPermissions(string ...$enums): void
{
    resolve(PermissionRegistry::class)->register($enums);
}

/**
 * A resolution built by hand — "stored and in effect" unless told otherwise.
 *
 * @param  list<PermissionDefinition>  $grantedBy
 * @param  list<PermissionDefinition>  $missing
 * @param  list<PermissionDefinition>  $conflicting
 * @param  list<PermissionCondition>  $unmetConditions
 */
function resolutionOf(
    bool $stored = true,
    bool $granted = true,
    bool $allowed = true,
    array $grantedBy = [],
    array $missing = [],
    array $conflicting = [],
    bool $restricted = false,
    array $unmetConditions = [],
): PermissionResolutionDto {
    return new PermissionResolutionDto(
        allowed: $allowed,
        stored: $stored,
        granted: $granted,
        grantedBy: $grantedBy,
        missing: $missing,
        conflicting: $conflicting,
        restricted: $restricted,
        unmetConditions: $unmetConditions,
    );
}
