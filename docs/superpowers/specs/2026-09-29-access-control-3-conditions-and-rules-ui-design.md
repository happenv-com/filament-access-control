# filament-access-control 3.x and laravel-access-control 3.1 — design

- **Date:** 2026-09-29
- **Status:** approved in brainstorming, awaiting written-spec review
- **Repositories:** `happenv-com/laravel-access-control` (library), `happenv-com/filament-access-control` (UI)

## Goal

1. Let a permission be switched off **per account** by a condition declared as an attribute — the
   package's own `#[RequiresMFA]` and any attribute an application writes itself.
2. Bring everything laravel-access-control 3.x adds to the permission screens: rules between
   permissions (`Requires`, `ImpliedBy`, `ConflictsWith`), effective state, declaration problems
   and permission graphs — shown so an operator always sees **why** a permission is or is not in
   effect.

## Decisions taken in brainstorming

| Question | Decision |
|---|---|
| Where conditions are enforced | Everywhere the library answers for an **account**: the Gate, `hasPermissionTo()` of accounts, `effectivePermissions()`. Never for a role (a grant container). Always the last step, like restrictions. |
| Shape of the contract | An interface implemented by the attribute class itself (`PermissionCondition::check(): bool`), plus an optional interface describing it for UIs. |
| `#[RequiresMFA]` on accounts that cannot have MFA | Fail closed: no MFA enabled with any of the panel's providers means no permission — machine accounts (API keys) and panels without MFA included. |
| Permission graphs in the UI | Yes: a Mermaid diagram in a modal, mermaid.js lazy-loaded from the package's own asset, switchable off. |
| Rules and problems in the UI | Every rule (both directions) and every `problems()` entry is shown explicitly. |

There is no attribute contract in laravel-access-control today: every attribute is a `final
readonly` class, read by its concrete class name (`getAttributes(Requires::class)`), and the only
"withhold" hook — `restrictUsing()` — is global and never sees the account.

## Part 1 — laravel-access-control 3.1.0

Branch `feat/permission-conditions` from `3.x`, released as `v3.1.0`. Additive only.

### Contract

```php
namespace Happenv\LaravelAccessControl\Contracts;

interface PermissionCondition
{
    /** True when the account satisfies the condition for this permission. */
    public function check(PermissionDefinition $permission, Authenticatable|AuthControllable $principal): bool;
}

interface DescribesPermissionCondition
{
    /** A short, translated label for UIs and diagrams, e.g. "Requires MFA". */
    public function describe(): string;
}
```

An application's attribute:

```php
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
final readonly class RequiresVerifiedEmail implements PermissionCondition, DescribesPermissionCondition
{
    public function check(PermissionDefinition $permission, Authenticatable|AuthControllable $principal): bool
    {
        return $principal instanceof MustVerifyEmail && $principal->hasVerifiedEmail();
    }

    public function describe(): string
    {
        return __('permissions.conditions.verified_email');
    }
}
```

### Discovery — `PermissionConditions` (singleton)

- Reads `getAttributes(PermissionCondition::class, ReflectionAttribute::IS_INSTANCEOF)` on the enum
  class (applies to every case) and on the case constant (adds to the class's). All must pass.
- The attribute **instances** per permission are memoised for the life of the process — code does
  not change at runtime, so this is safe under Octane. `check()` is called on every evaluation;
  results are never cached.
- `for(PermissionDefinition $permission): list<PermissionCondition>` and
  `unmet(PermissionDefinition $permission, Authenticatable|AuthControllable $principal): list<PermissionCondition>`.

### Enforcement — the last step

Order for an account: restrictions → rules → **conditions**. A condition, like a restriction, does
not propagate through rules: a condition on `Update` withholds `Update`, not what `Update` implies,
and a permission requiring `Update` is still judged on the stored grants.

| Place | Behaviour |
|---|---|
| `GateConfigurator` | After `hasPermissionTo()` allows, refuse when a condition of the permission is unmet for `$user`. Covers accounts with their own `hasPermissionTo()` (an administrator short-circuit). |
| `HasRoles::hasPermissionTo()`, `HasRolesAndPermissions::hasPermissionTo()` | `$this` is the account: refuse when a condition is unmet. Checked before the per-instance memo, like restrictions, so it is never remembered. |
| `HasPermissions::hasPermissionTo()` | Only when `$this instanceof Authenticatable` — a role is a grant container and is skipped; an API key that is a user is not. |
| `AccessControl::effectivePermissions()` | Excludes permissions with an unmet condition, also for accounts answering `hasPermissionTo()` themselves. |

A condition may therefore run twice in one Gate check; it must be cheap and free of side effects
(documented).

**Refusal message:** the same as a missing permission (`Unauthorized.` / `Unauthorized for <slug>`),
as for restrictions — the message is a contract consumers match on.

### Additions for UIs

- `PermissionDto::$conditions` — `list<PermissionCondition>` (instances).
- `AccessControl::unmetConditions(PermissionDefinition $permission, Authenticatable|AuthControllable $principal): list<PermissionCondition>`.
- `PermissionGraph::problemDetails(): list<PermissionProblemDto>` with `type`
  (`PermissionProblemType::RequiresConflicting`, `ImpliesConflicting`, `UnregisteredTarget`),
  `permission`, `other`, `ruleType`. `problems()` keeps returning the same sentences, built from these.
- `PermissionResolver::explainer(Closure $stored): Closure(PermissionDefinition): PermissionResolutionDto`
  — one evaluation answering many permissions (today every `explain()` builds a new one; a matrix
  asks permissions × roles times).
- Principal diagrams: new state `unmet-condition` (between `restricted` and `denied`); the JSON
  schema moves to version 2 because a new state value appears.

### Documentation and tests

- README: a section "Conditions: your own attributes" with the example above, the enforcement
  table, the note on cost and side effects, and the role/account distinction.
- Pest: condition on class and on case; conditions summed; Gate, each of the three traits, a role
  skipped, an Authenticatable using `HasPermissions` not skipped; `effectivePermissions()`;
  restrictions plus rules plus conditions together; an account with its own `hasPermissionTo()`;
  diagram state; `problemDetails()` equivalent to `problems()`; `explainer()` equivalent to
  `explain()`.

## Part 2 — filament-access-control 3.x: core

### Branches and versions

- `3.x` created from `2.x`; work on `feat/access-control-3`, pull request into `3.x`.
- Branch alias `dev-3.x: 3.x-dev`; `happenv-com/laravel-access-control: ^3.1` (a `path`
  repository during development, the constraint before merge); Filament `^4.13.3 || ^5.8.3` unchanged.
- `v3.0.0` after the library's `v3.1.0`. `UPGRADING.md` describes 2 → 3.

### `#[RequiresMFA]`

`Happenv\FilamentAccessControl\Attributes\RequiresMFA`, `TARGET_CLASS | TARGET_CLASS_CONSTANT`,
implements `PermissionCondition` and `DescribesPermissionCondition` ("Requires MFA", translated in
every shipped locale).

- `check()`: `true` only when the principal is `Authenticatable` and at least one multi-factor
  provider of the panel reports `isEnabled($principal)`.
- The panel: the one named by the optional `panel:` argument, otherwise the current panel,
  otherwise the default panel.
- Fail closed: no panel, a panel without providers, or a principal that is not `Authenticatable`
  → `false`.

### Cell state — `PermissionStates`

A service producing a `PermissionCellState` per (holder, permission):

| Field | Source |
|---|---|
| `stored` | the holder's stored grants **with staged changes applied** |
| `granted`, `allowed`, `impliedBy[]`, `missing[]`, `conflicting[]` | `PermissionResolver::explainer()` over those stored grants |
| `restricted` | `AccessControl::isRestricted()` |
| `unmetConditions[]` | `AccessControl::unmetConditions()` — accounts only |

- A **role's** state resolves the rules within that role alone.
- An **account's** effective state resolves them over direct grants plus role grants: roles
  implementing `HoldsGrants` are read as stored; other roles through `hasPermissionTo()` — as the
  library does.
- One explainer per holder per request.

### Reading and writing

Screens read and write **stored** grants only (`getPermissions()`), as the library's README warns.
Clicking an implied permission grants it explicitly; clicking a stored but inactive one
(missing requirement, conflict) revokes it. The writer is unchanged.

### `PermissionTree`

Rules from `PermissionDto::$rules` (both ends of every rule), conditions from
`PermissionDto::$conditions`. Search also matches the names of related permissions.

### Plugin options (new)

- `->diagrams(bool|Closure)` — default `true`.
- `->declarationProblems(bool|Closure)` — show `problems()`; default `true`.

## Part 3 — filament-access-control 3.x: UI

Filament components only (badges, icons, tooltips, callouts, modal); no new custom styles.

### "Dependencies" column

Next to the permission name, in the matrix and in the editor. One badge per rule, both directions,
the `reason` as tooltip:

| Badge | Colour |
|---|---|
| Requires: *Products → View* / Required by: … | gray |
| Implied by: … / Implies: … | info |
| Blocked by: … / Blocks: … | danger |
| each condition's `describe()` (e.g. Requires MFA) | warning |
| Invalid declaration (the row appears in `problemDetails()`) | danger |

### Cell states

The icon's shape says **why**; the tooltip names the permissions involved. When several reasons
apply, the icon shows the first in this order and the tooltip lists all.

| State | Icon | Colour | Tooltip |
|---|---|---|---|
| stored and in effect | check-circle | success | — |
| in effect, implied | check-circle | info | Implied by: X (click grants it explicitly) |
| stored, requirement missing | exclamation-triangle | warning | Missing requirement: X |
| stored, loses a conflict | no-symbol | danger | Blocked by: Y |
| stored, restricted at runtime | lock-closed | gray | Restricted by the application |
| stored, condition unmet (accounts) | shield-exclamation | warning | Requires MFA — this account has none |
| not granted | x-circle | danger | — |

A staged cell (deferred mode) takes the `primary` colour — `warning` now means a missing
requirement — and its state is computed with the staged changes, so the consequence of a change is
visible before it is saved. Subject rows keep all / some / none.

### User editor

Columns: *Granted* (direct, toggleable) | *From roles* | **In effect** (roles, rules, restrictions
and conditions together, same icons and tooltips). A callout above the table when the account
fails a condition of a permission it holds, e.g. "Enable MFA to use 3 of your permissions".

### Declaration problems

A danger callout on the access control page and above the editors, listing
`problemDetails()` as translated sentences, e.g. "Merge categories can never be allowed: it
requires Categories → View, which it conflicts with." Shown whenever there are problems; the
affected rows carry the "Invalid declaration" badge.

### Permission graph

An action "Permission graph": on the access control page (the whole catalogue) and on the editor
(the role or account, when it is `AuthControllable`). A modal renders
`AccessControl::diagram()->render($diagram, 'mermaid')`. mermaid.js is the package's own asset,
built with esbuild into `resources/dist`, registered as a lazy Alpine component and loaded only
when the modal opens. The package regains the skeleton's asset pipeline (`package.json`,
`bin/build.js`, the assets workflow).

### Translations

Every new string in all 64 shipped locales; `TranslationsTest` and `LocalesTest` keep holding them.

## Part 4 — testing, releases, risks

### Tests

- Package, test-first:
  - `PermissionStates`: each of the seven states, with and without staged changes, role versus
    account;
  - `#[RequiresMFA]`: account with MFA, without, panel without providers, non-`Authenticatable`,
    `panel:` argument;
  - Livewire: dependency badges, cell icons and tooltips, "In effect" column, conditions callout,
    problems callout, graph action producing Mermaid text.
- PHPStan and the CI matrix (Filament 4 and 5).
- Visual check in filament-playground switched to 3.x (`path` repositories to both working
  branches), with screenshots for review.

### Releases

1. laravel-access-control: PR into `3.x`, tag `v3.1.0`.
2. filament-access-control: PR `feat/access-control-3` into `3.x` with `^3.1`, tag `v3.0.0`.
3. Sellero stays on 2.x.

### Risks

| Risk | Mitigation |
|---|---|
| mermaid.js adds 2–3 MB to `dist` | Lazy-loaded in the modal only; `->diagrams(false)`. |
| Cost of states in a large matrix | `explainer()`; one evaluation per holder per request. |
| Diagram JSON gains a state value | Schema version 2, noted in the CHANGELOG. |
| A condition doing I/O runs on every check, possibly twice | Documented: conditions must be cheap and free of side effects. |
