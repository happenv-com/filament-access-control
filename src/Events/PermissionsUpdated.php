<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Events;

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * A record's permission list changed through one of this package's screens.
 *
 * Only what actually changed: a grant of something already held is not in `$granted`, and nothing
 * is dispatched for a save that changed nothing. Dispatched after the transaction committed.
 */
class PermissionsUpdated
{
    use Dispatchable;

    /**
     * @param  Model&HasEditablePermissions  $record
     * @param  list<string>  $granted
     * @param  list<string>  $revoked
     */
    public function __construct(
        public readonly Model $record,
        public readonly array $granted,
        public readonly array $revoked,
    ) {}
}
