<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector;
use RectorLaravel\Set\LaravelLevelSetList;

/*
 * Library, not an application: no privatization and no "treat classes as
 * final" here. Apps extend a plugin's classes and override its protected
 * methods; Rector cannot see those subclasses, so narrowing visibility or
 * finalizing classes would break them without a failing test in this repository.
 */
return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // The LOWEST Laravel the package supports, not the installed one:
    // `withComposerBased(laravel: true)` would follow the newest Laravel that
    // `composer update` resolves and rewrite code into forms (e.g. Laravel 13
    // Eloquent attributes) that break the older versions CI still tests.
    // Raise it when the package drops a Laravel version.
    ->withSets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
    ])
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        earlyReturn: true,
    )
    ->withPhpSets()
    ->withSkip([
        // Trusts the Filament facade's `@method` docblock, which shows `setCurrentPanel(Panel|string|null
        // $panel = null)` — but FilamentManager::setCurrentPanel() has no default; removing the explicit
        // `null` argument here throws ArgumentCountError at runtime.
        RemoveNullArgOnNullDefaultParamRector::class => [__DIR__ . '/tests/Feature/Attributes/RequiresMFATest.php'],
    ]);
