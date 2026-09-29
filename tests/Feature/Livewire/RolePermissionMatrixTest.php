<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Filament\Support\Icons\Heroicon;
use Happenv\FilamentAccessControl\Events\PermissionsUpdated;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Illuminate\Support\Facades\Event;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class);

beforeEach(function (): void {
    signInOperator();

    $this->admin = createRole(Role::ADMINISTRATOR, name: 'Administrator');
    $this->editor = createRole('editor', name: 'Editor');
});

describe('rendering', function (): void {
    it('draws every role as a column, the super-admin first', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertOk()
            ->assertSeeInOrder(['Administrator', 'Editor'])
            ->assertSee(__('filament-access-control::editor.super_admin'));
    });

    it('starts with every group folded', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertSee('Catalogue')
            ->assertDontSee('Products');
    });

    it('opens and closes a group', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggleGroup', 'catalogue')
            ->assertSee('Products')
            ->assertSee('Change names, prices and stock')
            ->call('toggleGroup', 'catalogue')
            ->assertDontSee('Products');
    });

    it('opens and closes every group at once', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('expandAll')
            ->assertSet('expandedGroups', ['administration', 'catalogue'])
            ->assertSee('Products')
            ->assertSee('Roles')
            ->call('collapseAll')
            ->assertSet('expandedGroups', []);
    });

    it('opens every group that survives a search', function (): void {
        livewire(RolePermissionMatrix::class)
            ->set('search', 'categor')
            ->assertSee('Categories')
            ->assertDontSee('Products')
            ->assertDontSee('Administration');
    });

    it('says so when nothing matches the search', function (): void {
        livewire(RolePermissionMatrix::class)
            ->set('search', 'nothing like it')
            ->assertSee(__('filament-access-control::editor.search_empty', ['search' => 'nothing like it']));
    });

    it('keeps the root element\'s attributes free of Livewire\'s block markers', function (): void {
        // A control structure in the partial included INSIDE the root tag once closed it early and
        // printed a stray `>` above the toolbar.
        foreach ([false, true] as $deferred) {
            $html = livewire(RolePermissionMatrix::class, ['deferred' => $deferred])->html();
            $rootTag = str($html)->after('<div')->before('>')->toString();

            expect($rootTag)->toContain('fi-ac-matrix')->not->toContain('<!--');
        }
    });

    it('says so when there are no roles', function (): void {
        Role::query()->delete();

        livewire(RolePermissionMatrix::class)->assertSee(__('filament-access-control::editor.no_roles'));
    });

    it('draws only what a surface offers', function (): void {
        livewire(RolePermissionMatrix::class, ['surface' => Surface::Api])
            ->call('expandAll')
            ->assertSee('Products')
            ->assertDontSee('Categories');
    });

    it('counts what each role holds in a group', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value, CategoryPermission::View->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertSee(__('filament-access-control::editor.counter', ['granted' => 2, 'total' => 7]))
            ->assertSee(__('filament-access-control::editor.counter', ['granted' => 7, 'total' => 7]));
    });
});

describe('live', function (): void {
    it('grants a permission the role did not hold', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertDispatched('filament-access-control::permissions-updated');

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
    });

    it('revokes a permission the role held and leaves the rest alone', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value, ProductPermission::Update->value]]);

        livewire(RolePermissionMatrix::class)->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Update->value]);
    });

    it('grants a whole subject when some of it is missing, and clears it when all of it is held', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value]]);

        $component = livewire(RolePermissionMatrix::class)
            ->call('toggleSubject', holderKey($this->editor), 'catalogue', ProductPermission::class);

        expect($this->editor->fresh()->getPermissions()->all())->toEqualCanonicalizing(array_column(ProductPermission::cases(), 'value'));

        $component->call('toggleSubject', holderKey($this->editor), 'catalogue', ProductPermission::class);

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('dispatches what changed', function (): void {
        Event::fake([PermissionsUpdated::class]);

        livewire(RolePermissionMatrix::class)->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        Event::assertDispatched(PermissionsUpdated::class, fn (PermissionsUpdated $event): bool => $event->granted === [ProductPermission::View->value]);
    });
});

describe('deferred', function (): void {
    it('collects clicks without writing them', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertSet('changes', [holderKey($this->editor) => ['grant' => [ProductPermission::View->value], 'revoke' => []]])
            ->assertSee(trans_choice('filament-access-control::editor.staged', 1, ['count' => 1]))
            ->assertNotDispatched('filament-access-control::permissions-updated');

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('shows a staged change as if it were already made', function (): void {
        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        expect($component->instance()->isGranted(holderKey($this->editor), ProductPermission::View->value))->toBeTrue()
            ->and($component->instance()->isStaged(holderKey($this->editor), ProductPermission::View->value))->toBeTrue()
            ->and($component->instance()->isStored(holderKey($this->editor), ProductPermission::View->value))->toBeFalse();
    });

    it('forgets a change clicked back', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertSet('changes', []);
    });

    it('writes everything on save, for every role touched', function (): void {
        $manager = createRole('manager', [ProductPermission::Delete->value]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->call('toggleSubject', holderKey($this->editor), 'catalogue', CategoryPermission::class)
            ->call('toggle', holderKey($manager), ProductPermission::Delete->value)
            ->call('save')
            ->assertSet('changes', [])
            ->assertNotified(__('filament-access-control::editor.notifications.saved'));

        expect($this->editor->fresh()->getPermissions()->all())->toEqualCanonicalizing([
            ProductPermission::View->value,
            ...array_column(CategoryPermission::cases(), 'value'),
        ])->and($manager->fresh()->getPermissions())->toBeEmpty();
    });

    it('saves onto what another operator wrote in the meantime', function (): void {
        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        $this->editor->update(['permissions' => [ProductPermission::Delete->value]]);

        $component->call('save');

        expect($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::Delete->value, ProductPermission::View->value]);
    });

    it('throws the changes away on discard', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->call('discard')
            ->assertSet('changes', [])
            ->call('save')
            ->assertNotNotified();

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('takes the default from the plugin', function (): void {
        plugin()->deferred();

        livewire(RolePermissionMatrix::class)->assertSet('deferred', true);

        plugin()->deferred(false);
    });

    it('keeps the staged changes out of the client\'s reach', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->set('changes', [holderKey($this->editor) => ['grant' => [RolePermission::Delete->value], 'revoke' => []]]);
    })->throws(CannotUpdateLockedPropertyException::class);

    it('drops the staged changes of a role deleted meanwhile', function (): void {
        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->tap(fn () => $this->editor->delete())
            ->dispatch(RolePermissionMatrix::ROLES_CHANGED)
            ->assertSet('changes', []);
    });
});

describe('refusals', function (): void {
    it('refuses a slug the catalogue does not know', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggle', holderKey($this->editor), 'not.a.permission')
            ->assertNotified(__('filament-access-control::editor.notifications.no_permission'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('refuses a permission the surface does not offer', function (): void {
        livewire(RolePermissionMatrix::class, ['surface' => Surface::Api])
            ->call('toggle', holderKey($this->editor), ProductPermission::Delete->value)
            ->assertNotified(__('filament-access-control::editor.notifications.not_offered'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('refuses a subject that is not there', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggleSubject', holderKey($this->editor), 'catalogue', 'App\\Nope')
            ->assertNotified(__('filament-access-control::editor.notifications.no_permission'));
    });

    it('refuses a role that is not there', function (): void {
        livewire(RolePermissionMatrix::class)
            ->call('toggle', '999', ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.no_holder'));
    });

    it('never writes to the super-admin role', function (): void {
        $component = livewire(RolePermissionMatrix::class)
            ->call('toggle', holderKey($this->admin), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.super_admin_hint'));

        expect($this->admin->fresh()->getPermissions())->toBeEmpty()
            ->and($component->instance()->isGranted(holderKey($this->admin), ProductPermission::View->value))->toBeTrue()
            ->and($component->instance()->canEditHolder(holderKey($this->admin)))->toBeFalse();
    });

    it('asks the gate, with the role, before every write', function (): void {
        signInOperator([RolePermission::View->value]);

        $component = livewire(RolePermissionMatrix::class)
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
            ->assertNotified(__('filament-access-control::editor.notifications.unauthorized'));

        expect($this->editor->fresh()->getPermissions())->toBeEmpty()
            ->and($component->instance()->canEditHolder(holderKey($this->editor)))->toBeFalse();
    });

    it('asks the gate again at save', function (): void {
        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', holderKey($this->editor), ProductPermission::View->value);

        plugin()->roleAbilities(update: fn (): bool => false);

        $component->call('save')
            ->assertNotified(__('filament-access-control::editor.notifications.unauthorized'))
            ->assertSet('changes', [holderKey($this->editor) => ['grant' => [ProductPermission::View->value], 'revoke' => []]]);

        plugin()->roleAbilities(update: RolePermission::Update);

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });
});

describe('deleting a role', function (): void {
    it('deletes a role nobody holds', function (): void {
        livewire(RolePermissionMatrix::class)
            ->callAction(TestAction::make('deleteRole')->arguments(['role' => holderKey($this->editor)]))
            ->assertDispatched(RolePermissionMatrix::ROLES_CHANGED);

        expect(Role::query()->find($this->editor->id))->toBeNull();
    });

    it('explains, with the voter\'s words, why a role cannot go', function (): void {
        $this->editor->users()->attach(createUser('member@example.com'));

        livewire(RolePermissionMatrix::class)
            ->assertActionDisabled(TestAction::make('deleteRole')->arguments(['role' => holderKey($this->editor)]))
            ->assertActionExists(
                TestAction::make('deleteRole')->arguments(['role' => holderKey($this->editor)]),
                fn ($action): bool => $action->getAuthorizationResponseWithMessage()->message() === 'This role is assigned to users and cannot be deleted.',
            );

        expect(Role::query()->find($this->editor->id))->not->toBeNull();
    });

    it('draws the delete button as an icon', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertActionHasIcon(TestAction::make('deleteRole')->arguments(['role' => holderKey($this->editor)]), Heroicon::OutlinedTrash);
    });

    it('offers no delete button under the super-admin', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertSeeHtml('fac.delete.' . holderKey($this->editor))
            ->assertDontSeeHtml('mountAction(\'deleteRole\', JSON.parse(\'{"role":"' . holderKey($this->admin));
    });
});
