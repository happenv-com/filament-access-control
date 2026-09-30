<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;

covers(PermissionMatrix::class);

it('hands the matrix its configuration and no record', function (): void {
    $matrix = PermissionMatrix::make()
        ->deferred()
        ->counters()
        ->rolesShownByDefault(8)
        ->surface(Surface::Api)
        ->data(['extra' => 1]);

    expect($matrix->getComponent())->toBe(RolePermissionMatrix::class)
        ->and($matrix->getComponentProperties())->toBe([
            'deferred' => true,
            'counters' => true,
            'rolesShownByDefault' => 8,
            'surface' => Surface::Api,
            'extra' => 1,
        ])
        ->and(PermissionMatrix::make()->lazy()->getComponentProperties())->toBe([
            'deferred' => null,
            'counters' => null,
            'rolesShownByDefault' => null,
            'surface' => null,
            'lazy' => true,
        ]);
});
