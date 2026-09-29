<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Contracts\PermissionGroupDefinition;

final class AdministrationGroup implements PermissionGroupDefinition
{
    public function getName(): string
    {
        return 'Administration';
    }

    public function getDescription(): ?string
    {
        return null;
    }

    public function getSlug(): string
    {
        return 'administration';
    }
}
