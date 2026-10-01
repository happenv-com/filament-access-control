<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Filament\Tables\Columns\Column;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Livewire\Features\SupportTesting\Testable;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

beforeEach(function (): void {
    // May change roles, and holds one permission of the catalogue: View products.
    $this->operator = signInOperator([
        RolePermission::View->value,
        RolePermission::Update->value,
        UserPermission::Update->value,
        ProductPermission::View->value,
    ]);

    $this->editor = createRole('editor', name: 'Editor');
    $this->key = holderKey($this->editor);
    $this->column = 'holder_' . $this->key;
});

/**
 * The holder column of the role, bound to one row — what Filament draws for that cell.
 */
function guardedCell(Testable $component, string $column, string $recordKey): Column
{
    $cell = $component->instance()->getTable()->getColumn($column);
    $cell->record($component->instance()->getTableRecord($recordKey));

    return $cell;
}

describe('live', function (): void {
    it('grants a permission the operator holds', function (): void {
        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, ProductPermission::View->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('refuses to grant a permission the operator does not hold', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggle', $this->key, ProductPermission::Create->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('refuses to revoke a permission the operator does not hold', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Create->value]]);

        livewire(RolePermissionMatrix::class)
            ->call('toggle', $this->key, ProductPermission::Create->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'));

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Create->value]);
    });

    it('toggles only what the operator may change of a subject, and decides by that alone', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Create->value]]);

        $component = livewire(RolePermissionMatrix::class)
            ->call('toggleSubject', $this->key, 'catalogue', ProductPermission::class);

        expect($this->editor->fresh()->getPermissions()->all())
            ->toEqualCanonicalizing([ProductPermission::Create->value, ProductPermission::View->value]);

        $component->call('toggleSubject', $this->key, 'catalogue', ProductPermission::class);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Create->value]);
    });

    it('refuses a subject of which the operator may change nothing', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggleSubject', $this->key, 'catalogue', CategoryPermission::class)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('guards a user\'s direct permissions the same way', function (): void {
        $user = createUser('member@example.com');

        livewire(RecordPermissions::class, ['record' => $user, 'ability' => UserPermission::Update])
            ->call('toggle', holderKey($user), ProductPermission::Delete->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'))
            ->call('toggle', holderKey($user), ProductPermission::View->value);

        expect($user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });
});

describe('deferred', function (): void {
    it('refuses the click, staging nothing', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Create->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'))
            ->assertSet('changes', []);
    });

    it('discards at save what the operator no longer holds, and saves the rest', function (): void {
        $this->operator->update(['permissions' => [...$this->operator->getPermissions(), ProductPermission::Create->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->call('toggle', $this->key, ProductPermission::Create->value);

        $this->operator->update(['permissions' => $this->operator->getPermissions()->reject(ProductPermission::Create->value)->values()->all()]);

        $component->call('save')
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable_discarded', ['permissions' => 'Create products']))
            ->assertSet('changes', []);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('reports no save when nothing staged may be saved any more', function (): void {
        $this->operator->update(['permissions' => [...$this->operator->getPermissions(), ProductPermission::Create->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Create->value);

        $this->operator->update(['permissions' => $this->operator->getPermissions()->reject(ProductPermission::Create->value)->values()->all()]);

        $component->call('save')
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable_discarded', ['permissions' => 'Create products']))
            ->assertNotNotified(__('filament-access-control::editor.notifications.saved'))
            ->assertNotDispatched('filament-access-control::permissions-updated');

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });
});

describe('who is not narrowed', function (): void {
    it('lets an operator holding the super-admin role change anything', function (): void {
        $this->operator->roles()->attach(createRole(Role::ADMINISTRATOR));

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, ProductPermission::Delete->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Delete->value]);
    });

    it('lets grantableBy() decide instead', function (): void {
        plugin()->grantableBy(fn (): array => [ProductPermission::Delete->value]);

        livewire(RolePermissionMatrix::class)
            ->call('toggle', $this->key, ProductPermission::Delete->value)
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_grantable'));

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Delete->value]);
    });

    it('changes anything with the guard off', function (): void {
        plugin()->preventEscalation(false);

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, ProductPermission::Delete->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Delete->value]);
    });
});

describe('the cells', function (): void {
    it('switch off a permission the operator does not hold, and say why', function (): void {
        $component = livewire(RolePermissionMatrix::class);

        expect(guardedCell($component, $this->column, 'permission:' . ProductPermission::Create->value))
            ->isClickDisabled()->toBeTrue()
            ->getTooltip()->toBe(__('filament-access-control::editor.cells.not_grantable'))
            ->and(guardedCell($component, $this->column, 'permission:' . ProductPermission::View->value))
            ->isClickDisabled()->toBeFalse()
            ->getTooltip()->toBeNull();
    });

    it('switch off a subject only when nothing of it may change', function (): void {
        $component = livewire(RolePermissionMatrix::class);

        expect(guardedCell($component, $this->column, 'subject:' . CategoryPermission::class)->isClickDisabled())->toBeTrue()
            ->and(guardedCell($component, $this->column, 'subject:' . ProductPermission::class)->isClickDisabled())->toBeFalse();
    });

    it('say why on a user\'s direct column too', function (): void {
        $user = createUser('member@example.com');
        $component = livewire(RecordPermissions::class, ['record' => $user, 'ability' => UserPermission::Update]);

        expect(guardedCell($component, 'holder_' . holderKey($user), 'permission:' . ProductPermission::Create->value))
            ->isClickDisabled()->toBeTrue()
            ->getTooltip()->toBe(__('filament-access-control::editor.cells.not_grantable'));
    });

    it('stay as they were with the guard off', function (): void {
        plugin()->preventEscalation(false);

        expect(guardedCell(livewire(RolePermissionMatrix::class), $this->column, 'permission:' . ProductPermission::Create->value)->isClickDisabled())->toBeFalse();
    });
});

it('offers save only for what may still be saved', function (): void {
    livewire(RolePermissionMatrix::class, ['deferred' => true])
        ->call('toggle', $this->key, ProductPermission::Create->value)
        ->assertActionDisabled(TestAction::make('saveChanges')->table());
});
