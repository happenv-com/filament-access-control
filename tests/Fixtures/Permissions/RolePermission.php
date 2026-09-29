<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

#[PermissionGroup(AdministrationGroup::class)]
#[PermissionName('Roles')]
enum RolePermission: string implements PermissionDefinition
{
    #[PermissionName('View roles')]
    case View = 'administration.role.view';

    #[PermissionName('Create roles')]
    case Create = 'administration.role.create';

    #[PermissionName('Update roles')]
    case Update = 'administration.role.update';

    #[PermissionName('Delete roles')]
    case Delete = 'administration.role.delete';
}
