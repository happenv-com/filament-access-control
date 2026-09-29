<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\FilamentAccessControl\Contracts\OffersEveryPermission;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;

enum Surface: string implements OffersEveryPermission, PermissionSurfaceDefinition
{
    case Panel = 'panel';
    case Api = 'api';

    public function offersEverything(): bool
    {
        return $this === self::Panel;
    }
}
