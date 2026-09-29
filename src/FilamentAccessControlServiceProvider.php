<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Happenv\FilamentAccessControl\Commands\FilamentAccessControlCommand;
use Happenv\FilamentAccessControl\Testing\TestsFilamentAccessControl;

class FilamentAccessControlServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-access-control';

    public static string $viewNamespace = 'filament-access-control';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command): void {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations();
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-access-control/{$file->getFilename()}"),
                ], 'filament-access-control-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentAccessControl);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'happenv-com/filament-access-control';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // Built by `npm run build` (bin/build.js) into resources/dist:
            // AlpineComponent::make('filament-access-control', __DIR__ . '/../resources/dist/components/filament-access-control.js'),
            // Css::make('filament-access-control-styles', __DIR__ . '/../resources/dist/filament-access-control.css'),
            // Js::make('filament-access-control-scripts', __DIR__ . '/../resources/dist/filament-access-control.js'),
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentAccessControlCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_filament_access_control_table',
        ];
    }
}
