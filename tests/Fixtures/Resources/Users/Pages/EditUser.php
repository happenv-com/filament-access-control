<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\Pages;

use Filament\Resources\Pages\EditRecord;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\UserResource;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;
}
