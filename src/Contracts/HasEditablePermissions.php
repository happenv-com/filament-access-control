<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Contracts;

use Illuminate\Support\Collection;

/**
 * A record whose permission slugs this package may read and rewrite — a role, or a user holding
 * permissions directly.
 *
 * The same two methods `HasPermissions` from laravel-access-control asks for, made PUBLIC: the
 * trait declares them protected, and a screen that edits the list has to reach it from outside.
 * A model that already declares them public only has to name this interface.
 *
 * `setPermissions()` is expected to persist. The editor reads the record again under a row lock,
 * hands it the new list and saves nothing itself, so a model that only assigns the attribute
 * would lose every change the screen reports as saved.
 */
interface HasEditablePermissions
{
    /**
     * @return Collection<int,string>
     */
    public function getPermissions(): Collection;

    /**
     * @param  Collection<int,string>  $permissions
     */
    public function setPermissions(Collection $permissions): void;
}
