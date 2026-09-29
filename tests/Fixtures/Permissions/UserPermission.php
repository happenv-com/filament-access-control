<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

#[PermissionGroup(AdministrationGroup::class)]
#[PermissionName('Users')]
enum UserPermission: string implements PermissionDefinition
{
    #[PermissionName('View users')]
    case View = 'administration.user.view';

    #[PermissionName('Update users')]
    case Update = 'administration.user.update';
}
