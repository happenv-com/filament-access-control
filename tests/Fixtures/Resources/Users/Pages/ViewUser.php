<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\Pages;

use Filament\Resources\Pages\ViewRecord;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\UserResource;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;
}
