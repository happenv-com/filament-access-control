<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\ConflictsWith;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Attributes\Requires;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Declarations that can never work — registered only by the tests about them.
 */
#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Broken')]
enum BrokenPermission: string implements PermissionDefinition
{
    #[PermissionName('Merge everything')]
    #[Requires(self::Split)]
    #[ConflictsWith(self::Split)]
    case Merge = 'catalogue.broken.merge';

    #[PermissionName('Split everything')]
    case Split = 'catalogue.broken.split';

    #[PermissionName('Adopt orphans')]
    #[Requires(UnregisteredPermission::Orphan)]
    case Adopt = 'catalogue.broken.adopt';
}
