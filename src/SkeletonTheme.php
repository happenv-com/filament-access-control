<?php

declare(strict_types=1);

namespace VendorName\Skeleton;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Assets\Theme;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentAsset;

class Skeleton implements Plugin
{
    public function getId(): string
    {
        return 'skeleton';
    }

    public function register(Panel $panel): void
    {
        FilamentAsset::register([
            Theme::make('skeleton', __DIR__ . '/../resources/dist/skeleton.css'),
        ]);

        $panel
            ->font('DM Sans')
            ->colors([
                'primary' => Color::Amber,
                'gray' => Color::Gray,
                'danger' => Color::Rose,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->theme('skeleton');
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
