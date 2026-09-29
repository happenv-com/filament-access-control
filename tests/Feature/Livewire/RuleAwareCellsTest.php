<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\PermissionRestrictions;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->admin = createRole(Role::ADMINISTRATOR, name: 'Administrator');
    $this->editor = createRole('editor', name: 'Editor');
    $this->key = holderKey($this->editor);
    $this->column = 'holder_' . $this->key;
});

describe('a role\'s cells', function (): void {
    it('show what the rules make of the role\'s grants', function (): void {
        $this->editor->update(['permissions' => [
            ProductPermission::Update->value,
            ProductPermission::Delete->value,
            GalleryPermission::View->value,
            GalleryPermission::Archive->value,
        ]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . ProductPermission::Update->value)
            ->assertTableColumnStateSet($this->column, 'implied', 'permission:' . GalleryPermission::Manage->value)
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'not-granted', 'permission:' . ProductPermission::Create->value);
    });

    it('never show a condition — a role does not sign in', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Publish->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Publish->value);
    });

    it('show a permission the application restricts', function (): void {
        resolve(PermissionRestrictions::class)->restrictUsing(fn (PermissionDefinition $permission): bool => $permission === ProductPermission::Delete);

        $this->editor->update(['permissions' => [ProductPermission::Delete->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'restricted', 'permission:' . ProductPermission::Delete->value);
    });

    it('name what is involved in the tooltip', function (): void {
        $this->editor->update(['permissions' => [
            GalleryPermission::View->value,
            GalleryPermission::Archive->value,
            ProductPermission::Delete->value,
            ProductPermission::Update->value,
        ]]);

        $matrix = livewire(RolePermissionMatrix::class)->instance();

        expect($matrix->holderCell($this->key, GalleryPermission::View->value)->tooltip())->toBe('Missing requirement: View products')
            ->and($matrix->holderCell($this->key, GalleryPermission::Archive->value)->tooltip())->toBe('Blocked by: Delete products')
            ->and($matrix->holderCell($this->key, GalleryPermission::Manage->value)->tooltip())->toBe('Implied by: Update products · A click grants it explicitly');
    });

    it('do not promise a click to an operator who cannot make it', function (): void {
        signInOperator([RolePermission::View->value]);

        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        expect(livewire(RolePermissionMatrix::class)->instance()->holderCell($this->key, GalleryPermission::Manage->value)->tooltip())
            ->toBe('Implied by: Update products');
    });

    it('keep the super-admin role fully in effect, whatever the rules say', function (): void {
        $column = 'holder_' . holderKey($this->admin);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($column, 'effective', 'permission:' . GalleryPermission::View->value)
            ->assertTableColumnStateSet($column, 'effective', 'permission:' . GalleryPermission::Archive->value);
    });

    it('are drawn the same in the editor of one role', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value);
    });

    it('revoke a stored permission that is not in effect when clicked', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, GalleryPermission::View->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([]);
    });

    it('grant an implied permission explicitly when clicked', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, GalleryPermission::Manage->value);

        expect($this->editor->fresh()->getPermissions()->all())
            ->toEqualCanonicalizing([ProductPermission::Update->value, GalleryPermission::Manage->value]);
    });
});

describe('staged changes (deferred) — the spec\'s table', function (): void {
    it('show a staged revocation as not granted', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'not-granted', 'permission:' . GalleryPermission::Archive->value);

        expect($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->color())->toBe('primary');
    });

    it('show a staged grant as stored and in effect', function (): void {
        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Archive->value);

        expect($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->staged)->toBeTrue();
    });

    it('show a staged grant of an implied permission as stored', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->assertTableColumnStateSet($this->column, 'implied', 'permission:' . GalleryPermission::Manage->value)
            ->call('toggle', $this->key, GalleryPermission::Manage->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Manage->value);
    });

    it('show at once, before saving, the conflict a staged grant causes', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Delete->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([GalleryPermission::Archive->value])
            ->and($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->staged)->toBeFalse();
    });

    it('show the requirement a staged grant satisfies', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::View->value);
    });

    it('go back to what is saved on discard', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Delete->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value)
            ->call('discard')
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Archive->value);
    });
});

describe('live changes', function (): void {
    it('resolve the related cells again in the same response', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::View->value);
    });
});
