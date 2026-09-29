<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\FilamentAccessControlServiceProvider;

it('registers the service provider', function (): void {
    expect(app()->getProviders(FilamentAccessControlServiceProvider::class))->not->toBeEmpty();
});

it('merges the package config', function (): void {
    expect(config('filament-access-control'))->toBeArray();
});
