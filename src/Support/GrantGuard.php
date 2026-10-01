<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Facades\AccessControl;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Who may hand out what, on every screen of the plugin.
 *
 * Two questions, both asked on the server at every write — a disabled cell is a hint, never the guard:
 *
 *   - ESCALATION: an operator grants and revokes only what they hold in effect themselves — what
 *     laravel-access-control lets through for them, implied permissions included, restrictions and
 *     conditions applied. Revoking counts too: taking a permission away from a role is as much a
 *     decision about that permission as handing it out. An operator holding the super-admin role is
 *     not narrowed; `grantableBy()` replaces the set for everybody else.
 *   - SELF-EDITING: whether the holder whose permissions change is the operator, or a role the
 *     operator holds. Answered as a fact; the screens ask the plugin whether it matters.
 *
 * The escalation set fails closed: nobody signed in, or an operator the library cannot ask (not
 * `AuthControllable`), may change nothing while the guard is on. The self-editing question does not:
 * for an operator that is not an Eloquent model `isOwnHolder()` answers false, as it cannot compare.
 */
class GrantGuard
{
    /**
     * What the operator may grant and revoke — `null` for anything.
     *
     * @return Collection<int, string>|null
     */
    public function grantableSlugs(?Authenticatable $operator): ?Collection
    {
        $plugin = $this->plugin();

        if (! $plugin->isEscalationPrevented()) {
            return null;
        }

        if (! $operator instanceof Authenticatable) {
            return new Collection;
        }

        if ($this->holdsSuperAdminRole($operator)) {
            return null;
        }

        if ($plugin->hasGrantableBy()) {
            return $plugin->evaluateGrantableBy($operator);
        }

        if (! $operator instanceof AuthControllable) {
            return new Collection;
        }

        return AccessControl::effectivePermissions($operator)
            ->map(fn (PermissionDefinition $permission): string => (string) $permission->value)
            ->values();
    }

    public function mayChange(?Authenticatable $operator, string $slug): bool
    {
        $grantable = $this->grantableSlugs($operator);

        return ! $grantable instanceof Collection || $grantable->contains($slug);
    }

    /**
     * Whether the holder IS the operator, or is a role the operator holds.
     */
    public function isOwnHolder(?Authenticatable $operator, Model $holder): bool
    {
        if (! $operator instanceof Model) {
            return false;
        }

        if ($holder->is($operator)) {
            return true;
        }

        foreach ($this->rolesOf($operator) as $role) {
            if ($role instanceof Model && $holder->is($role)) {
                return true;
            }
        }

        return false;
    }

    protected function holdsSuperAdminRole(Authenticatable $operator): bool
    {
        foreach ($this->rolesOf($operator) as $role) {
            if ($role instanceof Model && $this->plugin()->isSuperAdminRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The roles the operator holds, read as the screens read a user's roles: through `getRoles()`,
     * which laravel-access-control's `HasRoles` asks the model for.
     *
     * @return iterable<mixed>
     */
    protected function rolesOf(Authenticatable $operator): iterable
    {
        return method_exists($operator, 'getRoles') ? $operator->getRoles() : [];
    }

    protected function plugin(): FilamentAccessControlPlugin
    {
        return FilamentAccessControlPlugin::current();
    }
}
