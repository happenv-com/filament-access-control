<?php

declare(strict_types=1);

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Panel;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Pages\AccessControl;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Illuminate\Database\Eloquent\Model;

covers(FilamentAccessControlPlugin::class);

it('registers the plugin on the panel', function (): void {
    expect(Filament::getPanel('admin')->getPlugin(FilamentAccessControlPlugin::ID))
        ->toBeInstanceOf(FilamentAccessControlPlugin::class);
});

it('serves the panel login page', function (): void {
    $this->get('/admin/login')->assertOk();
});

it('is found on the current panel', function (): void {
    expect(plugin())->toBe(Filament::getPanel('admin')->getPlugin(FilamentAccessControlPlugin::ID))
        ->and(FilamentAccessControlPlugin::current())->toBe(plugin());
});

it('falls back to an unconfigured plugin on a panel without it', function (): void {
    Filament::setCurrentPanel(Panel::make()->id('bare')->path('bare'));

    expect(FilamentAccessControlPlugin::current())
        ->not->toBe(Filament::getPanel('admin')->getPlugin(FilamentAccessControlPlugin::ID))
        ->getRoleModel()->toBeNull();
});

it('wants an Eloquent model that can have its permissions edited', function (): void {
    FilamentAccessControlPlugin::make()->roleModel(Model::class);
})->throws(InvalidArgumentException::class);

it('registers the access control page only once it knows the role model', function (): void {
    $withoutModel = Panel::make()->id('a');
    FilamentAccessControlPlugin::make()->register($withoutModel);

    $withModel = Panel::make()->id('b');
    FilamentAccessControlPlugin::make()->roleModel(Role::class)->register($withModel);

    $disabled = Panel::make()->id('c');
    FilamentAccessControlPlugin::make()->roleModel(Role::class)->accessControlPage(false)->register($disabled);

    expect($withoutModel->getPages())->not->toContain(AccessControl::class)
        ->and($withModel->getPages())->toContain(AccessControl::class)
        ->and($disabled->getPages())->not->toContain(AccessControl::class);
});

it('tells the super-admin role by its code, or by a closure', function (): void {
    $admin = new Role(['code' => Role::ADMINISTRATOR]);
    $editor = new Role(['code' => 'editor']);

    $byCode = FilamentAccessControlPlugin::make()->roleModel(Role::class)->superAdminRole(Role::ADMINISTRATOR);
    $byClosure = FilamentAccessControlPlugin::make()->superAdminRole(fn (Role $role): bool => $role->code === 'editor');

    expect($byCode->isSuperAdminRole($admin))->toBeTrue()
        ->and($byCode->isSuperAdminRole($editor))->toBeFalse()
        ->and($byCode->isSuperAdminRole(new User))->toBeFalse()
        ->and($byClosure->isSuperAdminRole($editor))->toBeTrue()
        ->and(FilamentAccessControlPlugin::make()->isSuperAdminRole($admin))->toBeFalse();
});

it('titles a role by an attribute or a closure, and by its key when there is nothing to show', function (): void {
    $role = new Role(['name' => 'Editor', 'code' => 'editor']);
    $role->id = 5;

    expect(FilamentAccessControlPlugin::make()->getRoleTitle($role))->toBe('Editor')
        ->and(FilamentAccessControlPlugin::make()->roleTitleAttribute('code')->getRoleTitle($role))->toBe('editor')
        ->and(FilamentAccessControlPlugin::make()->roleTitleAttribute(fn (Role $role): string => strtoupper($role->code))->getRoleTitle($role))->toBe('EDITOR')
        ->and(FilamentAccessControlPlugin::make()->roleTitleAttribute('missing')->getRoleTitle($role))->toBe('5');
});

it('orders roles by key unless told otherwise', function (): void {
    createRole('b');
    createRole('a');

    expect(FilamentAccessControlPlugin::make()->roleModel(Role::class)->getRolesQuery()->pluck('code')->all())->toBe(['b', 'a']);
});

it('cannot query roles it was never told about', function (): void {
    FilamentAccessControlPlugin::make()->getRolesQuery();
})->throws(InvalidArgumentException::class);

it('keeps the abilities it was not given', function (): void {
    $plugin = FilamentAccessControlPlugin::make()->roleAbilities(update: null);

    expect($plugin->getRoleAbility('update'))->toBeNull()
        ->and($plugin->getRoleAbility('viewAny'))->toBe('viewAny')
        ->and($plugin->getRoleAbility('create'))->toBe('create')
        ->and($plugin->getRoleAbility('delete'))->toBe('delete');
});

it('lets the application configure the role actions', function (): void {
    $plugin = FilamentAccessControlPlugin::make()
        ->modifyCreateRoleActionUsing(fn (CreateAction $action): CreateAction => $action->label('New role'))
        ->modifyDeleteRoleActionUsing(fn (Action $action): Action => $action->label('Remove'));

    expect($plugin->configureCreateRoleAction(CreateAction::make())->getLabel())->toBe('New role')
        ->and($plugin->configureDeleteRoleAction(Action::make('deleteRole'))->getLabel())->toBe('Remove');
});
