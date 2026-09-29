<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\LaravelAccessControl\Attributes\ConflictsWith;
use Happenv\LaravelAccessControl\Attributes\ImpliedBy;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Attributes\Requires;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Every rule, and a condition, against the products. Registered only by the tests that need it
 * ({@see registerPermissions()}), so every other test's catalogue stays as it was.
 */
#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Gallery')]
enum GalleryPermission: string implements PermissionDefinition
{
    #[PermissionName('View gallery')]
    #[Requires(ProductPermission::View, reason: 'The gallery shows products.')]
    case View = 'catalogue.gallery.view';

    #[PermissionName('Manage gallery')]
    #[ImpliedBy(ProductPermission::Update)]
    case Manage = 'catalogue.gallery.manage';

    #[PermissionName('Archive gallery')]
    #[ConflictsWith(ProductPermission::Delete)]
    case Archive = 'catalogue.gallery.archive';

    #[PermissionName('Publish gallery')]
    #[RequiresMFA]
    case Publish = 'catalogue.gallery.publish';
}
