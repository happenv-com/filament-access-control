# Upgrading

Every major version gets a section here — and a minor one that changes a default: what breaks, and what to change in an application to upgrade. Newest first.

## From 3.3 to 3.4

3.4 switches on two guards (see the README's *Who may change what*). Nothing in your code has to change for the package to work, but what an operator may do on the permission screens does:

- **Escalation guard.** An operator grants and revokes only the permissions they hold in effect. A cell outside that set is switched off with a tooltip, a refused click or save says why, and `PermissionSelector` reports the slugs it refuses as a validation error. An operator holding the super-admin role is not narrowed.
- **Self-editing guard.** An operator no longer changes the permissions of a role they hold, nor their own direct permissions.
- The operator must be `AuthControllable` (laravel-access-control's `HasPermissions` / `HasRoles`) for the escalation guard to know what they hold; otherwise they may change nothing. Give your user model the contract, or tell the plugin what each operator may hand out with `grantableBy()`.
- A deferred screen's **Save permissions** discards — with a notification — the staged changes it may not make, instead of keeping them staged; a save left with nothing to write no longer reports one.
- A subject's click (grant or clear a whole resource) acts on the permissions the operator may change, and decides between granting and clearing by those alone.
- `PermissionMatrix::make()` without a component draws the plugin's `matrixComponent()` — `Livewire\RolePermissionMatrix` unless you name another.

To keep 3.3's behaviour:

```php
FilamentAccessControlPlugin::make()
    ->preventEscalation(false)
    ->preventSelfEditing(false);
```

If a test of yours signs in an operator who hands out permissions they do not hold, give the operator those permissions (or a super-admin role), or switch the guard off in that test: `FilamentAccessControlPlugin::get()->preventEscalation(false)`.

## From 2.x to 3.0

3.0 follows laravel-access-control 3 — rules between permissions and conditions on accounts — and needs its 3.1:

```bash
composer require happenv-com/filament-access-control:^3.0 happenv-com/laravel-access-control:^3.1
```

Nothing in your code has to change. What looks different:

- A role's permission cells show what the rules make of its grants — new icons for *implied*, *missing requirement*, *blocked*, *restricted* (see the README's *Rules and conditions*). Without rules or conditions every cell looks as before.
- A staged (unsaved) cell is `primary`; it was `warning`, which now means a missing requirement.
- A restricted permission's marker next to its name is a grey lock; it was a red no-entry sign, which now means a lost conflict.
- The user editor has an *In effect* column, and a callout when the account fails a condition.
- A *Dependencies* column appears once any permission declares a rule or a condition; declaration problems are listed above the screens (`->declarationProblems(false)` hides them).
- With `->counters()`, the numbers moved from badges in the group header to a first row per group, one number per role column.

If a test of yours asserts a role cell's state, `granted` is now `effective` and `revoked` is `not-granted`; a user's direct column keeps `granted` / `revoked`.

Roles using `HasPermissions` should also implement laravel-access-control's `HoldsGrants`, so the *In effect* column can tell a permission a role implies from one it stores directly.
