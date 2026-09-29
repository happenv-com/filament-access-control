<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Forms\Components\PermissionSelector;
use Happenv\FilamentAccessControl\Tests\Fixtures\Livewire\DirectPermissionsForm;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

covers(PermissionSelector::class);

beforeEach(function (): void {
    signInOperator();
});

it('draws only what the surface offers', function (): void {
    livewire(DirectPermissionsForm::class, ['record' => createUser()])
        ->assertOk()
        ->assertSee('Products')
        ->assertDontSee('Categories');
});

it('draws the whole catalogue without a surface', function (): void {
    livewire(DirectPermissionsForm::class, ['record' => createUser(), 'narrowed' => false])
        ->assertSee('Products')
        ->assertSee('Categories');
});

it('saves the ticked slugs as a flat list', function (): void {
    $user = createUser();

    livewire(DirectPermissionsForm::class, ['record' => $user])
        ->set('data.permissions', [ProductPermission::View->value, ProductPermission::Create->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value, ProductPermission::Create->value]);
});

it('refuses a slug nobody declares', function (): void {
    livewire(DirectPermissionsForm::class, ['record' => createUser()])
        ->set('data.permissions', ['not.a.permission'])
        ->call('save')
        ->assertHasFormErrors(['permissions']);
});

it('refuses a known slug the surface does not offer', function (): void {
    $user = createUser();

    livewire(DirectPermissionsForm::class, ['record' => $user])
        ->set('data.permissions', [ProductPermission::Delete->value])
        ->call('save')
        ->assertHasFormErrors(['permissions']);

    expect($user->fresh()->getPermissions())->toBeEmpty();
});

it('shows what the record holds outside the offering, and lets it be revoked', function (): void {
    $user = createUser(permissions: [ProductPermission::View->value, CategoryPermission::Update->value]);

    livewire(DirectPermissionsForm::class, ['record' => $user])
        ->assertSee(__('filament-access-control::permission-selector.held_outside_offering.heading'))
        ->assertSee(CategoryPermission::Update->value)
        ->set('data.permissions', [ProductPermission::View->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
});

it('keeps a held grant outside the offering when it is left ticked', function (): void {
    $user = createUser(permissions: [CategoryPermission::Update->value]);

    livewire(DirectPermissionsForm::class, ['record' => $user])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->getPermissions()->all())->toBe([CategoryPermission::Update->value]);
});

it('keeps a grant this deployment cannot draw', function (): void {
    $user = createUser(permissions: ['module.left.out']);

    livewire(DirectPermissionsForm::class, ['record' => $user])
        ->set('data.permissions', ['module.left.out', ProductPermission::View->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value, 'module.left.out']);
});

it('sanitises whatever state arrives', function (): void {
    expect(PermissionSelector::make('permissions')->sanitise(['a', 'a', 3, null, 'b']))->toBe(['a', 'b'])
        ->and(PermissionSelector::make('permissions')->sanitise('a'))->toBe([]);
});
