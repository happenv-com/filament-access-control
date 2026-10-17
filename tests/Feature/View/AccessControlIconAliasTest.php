<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Support\PermissionCellState;
use Happenv\FilamentAccessControl\View\AccessControlIconAlias;

covers(AccessControlIconAlias::class);

afterEach(function (): void {
    FilamentIcon::register([
        AccessControlIconAlias::CELL_RESTRICTED => null,
        AccessControlIconAlias::NAVIGATION => null,
    ]);
});

it('keeps the Heroicon default when the host registers no alias', function (): void {
    expect(PermissionCellState::Restricted->icon())->toBe(Heroicon::LockClosed)
        ->and(FilamentAccessControlPlugin::make()->getNavigationIcon())->toBe(Heroicon::OutlinedShieldCheck);
});

it('lets an alias registered by the host win over the default cell icon', function (): void {
    FilamentIcon::register([AccessControlIconAlias::CELL_RESTRICTED => 'host-lock']);

    expect(PermissionCellState::Restricted->icon())->toBe('host-lock')
        ->and(PermissionCellState::Effective->icon())->toBe(Heroicon::CheckCircle);
});

it('resolves the navigation icon through its alias, but lets the plugin setter win', function (): void {
    FilamentIcon::register([AccessControlIconAlias::NAVIGATION => 'host-shield']);

    expect(FilamentAccessControlPlugin::make()->getNavigationIcon())->toBe('host-shield')
        ->and(FilamentAccessControlPlugin::make()->navigationIcon('heroicon-o-key')->getNavigationIcon())->toBe('heroicon-o-key');
});
