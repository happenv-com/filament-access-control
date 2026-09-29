<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Happenv\FilamentAccessControl\FilamentAccessControl
 */
class FilamentAccessControl extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Happenv\FilamentAccessControl\FilamentAccessControl::class;
    }
}
