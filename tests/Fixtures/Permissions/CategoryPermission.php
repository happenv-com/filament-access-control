<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Categories')]
enum CategoryPermission: string implements PermissionDefinition
{
    #[PermissionName('View categories')]
    case View = 'catalogue.category.view';

    #[PermissionName('Update categories')]
    case Update = 'catalogue.category.update';

    // No translation of its own: the verb falls back to the case name.
    #[PermissionName('Merge categories')]
    case MergeDuplicates = 'catalogue.category.merge-duplicates';
}
