<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Conditions;

use Attribute;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * A condition that does not describe itself — the screens name it after its class.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class RequiresOfficeHours implements PermissionCondition
{
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        return true;
    }
}
