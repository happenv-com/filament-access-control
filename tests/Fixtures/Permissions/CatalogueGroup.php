<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Contracts\PermissionGroupDefinition;

final class CatalogueGroup implements PermissionGroupDefinition
{
    public function getName(): string
    {
        return 'Catalogue';
    }

    public function getDescription(): string
    {
        return 'What the shop sells';
    }

    public function getSlug(): string
    {
        return 'catalogue';
    }
}
