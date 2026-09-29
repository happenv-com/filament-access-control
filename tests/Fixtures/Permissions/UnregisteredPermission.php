<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Registered by nobody — a rule may still point at it.
 */
enum UnregisteredPermission: string implements PermissionDefinition
{
    case Orphan = 'nowhere.orphan';
}
