<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\DeclarationProblems;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Support\RefusalLead;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentAccessControlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-access-control';

    public static string $viewNamespace = 'filament-access-control';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasTranslations()
            ->hasViews(static::$viewNamespace);
    }

    public function packageRegistered(): void
    {
        // The lifetime is the REQUEST, not the worker: every label of the catalogue resolves
        // through `__()`, and a tree kept for the life of an Octane worker would serve the first
        // request's locale to every request after it.
        $this->app->scoped(PermissionTree::class);
        $this->app->scoped(RefusalLead::class);
        $this->app->scoped(DeclarationProblems::class);
    }

    public function packageBooted(): void
    {
        Livewire::component('filament-access-control.role-permission-matrix', RolePermissionMatrix::class);
        Livewire::component('filament-access-control.record-permissions', RecordPermissions::class);

        // Loaded by the permission graph's modal alone (`x-load`), never with the panel: mermaid.js
        // is megabytes, and nothing else needs it.
        FilamentAsset::register([
            AlpineComponent::make('permission-graph', __DIR__ . '/../resources/dist/components/permission-graph.js'),
        ], package: 'happenv-com/filament-access-control');
    }
}
