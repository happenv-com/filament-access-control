<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;

it('registers the plugin on the panel', function (): void {
    expect(Filament::getPanel('admin')->getPlugin((new FilamentAccessControlPlugin)->getId()))
        ->toBeInstanceOf(FilamentAccessControlPlugin::class);
});

it('serves the panel login page', function (): void {
    $this->get('/admin/login')->assertOk();
});
