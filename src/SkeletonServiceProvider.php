<?php

declare(strict_types=1);

namespace VendorName\Skeleton;

// @filament-start
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
// @filament-end
use Illuminate\Filesystem\Filesystem;
// @filament-start
use Livewire\Features\SupportTesting\Testable;
// @filament-end
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use VendorName\Skeleton\Commands\SkeletonCommand;
// @filament-start
use VendorName\Skeleton\Testing\TestsSkeleton;
// @filament-end

class SkeletonServiceProvider extends PackageServiceProvider
{
    public static string $name = 'skeleton';

    public static string $viewNamespace = 'skeleton';

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
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub(':github_org/:package_slug');
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
        // @filament-start
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

        // @filament-end
        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/skeleton/{$file->getFilename()}"),
                ], 'skeleton-stubs');
            }
        }
        // @filament-start

        // Testing
        Testable::mixin(new TestsSkeleton);
        // @filament-end
    }
    // @filament-start

    protected function getAssetPackageName(): ?string
    {
        return ':vendor_slug/:package_slug';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // Built by `npm run build` (bin/build.js) into resources/dist:
            // AlpineComponent::make('skeleton', __DIR__ . '/../resources/dist/components/skeleton.js'),
            // Css::make('skeleton-styles', __DIR__ . '/../resources/dist/skeleton.css'),
            // Js::make('skeleton-scripts', __DIR__ . '/../resources/dist/skeleton.js'),
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
    // @filament-end

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            SkeletonCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_migration_table_name_table',
        ];
    }
}
