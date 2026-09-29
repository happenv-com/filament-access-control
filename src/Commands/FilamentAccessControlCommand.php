<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Commands;

use Illuminate\Console\Command;

class FilamentAccessControlCommand extends Command
{
    public $signature = 'filament-access-control';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
