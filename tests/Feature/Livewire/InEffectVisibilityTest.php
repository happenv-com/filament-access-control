<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\PermissionRestrictions;

use function Pest\Livewire\livewire;

covers(RecordPermissions::class);

// The fixture catalogue registered by default declares no rule and no condition; GalleryPermission
// brings both, and is registered only by the tests that need it.
beforeEach(function (): void {
    signInOperator();

    $this->user = createUser('member@example.com', [ProductPermission::View->value]);
});

it('draws no In effect column when it could only repeat Granted', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->user])
        ->assertTableColumnVisible('holder_' . holderKey($this->user))
        ->assertTableColumnHidden('in_effect');
});

it('draws the column for an account holding a role', function (): void {
    $this->user->roles()->attach(createRole('editor', permissions: [ProductPermission::Update->value], name: 'Editor'));

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnVisible('in_effect')
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::Update->value);
});

it('draws the column for an account holding a role even with the From roles column switched off', function (): void {
    $this->user->roles()->attach(createRole('editor', permissions: [ProductPermission::Update->value], name: 'Editor'));

    livewire(RecordPermissions::class, ['record' => $this->user->fresh(), 'showInherited' => false])
        ->assertTableColumnHidden('inherited')
        ->assertTableColumnVisible('in_effect');
});

it('draws the column once a permission declares a rule or a condition', function (): void {
    registerPermissions(GalleryPermission::class);

    livewire(RecordPermissions::class, ['record' => $this->user])
        ->assertTableColumnVisible('in_effect');
});

it('draws the column while the application restricts a permission', function (): void {
    resolve(PermissionRestrictions::class)->restrictUsing(fn (PermissionDefinition $permission): bool => $permission === ProductPermission::Delete);
    $this->user->update(['permissions' => [ProductPermission::Delete->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user])
        ->assertTableColumnVisible('in_effect')
        ->assertTableColumnStateSet('in_effect', 'restricted', 'permission:' . ProductPermission::Delete->value);
});
