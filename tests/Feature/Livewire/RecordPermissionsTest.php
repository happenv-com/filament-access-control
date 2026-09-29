<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\LaravelAccessControl\PermissionRestrictions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\ViewException;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;

use function Pest\Livewire\livewire;

covers(RecordPermissions::class);

beforeEach(function (): void {
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
    $this->user = createUser('member@example.com');
});

describe('a role', function (): void {
    it('lists the catalogue with the role\'s grants, folded', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value]]);

        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->assertOk()
            ->assertSee('Catalogue')
            ->assertSee(__('filament-access-control::editor.counter', ['granted' => 1, 'total' => 7]))
            ->assertDontSee('Change names, prices and stock')
            ->call('toggleGroup', 'catalogue')
            ->assertSee('Change names, prices and stock')
            ->assertSeeHtml('aria-checked="true"');
    });

    it('writes each click at once', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->call('toggleSubject', holderKey($this->editor), 'catalogue', CategoryPermission::class);

        expect($this->editor->fresh()->getPermissions()->all())->toEqualCanonicalizing([
            ProductPermission::View->value,
            ...array_column(CategoryPermission::cases(), 'value'),
        ]);
    });

    it('reads its own writes back', function (): void {
        $component = livewire(RecordPermissions::class, ['record' => $this->editor])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        expect($component->instance()->isStored(holderKey($this->editor), ProductPermission::View->value))->toBeTrue();

        $component->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        expect($component->instance()->isStored(holderKey($this->editor), ProductPermission::View->value))->toBeFalse()
            ->and($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('collects clicks until saved when deferred', function (): void {
        $component = livewire(RecordPermissions::class, ['record' => $this->editor, 'deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertSee(__('filament-access-control::editor.actions.save'))
            ->assertSee(trans_choice('filament-access-control::editor.staged', 1, ['count' => 1]));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();

        $component->call('save')->assertSet('changes', []);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('draws no save button when live', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->assertDontSee(__('filament-access-control::editor.actions.save'));
    });

    it('is asked the plugin\'s role ability', function (): void {
        signInOperator([RolePermission::View->value]);

        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->assertSee(__('filament-access-control::editor.read_only_hint'))
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.unauthorized'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('shows the super-admin role fully granted and locked', function (): void {
        $admin = createRole(Role::ADMINISTRATOR);

        $component = livewire(RecordPermissions::class, ['record' => $admin])
            ->assertSee(__('filament-access-control::editor.super_admin_hint'))
            ->call('toggle', holderKey($admin), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.super_admin_hint'));

        expect($component->instance()->isGranted(holderKey($admin), ProductPermission::View->value))->toBeTrue()
            ->and($admin->fresh()->getPermissions())->toBeEmpty();
    });
});

describe('a user', function (): void {
    it('edits the user\'s own, direct grants', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->user, 'ability' => UserPermission::Update])
            ->call('toggle', holderKey($this->user), ProductPermission::View->value);

        expect($this->user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('asks Laravel\'s update ability when told nothing', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->user])
            ->call('toggle', holderKey($this->user), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.unauthorized'));

        expect($this->user->fresh()->getPermissions())->toBeEmpty();
    });

    it('checks nothing when told to', function (): void {
        signInOperator([]);

        livewire(RecordPermissions::class, ['record' => $this->user, 'ability' => null])
            ->call('toggle', holderKey($this->user), ProductPermission::View->value);

        expect($this->user->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('shows what the user\'s roles grant already', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value]]);
        $this->user->roles()->attach($this->editor);

        $component = livewire(RecordPermissions::class, ['record' => $this->user, 'ability' => UserPermission::Update])
            ->call('toggleGroup', 'catalogue')
            ->assertSee(__('filament-access-control::editor.inherited', ['roles' => 'Editor']));

        expect($component->instance()->inheritedFrom(ProductPermission::View->value))->toBe(['Editor'])
            ->and($component->instance()->isGranted(holderKey($this->user), ProductPermission::View->value))->toBeFalse();
    });

    it('can keep quiet about the roles', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value]]);
        $this->user->roles()->attach($this->editor);

        livewire(RecordPermissions::class, ['record' => $this->user, 'showInherited' => false])
            ->call('toggleGroup', 'catalogue')
            ->assertDontSee(__('filament-access-control::editor.inherited', ['roles' => 'Editor']));
    });

    it('says a super-admin role makes the list moot', function (): void {
        $this->user->roles()->attach(createRole(Role::ADMINISTRATOR, name: 'Administrator'));

        $component = livewire(RecordPermissions::class, ['record' => $this->user, 'ability' => UserPermission::Update])
            ->assertSee(trans_choice('filament-access-control::editor.super_admin_inherited', 1, ['roles' => 'Administrator']));

        expect($component->instance()->inheritedFrom(CategoryPermission::View->value))->toBe(['Administrator']);
    });
});

describe('a surface', function (): void {
    it('offers only what the surface offers', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->user, 'surface' => Surface::Api, 'ability' => null])
            ->call('expandAll')
            ->assertSee('Products')
            ->assertDontSee('Categories')
            ->call('toggle', holderKey($this->user), ProductPermission::Delete->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_offered'));

        expect($this->user->fresh()->getPermissions())->toBeEmpty();
    });

    it('lists what the record holds outside the offering, revocable but never grantable again', function (): void {
        $this->user->update(['permissions' => [ProductPermission::Delete->value]]);

        $component = livewire(RecordPermissions::class, ['record' => $this->user, 'surface' => Surface::Api, 'ability' => null, 'deferred' => true])
            ->assertSee(__('filament-access-control::editor.held_outside_offering.heading'))
            ->assertSee(ProductPermission::Delete->value)
            ->call('toggle', holderKey($this->user), ProductPermission::Delete->value)
            ->assertSet('changes', [holderKey($this->user) => ['grant' => [], 'revoke' => [ProductPermission::Delete->value]]])
            // Clicked back before saving: nothing is handed out, a staged revocation is withdrawn.
            ->call('toggle', holderKey($this->user), ProductPermission::Delete->value)
            ->assertSet('changes', [])
            ->call('toggle', holderKey($this->user), ProductPermission::Delete->value)
            ->call('save');

        expect($this->user->fresh()->getPermissions())->toBeEmpty();

        $component->assertDontSee(__('filament-access-control::editor.held_outside_offering.heading'))
            ->call('toggle', holderKey($this->user), ProductPermission::Delete->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_offered'));

        expect($this->user->fresh()->getPermissions())->toBeEmpty();
    });
});

describe('read-only', function (): void {
    it('changes nothing', function (): void {
        $component = livewire(RecordPermissions::class, ['record' => $this->editor, 'readOnly' => true])
            ->assertSee(__('filament-access-control::editor.read_only_hint'))
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.read_only'));

        expect($component->instance()->canEditHolder(holderKey($this->editor)))->toBeFalse()
            ->and($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('keeps the flag out of the client\'s reach', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->editor, 'readOnly' => true])->set('readOnly', false);
    })->throws(CannotUpdateLockedPropertyException::class);
});

it('marks a permission the application restricts', function (): void {
    resolve(PermissionRestrictions::class)->restrictUsing(fn ($permission): bool => $permission === ProductPermission::Delete);

    $component = livewire(RecordPermissions::class, ['record' => $this->editor])
        ->call('toggleGroup', 'catalogue')
        ->assertSee(__('filament-access-control::editor.restricted'));

    expect($component->instance()->isRestricted(resolve(PermissionTree::class)->find(ProductPermission::Delete->value)))->toBeTrue();
});

it('refuses a record it cannot edit the permissions of', function (): void {
    $record = new class extends Model
    {
        protected $table = 'roles';
    };

    livewire(RecordPermissions::class, ['record' => $record]);
})->throws(ViewException::class, 'must implement [' . HasEditablePermissions::class . ']');
