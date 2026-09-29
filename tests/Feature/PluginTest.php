<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use VendorName\Skeleton\SkeletonPlugin;

it('registers the plugin on the panel', function (): void {
    expect(Filament::getPanel('admin')->getPlugin((new SkeletonPlugin)->getId()))
        ->toBeInstanceOf(SkeletonPlugin::class);
});

it('serves the panel login page', function (): void {
    $this->get('/admin/login')->assertOk();
});
