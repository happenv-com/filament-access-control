<?php

declare(strict_types=1);

namespace VendorName\Skeleton\Tests;

// @filament-start
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
// @filament-end
use ErrorException;
// @filament-start
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
// @filament-end
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
// @filament-start
use Livewire\LivewireServiceProvider;
// @filament-end
use Orchestra\Testbench\Concerns\WithLaravelMigrations;
use Orchestra\Testbench\TestCase as Orchestra;
// @filament-start
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use VendorName\Skeleton\Tests\Fixtures\AdminPanelProvider;
// @filament-end
use VendorName\Skeleton\SkeletonServiceProvider;
use VendorName\Skeleton\Tests\Fixtures\User;

abstract class TestCase extends Orchestra
{
    use RefreshDatabase;

    // The `users` table for Fixtures\User.
    use WithLaravelMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName): string => 'VendorName\\Skeleton\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );

        // Laravel only logs deprecations. Fail the test when the package's OWN
        // code triggers one, so it is fixed before the next PHP / Laravel /
        // Filament release turns it into an error.
        $sourcePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;

        $previousHandler = set_error_handler(function (int $level, string $message, string $file = '', int $line = 0) use (&$previousHandler, $sourcePath): bool {
            if (in_array($level, [E_DEPRECATED, E_USER_DEPRECATED], true) && str_starts_with($file, $sourcePath)) {
                throw new ErrorException($message, 0, $level, $file, $line);
            }

            // Laravel's handler returns nothing once it has logged a deprecation;
            // only an explicit `false` hands the error back to PHP, which would
            // print it and make the test risky.
            return $previousHandler !== null && $previousHandler($level, $message, $file, $line) !== false;
        });
    }

    protected function tearDown(): void
    {
        restore_error_handler();

        parent::tearDown();
    }

    /**
     * @return list<class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            // @filament-start
            ActionsServiceProvider::class,
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            // After Filament, as package discovery orders them in an app
            // (alphabetically: filament/* before livewire/livewire). Filament
            // before 4.13.3 / 5.8.3 re-binds Livewire's DataStore as non-shared
            // when it registers AFTER Livewire (filamentphp/filament#20515).
            LivewireServiceProvider::class,
            // @filament-end
            SkeletonServiceProvider::class,
            // @filament-start
            AdminPanelProvider::class,
            // @filament-end
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:' . base64_encode(random_bytes(32)));
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Fixtures/database/migrations');
    }
}
