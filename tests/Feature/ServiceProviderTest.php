<?php

declare(strict_types=1);

use VendorName\Skeleton\SkeletonServiceProvider;

it('registers the service provider', function (): void {
    expect(app()->getProviders(SkeletonServiceProvider::class))->not->toBeEmpty();
});

it('merges the package config', function (): void {
    expect(config(':package_short_name'))->toBeArray();
});
