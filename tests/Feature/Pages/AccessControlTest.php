<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Pages\AccessControl;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

covers(AccessControl::class);

it('is registered on the panel', function (): void {
    expect(Filament::getPanel('admin')->getPages())->toContain(AccessControl::class)
        ->and(AccessControl::getUrl(panel: 'admin'))->toEndWith('/admin/access-control');
});

it('serves the matrix to somebody who may see roles', function (): void {
    signInOperator([RolePermission::View->value]);

    get(AccessControl::getUrl(panel: 'admin'))
        ->assertOk()
        ->assertSeeLivewire(RolePermissionMatrix::class);
});

it('is closed to anybody else', function (): void {
    signInOperator([]);

    expect(AccessControl::canAccess())->toBeFalse();

    get(AccessControl::getUrl(panel: 'admin'))->assertForbidden();
});

it('adds a role', function (): void {
    signInOperator();

    livewire(AccessControl::class)
        ->callAction('createRole', data: ['name' => 'Support', 'code' => 'support'])
        ->assertHasNoFormErrors()
        ->assertDispatched(RolePermissionMatrix::ROLES_CHANGED);

    expect(Role::query()->where('code', 'support')->first())->name->toBe('Support');
});

it('adds roles only for somebody who may create them', function (): void {
    signInOperator([RolePermission::View->value]);

    livewire(AccessControl::class)->assertActionHidden(TestAction::make('createRole'));
});

it('passes the plugin\'s save mode on to the matrix', function (): void {
    signInOperator();

    plugin()->deferred()->counters()->rolesShownByDefault(8);

    expect(livewire(AccessControl::class)->instance()->getMatrixProperties())
        ->toBe(['deferred' => true, 'counters' => true, 'rolesShownByDefault' => 8]);

    plugin()->deferred(false)->counters(false)->rolesShownByDefault(null);
});

it('takes its navigation from the plugin', function (): void {
    plugin()
        ->navigationGroup('Settings')
        ->navigationIcon('heroicon-o-key')
        ->navigationSort(7)
        ->navigationLabel('Permissions');

    expect(AccessControl::getNavigationGroup())->toBe('Settings')
        ->and(AccessControl::getNavigationIcon())->toBe('heroicon-o-key')
        ->and(AccessControl::getNavigationSort())->toBe(7)
        ->and(AccessControl::getNavigationLabel())->toBe('Permissions')
        ->and(AccessControl::getCluster())->toBeNull();

    plugin()->navigationGroup(null)->navigationIcon('heroicon-o-shield-check')->navigationSort(null)->navigationLabel(null);

    expect(AccessControl::getNavigationLabel())->toBe('Access control');
});
