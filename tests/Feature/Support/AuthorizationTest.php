<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\Support\Authorization;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use function Pest\Laravel\actingAs;

covers(Authorization::class);

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');
});

it('allows when no ability is asked', function (): void {
    expect(Authorization::allows(null))->toBeTrue();
});

it('asks a permission enum of the access-control gate, with the record', function (): void {
    actingAs(createUser(permissions: [RolePermission::Delete->value]));

    $idle = createRole('idle');
    $busy = createRole('busy');
    $busy->users()->attach(createUser('someone@example.com'));

    expect(Authorization::allows(RolePermission::Delete, $idle))->toBeTrue()
        ->and(Authorization::inspect(RolePermission::Delete, $busy)->message())->toBe('This role is assigned to users and cannot be deleted.')
        ->and(Authorization::allows(RolePermission::Create, model: Role::class))->toBeFalse();
});

it('hands a string ability the model class when there is no record', function (): void {
    actingAs(createUser());

    Gate::define('create-things', fn (User $user, string $model): bool => $model === Role::class);

    expect(Authorization::allows('create-things', model: Role::class))->toBeTrue();
});

it('lets a closure decide', function (): void {
    actingAs($user = createUser());

    $role = createRole('editor');

    expect(Authorization::allows(fn (?Role $record, User $user): bool => $record?->is($role) && $user->exists, $role))->toBeTrue()
        ->and(Authorization::inspect(fn (): Response => Response::deny('No.'))->message())->toBe('No.')
        ->and(Authorization::allows(fn (): bool => false))->toBeFalse();
});

it('asks about the panel\'s user, not the default guard\'s', function (): void {
    config()->set('auth.guards.panel', ['driver' => 'session', 'provider' => 'users']);
    Filament::getCurrentPanel()->authGuard('panel');

    actingAs(createUser(permissions: [RolePermission::Create->value]), 'panel');
    Auth::shouldUse('web');

    expect(Auth::user())->toBeNull()
        ->and(Authorization::allows(RolePermission::Create))->toBeTrue();
});
