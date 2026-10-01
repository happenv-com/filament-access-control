<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Forms\Components\PermissionSelector;
use Happenv\FilamentAccessControl\Tests\Fixtures\Livewire\DirectPermissionsForm;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;

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

describe('the escalation guard', function (): void {
    beforeEach(function (): void {
        signInOperator([ProductPermission::View->value]);
    });

    it('refuses to grant a permission the operator does not hold', function (): void {
        $user = createUser();

        livewire(DirectPermissionsForm::class, ['record' => $user])
            ->set('data.permissions', [ProductPermission::View->value, ProductPermission::Create->value])
            ->call('save')
            ->assertHasFormErrors(['permissions'])
            ->assertSee(__('filament-access-control::permission-selector.validation.not_grantable', [
                'permissions' => ProductPermission::Create->value,
            ]));

        expect($user->fresh()->getPermissions())->toBeEmpty();
    });

    it('refuses to revoke a permission the operator does not hold', function (): void {
        $user = createUser(permissions: [ProductPermission::Create->value]);

        livewire(DirectPermissionsForm::class, ['record' => $user])
            ->set('data.permissions', [])
            ->call('save')
            ->assertHasFormErrors(['permissions']);

        expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::Create->value]);
    });

    it('changes what the operator holds and leaves the rest as it is', function (): void {
        $user = createUser(permissions: [ProductPermission::Create->value]);

        livewire(DirectPermissionsForm::class, ['record' => $user])
            ->assertSee(__('filament-access-control::permission-selector.not_grantable'))
            ->set('data.permissions', [ProductPermission::Create->value, ProductPermission::View->value])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::Create->value, ProductPermission::View->value]);
    });

    it('refuses it on a form for a record not saved yet', function (): void {
        livewire(DirectPermissionsForm::class, ['record' => new User])
            ->set('data.permissions', [ProductPermission::View->value, ProductPermission::Create->value])
            ->call('save')
            ->assertHasFormErrors(['permissions']);
    });

    it('takes no part with the guard off', function (): void {
        plugin()->preventEscalation(false);

        $user = createUser();

        livewire(DirectPermissionsForm::class, ['record' => $user])
            ->assertDontSee(__('filament-access-control::permission-selector.not_grantable'))
            ->set('data.permissions', [ProductPermission::Create->value])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::Create->value]);
    });
});

describe('the self-editing guard', function (): void {
    it('keeps the operator off their own permissions, and says so', function (): void {
        $operator = signInOperator();

        livewire(DirectPermissionsForm::class, ['record' => $operator])
            ->assertSee(__('filament-access-control::permission-selector.own_record_hint'))
            ->set('data.permissions', [])
            ->call('save')
            ->assertHasFormErrors(['permissions'])
            ->assertSee(__('filament-access-control::permission-selector.validation.own_record'));

        expect($operator->fresh()->getPermissions())->not->toBeEmpty();
    });

    it('lets an unchanged form of their own be saved', function (): void {
        $operator = signInOperator();

        livewire(DirectPermissionsForm::class, ['record' => $operator])
            ->call('save')
            ->assertHasNoFormErrors();
    });

    it('lets the operator edit their own permissions with the guard off', function (): void {
        plugin()->preventSelfEditing(false);

        $operator = signInOperator();

        livewire(DirectPermissionsForm::class, ['record' => $operator])
            ->set('data.permissions', [ProductPermission::View->value])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($operator->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });
});

it('returns the held list unchanged on the operator\'s own record, whatever a forged state says', function (): void {
    $operator = signInOperator([ProductPermission::View->value]);

    $field = livewire(DirectPermissionsForm::class, ['record' => $operator, 'narrowed' => false])->instance()->form->getComponent('permissions');

    $merged = (fn (mixed $state): array => $this->merge($state))->call($field, [ProductPermission::Create->value]);

    expect($merged)->toEqualCanonicalizing($operator->getPermissions()->all());
});

it('never writes past the guards, even when nothing validated the list', function (): void {
    signInOperator([ProductPermission::View->value]);

    $user = createUser(permissions: [ProductPermission::Create->value]);
    $field = livewire(DirectPermissionsForm::class, ['record' => $user, 'narrowed' => false])->instance()->form->getComponent('permissions');

    // What dehydration writes: Update (not held by the operator) is not granted, Create (not held by
    // the operator either) is not revoked.
    $merged = (fn (mixed $state): array => $this->merge($state))->call($field, [ProductPermission::View->value, ProductPermission::Update->value]);

    expect($merged)->toBe([ProductPermission::View->value, ProductPermission::Create->value]);
});
