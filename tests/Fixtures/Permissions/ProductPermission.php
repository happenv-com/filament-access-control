<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\AvailableFor;
use Happenv\LaravelAccessControl\Attributes\PermissionDescription;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Products')]
#[PermissionDescription('What is sold')]
enum ProductPermission: string implements PermissionDefinition
{
    #[PermissionName('View products')]
    #[AvailableFor(Surface::Api)]
    case View = 'catalogue.product.view';

    #[PermissionName('Create products')]
    #[AvailableFor(Surface::Api)]
    case Create = 'catalogue.product.create';

    #[PermissionName('Update products')]
    #[PermissionDescription('Change names, prices and stock')]
    case Update = 'catalogue.product.update';

    #[PermissionName('Delete products')]
    case Delete = 'catalogue.product.delete';
}
