<?php

declare(strict_types=1);

arch()->preset()->php();

arch()->preset()->security()->ignoring('Happenv\LaravelAccessControl');

arch('no debugging calls')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();
