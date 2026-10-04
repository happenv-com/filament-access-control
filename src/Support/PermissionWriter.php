<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Events\PermissionsUpdated;
use Happenv\FilamentAccessControl\Exceptions\PermissionWriteRefused;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Applies grants and revocations to one record's permission list.
 *
 * The whole list lives in one column, so every change is a read-modify-write of all of it. The
 * read happens again here, under a row lock, rather than trusting the record the screen loaded:
 * two operators changing different permissions of the same role would otherwise each write a list
 * built from a stale read, and the second save would silently undo the first.
 *
 * What is written is the operator's INTENT — these slugs granted, those revoked — replayed onto
 * the current list. A grant of something already held and a revocation of something already gone
 * are no-ops rather than conflicts.
 *
 * Resolved from the container: an application that must refuse some lists binds a subclass and
 * overrides {@see self::ensureMayWrite()}, which sees the locked record and the list about to be
 * written.
 */
class PermissionWriter
{
    /**
     * @param  Model&HasEditablePermissions  $record
     * @param  iterable<int,string>  $grant
     * @param  iterable<int,string>  $revoke
     * @return Model&HasEditablePermissions the record as written
     *
     * @throws PermissionWriteRefused when {@see self::ensureMayWrite()} refuses — nothing is written
     */
    public function write(Model $record, iterable $grant = [], iterable $revoke = []): Model
    {
        $grant = (new Collection($grant))->unique()->values();
        $revoke = (new Collection($revoke))->unique()->diff($grant)->values();

        /** @var array{0: Model&HasEditablePermissions, 1: Collection<int,string>, 2: Collection<int,string>} $result */
        $result = $record->getConnection()->transaction(function () use ($record, $grant, $revoke): array {
            /** @var Model&HasEditablePermissions $locked */
            $locked = $record->newQueryWithoutScopes()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $held = $locked->getPermissions();

            $granted = $grant->diff($held)->values();
            $revoked = $revoke->intersect($held)->values();

            if ($granted->isNotEmpty() || $revoked->isNotEmpty()) {
                $resulting = $held->reject(fn (string $slug): bool => $revoked->contains($slug))
                    ->concat($granted)
                    ->values();

                $this->ensureMayWrite($locked, $resulting, $granted, $revoked);

                $locked->setPermissions($resulting);
            }

            return [$locked, $granted, $revoked];
        });

        [$written, $granted, $revoked] = $result;

        if ($granted->isNotEmpty() || $revoked->isNotEmpty()) {
            event(new PermissionsUpdated($written, $granted->all(), $revoked->all()));
        }

        return $written;
    }

    /**
     * Called under the row lock, just before a list that changes is written — throw
     * {@see PermissionWriteRefused} to write nothing. Nothing is refused by default.
     *
     * @param  Model&HasEditablePermissions  $locked  the record as it is now, locked
     * @param  Collection<int, string>  $resulting  the whole list about to be written
     * @param  Collection<int, string>  $granted  what it adds
     * @param  Collection<int, string>  $revoked  what it takes away
     *
     * @throws PermissionWriteRefused
     */
    protected function ensureMayWrite(Model $locked, Collection $resulting, Collection $granted, Collection $revoked): void
    {
        //
    }
}
