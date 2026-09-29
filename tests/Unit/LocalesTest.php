<?php

declare(strict_types=1);

/*
 * Every locale Filament ships, this package ships too — a panel switched to a language Filament
 * speaks must not fall back to English on these screens alone. What each locale contains is
 * TranslationsTest's job; this one only asks that none is missing.
 */

it('ships every locale Filament ships', function (): void {
    $locales = static fn (string $directory): array => array_map(basename(...), glob("{$directory}/*", GLOB_ONLYDIR) ?: []);

    $filament = $locales(dirname(__DIR__, 2) . '/vendor/filament/filament/resources/lang');
    $package = $locales(dirname(__DIR__, 2) . '/resources/lang');

    expect($filament)->not->toBeEmpty()
        ->and(array_values(array_diff($filament, $package)))->toBe([]);
});
