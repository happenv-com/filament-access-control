<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\PlainAccount;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

beforeEach(function (): void {
    $this->operator = signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
    $this->support = createRole('support', name: 'Support');

    $this->operator->roles()->attach($this->editor);
});

it('keeps the operator off a role they hold, and only that one', function (): void {
    $component = livewire(RolePermissionMatrix::class)
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->assertNotified(__('filament-access-control::editor.notifications.own_holder'))
        ->call('toggle', holderKey($this->support), ProductPermission::View->value);

    expect($component->instance()->canEditHolder(holderKey($this->editor)))->toBeFalse()
        ->and($component->instance()->getTable()->getColumn('holder_' . holderKey($this->editor))->getHeaderTooltip())
        ->toBe(__('filament-access-control::editor.own_role_hint'))
        ->and($component->instance()->getTable()->getColumn('holder_' . holderKey($this->support))->getHeaderTooltip())->toBeNull()
        ->and($component->instance()->canEditHolder(holderKey($this->support)))->toBeTrue()
        ->and($this->editor->fresh()->getPermissions())->toBeEmpty()
        ->and($this->support->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
});

it('keeps the operator off their own direct permissions, and says so', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->operator, 'ability' => UserPermission::Update])
        ->assertSee(__('filament-access-control::editor.own_holder_hint'))
        ->assertDontSee(__('filament-access-control::editor.read_only_hint'))
        ->call('toggle', holderKey($this->operator), ProductPermission::Delete->value)
        ->assertNotified(__('filament-access-control::editor.notifications.own_holder'));

    expect($this->operator->fresh()->getPermissions()->all())->toContain(ProductPermission::Delete->value);
});

it('discards at save the changes to a role the operator has come to hold', function (): void {
    $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
        ->call('toggle', holderKey($this->support), ProductPermission::View->value);

    $this->operator->roles()->attach($this->support);
    $this->operator->load('roles');

    $component->call('save')
        ->assertNotified(__('filament-access-control::editor.notifications.discarded', [
            'holder' => 'Support',
            'reason' => __('filament-access-control::editor.notifications.own_holder'),
        ]))
        ->assertSet('changes', []);

    expect($this->support->fresh()->getPermissions())->toBeEmpty();
});

it('says so on the editor of a role the operator holds', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->assertSee(__('filament-access-control::editor.own_role_hint'))
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->assertNotified(__('filament-access-control::editor.notifications.own_holder'));

    expect($this->editor->fresh()->getPermissions())->toBeEmpty();
});

it('keeps even a super-admin off an ordinary role they also hold', function (): void {
    $this->operator->roles()->attach(createRole(Role::ADMINISTRATOR));

    livewire(RolePermissionMatrix::class)
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->assertNotified(__('filament-access-control::editor.notifications.own_holder'));

    expect($this->editor->fresh()->getPermissions())->toBeEmpty();
});

it('knows the operator\'s own row under another model class', function (): void {
    $account = PlainAccount::query()->findOrFail($this->operator->getKey());

    livewire(RecordPermissions::class, ['record' => $account, 'ability' => null])
        ->call('toggle', holderKey($account), ProductPermission::Delete->value)
        ->assertNotified(__('filament-access-control::editor.notifications.own_holder'));
});

it('lets the operator edit their own roles with the guard off', function (): void {
    plugin()->preventSelfEditing(false);

    livewire(RolePermissionMatrix::class)->call('toggle', holderKey($this->editor), ProductPermission::View->value);

    expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
});
