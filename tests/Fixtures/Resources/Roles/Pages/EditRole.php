<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\Pages;

use Filament\Resources\Pages\EditRecord;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\RoleResource;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;
}
