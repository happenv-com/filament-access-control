<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Happenv\FilamentAccessControl\Events\PermissionsUpdated;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Js;
use Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class);

beforeEach(function (): void {
    signInOperator();

    $this->admin = createRole(Role::ADMINISTRATOR, name: 'Administrator');
    $this->editor = createRole('editor', name: 'Editor');
});

describe('rendering', function (): void {
    it('draws a Filament table with a column per role, the super-admin first', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertOk()
            ->assertTableColumnExists('label')
            ->assertTableColumnExists('holder_' . holderKey($this->admin))
            ->assertTableColumnExists('holder_' . holderKey($this->editor))
            ->assertSeeInOrder(['Administrator', 'Editor'])
            ->assertSee(__('filament-access-control::editor.super_admin_hint'));
    });

    it('lists each subject followed by its permissions, grouped by module', function (): void {
        $records = livewire(RolePermissionMatrix::class)->instance()->permissionRecords();

        expect(array_slice(array_keys($records), 0, 3))->toBe([
            'subject:' . RolePermission::class,
            'permission:' . RolePermission::View->value,
            'permission:' . RolePermission::Create->value,
        ])
            ->and($records['subject:' . ProductPermission::class])->toMatchArray([
                'type' => 'subject',
                'group' => 'catalogue',
                'group_name' => 'Catalogue',
                'label' => 'Products',
            ])
            ->and($records['permission:' . ProductPermission::Update->value])->toMatchArray([
                'type' => 'permission',
                'label' => 'Update',
                'description' => 'Change names, prices and stock',
                'slug' => ProductPermission::Update->value,
            ]);
    });

    it('folds the module groups by default, in Filament\'s own collapsible groups', function (): void {
        $table = livewire(RolePermissionMatrix::class)->instance()->getTable();

        expect($table->areGroupsCollapsedByDefault())->toBeTrue()
            ->and($table->getGrouping()?->isCollapsible())->toBeTrue()
            ->and($table->getGrouping()?->getTitle(['group_name' => 'Catalogue']))->toBe('Catalogue');
    });

    it('opens and folds every group at once', function (): void {
        $component = livewire(RolePermissionMatrix::class)
            ->callAction(TestAction::make('expandAll')->table());

        expect(collect($component->effects['xjs'] ?? [])->pluck('expression')->implode(' '))
            ->toContain('groupVisibility = ' . Js::from(['Administration', 'Catalogue']));

        $component->callAction(TestAction::make('collapseAll')->table());

        expect(collect($component->effects['xjs'] ?? [])->pluck('expression')->implode(' '))
            ->toContain('groupVisibility = ' . Js::from([]));
    });

    it('narrows the table to a search and opens what survives', function (): void {
        $component = livewire(RolePermissionMatrix::class)
            ->searchTable('categor')
            ->assertSee('Categories')
            ->assertDontSee('Products');

        expect(collect($component->effects['xjs'] ?? [])->pluck('expression')->implode(' '))
            ->toContain('groupVisibility = ' . Js::from(['Catalogue']));
    });

    it('says so when nothing matches the search', function (): void {
        livewire(RolePermissionMatrix::class)
            ->searchTable('nothing like it')
            ->assertSee(__('filament-access-control::editor.search_empty', ['search' => 'nothing like it']));
    });

    it('says so when there are no roles', function (): void {
        Role::query()->delete();

        livewire(RolePermissionMatrix::class)->assertSee(__('filament-access-control::editor.no_roles'));
    });

    it('draws only what a surface offers', function (): void {
        livewire(RolePermissionMatrix::class, ['surface' => Surface::Api])
            ->assertSee('Products')
            ->assertDontSee('Categories');
    });

    it('shows a subject held in full, in part or not at all', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value, ...array_column(CategoryPermission::cases(), 'value')]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'some', 'subject:' . ProductPermission::class)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'all', 'subject:' . CategoryPermission::class)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'none', 'subject:' . RolePermission::class)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'effective', 'permission:' . ProductPermission::View->value)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'not-granted', 'permission:' . ProductPermission::Create->value)
            ->assertTableColumnStateSet('holder_' . holderKey($this->admin), 'all', 'subject:' . RolePermission::class);
    });

    it('counts, in each group\'s header, what every role holds of it — when asked to', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value, CategoryPermission::View->value]]);

        $record = ['group' => 'catalogue', 'group_description' => 'What the shop sells'];

        $without = livewire(RolePermissionMatrix::class)->instance();
        $with = livewire(RolePermissionMatrix::class, ['counters' => true])->instance();

        expect($without->groupDescription($record))->toBe('What the shop sells')
            ->and((string) $with->groupDescription($record))
            ->toContain('What the shop sells')
            ->toContain('Administrator:')
            ->toContain('7 of 7')
            ->toContain('Editor:')
            ->toContain('2 of 7');
    });

    it('counts a subject in its cell\'s tooltip when counters are on', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::View->value]]);

        livewire(RolePermissionMatrix::class, ['counters' => true])
            ->assertSee('1 of 4 · ' . __('filament-access-control::editor.toggle_subject'));
    });

    it('takes the counters from the plugin', function (): void {
        plugin()->counters();

        livewire(RolePermissionMatrix::class)->assertSet('counters', true);

        plugin()->counters(false);

        livewire(RolePermissionMatrix::class)->assertSet('counters', false);
    });

    it('keeps the root element\'s attributes free of Livewire\'s block markers', function (): void {
        // A control structure in the partial included INSIDE the root tag once closed it early and
        // printed a stray `>` above the table.
        foreach ([false, true] as $deferred) {
            $rootTag = str(livewire(RolePermissionMatrix::class, ['deferred' => $deferred])->html())->after('<div')->before('>')->toString();

            expect($rootTag)->toContain('x-data')->not->toContain('<!--');
        }
    });

    it('needs no dependencies column while no permission declares a rule or a condition', function (): void {
        livewire(RolePermissionMatrix::class)->assertTableColumnHidden('dependencies');
    });

    it('says nothing about declarations that are sound', function (): void {
        livewire(RolePermissionMatrix::class)->assertDontSee(__('filament-access-control::editor.problems.heading'));
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

    it('toggles through the cell Filament draws', function (): void {
        livewire(RolePermissionMatrix::class)
            ->callTableColumnAction('holder_' . holderKey($this->editor), 'permission:' . ProductPermission::View->value)
            ->callTableColumnAction('holder_' . holderKey($this->editor), 'subject:' . CategoryPermission::class);

        expect($this->editor->fresh()->getPermissions()->all())->toEqualCanonicalizing([
            ProductPermission::View->value,
            ...array_column(CategoryPermission::cases(), 'value'),
        ]);
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
            ->assertActionEnabled(TestAction::make('saveChanges')->table())
            ->assertActionExists(TestAction::make('saveChanges')->table(), fn ($action): bool => (int) $action->getBadge() === 1)
            ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'effective', 'permission:' . ProductPermission::View->value)
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
            ->callAction(TestAction::make('saveChanges')->table())
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
            ->callAction(TestAction::make('discardChanges')->table())
            ->assertSet('changes', [])
            ->call('save')
            ->assertNotNotified();

        expect($this->editor->fresh()->getPermissions())->toBeEmpty();
    });

    it('offers save and discard only when deferred, and only once something is staged', function (): void {
        livewire(RolePermissionMatrix::class)
            ->assertActionHidden(TestAction::make('saveChanges')->table())
            ->assertActionHidden(TestAction::make('discardChanges')->table());

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->assertActionVisible(TestAction::make('saveChanges')->table())
            ->assertActionDisabled(TestAction::make('saveChanges')->table())
            ->assertActionDisabled(TestAction::make('discardChanges')->table());
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
            ->callAction(TestAction::make('deleteRole')->table(), data: ['role' => holderKey($this->editor)])
            ->assertHasNoFormErrors()
            ->assertNotified(__('filament-access-control::editor.notifications.role_deleted'))
            ->assertDispatched(RolePermissionMatrix::ROLES_CHANGED)
            ->assertTableColumnDoesNotExist('holder_' . holderKey($this->editor));

        expect(Role::query()->find($this->editor->id))->toBeNull();
    });

    it('explains, with the voter\'s words, why a role cannot go', function (): void {
        $this->editor->users()->attach(createUser('member@example.com'));

        livewire(RolePermissionMatrix::class)
            ->callAction(TestAction::make('deleteRole')->table(), data: ['role' => holderKey($this->editor)])
            ->assertHasFormErrors(['role' => 'This role is assigned to users and cannot be deleted.']);

        expect(Role::query()->find($this->editor->id))->not->toBeNull();
    });

    it('never offers the super-admin', function (): void {
        $component = livewire(RolePermissionMatrix::class)
            ->callAction(TestAction::make('deleteRole')->table(), data: ['role' => holderKey($this->admin)])
            ->assertHasFormErrors(['role']);

        expect(Role::query()->find($this->admin->id))->not->toBeNull();
    });

    it('asks the plugin\'s delete ability', function (): void {
        signInOperator([RolePermission::View->value, RolePermission::Update->value]);

        livewire(RolePermissionMatrix::class)
            ->callAction(TestAction::make('deleteRole')->table(), data: ['role' => holderKey($this->editor)])
            ->assertHasFormErrors(['role']);

        expect(Role::query()->find($this->editor->id))->not->toBeNull();
    });

    it('is not offered when there is nothing to delete', function (): void {
        $this->editor->delete();

        livewire(RolePermissionMatrix::class)->assertActionHidden(TestAction::make('deleteRole')->table());
    });
});
