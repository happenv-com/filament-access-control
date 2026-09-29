<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\FilamentAccessControlServiceProvider;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\PermissionTree;

it('registers the service provider', function (): void {
    expect(app()->getProviders(FilamentAccessControlServiceProvider::class))->not->toBeEmpty();
});

it('loads the translations under the package namespace', function (): void {
    expect(__('filament-access-control::editor.actions.save'))->toBe('Save permissions');
});

it('registers the Livewire components by name', function (string $name, string $class): void {
    expect(app('livewire.finder')->resolveClassComponentClassName($name))->toBe($class);
})->with([
    ['filament-access-control.role-permission-matrix', RolePermissionMatrix::class],
    ['filament-access-control.record-permissions', RecordPermissions::class],
]);

it('builds the permission tree once per request, not once per worker', function (): void {
    $first = resolve(PermissionTree::class);

    expect(resolve(PermissionTree::class))->toBe($first);

    app()->forgetScopedInstances();

    expect(resolve(PermissionTree::class))->not->toBe($first);
});
