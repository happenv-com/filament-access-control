# Upgrading

Every major version gets a section here: what breaks, and what to change in an application to upgrade. Newest first.

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
