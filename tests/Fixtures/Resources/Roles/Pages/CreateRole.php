<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\Pages;

use Filament\Resources\Pages\CreateRecord;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\RoleResource;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;
}
