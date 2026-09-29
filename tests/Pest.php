<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Happenv\FilamentAccessControl\Tests\TestCase;
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
