# filament-access-control 3.x and laravel-access-control 3.1 — design

- **Date:** 2026-09-29
- **Status:** approved in brainstorming; revised after an external review (principal semantics,
  invariants, resolution examples, layering)
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
| Where conditions are enforced | Everywhere the library answers for an **account**: the Gate, `hasPermissionTo()`, `effectivePermissions()`. Never for a role. Always the last step, like restrictions. An account is an `Authenticatable` principal — see *Principal semantics*. |
| Shape of the contract | An interface implemented by the attribute class itself (`PermissionCondition::check(): bool`), plus an optional interface describing it for UIs. |
| `#[RequiresMFA]` on accounts that cannot have MFA | Fail closed: no MFA enabled with any of the panel's providers means no permission — machine accounts (API keys) and panels without MFA included. |
| Permission graphs in the UI | Yes: a Mermaid diagram in a modal, mermaid.js lazy-loaded from the package's own asset, switchable off. An enhancement only. |
| Rules and problems in the UI | Every rule (both directions) and every declaration problem is shown explicitly. |

There is no attribute contract in laravel-access-control today: every attribute is a `final
readonly` class, read by its concrete class name (`getAttributes(Requires::class)`), and the only
"withhold" hook — `restrictUsing()` — is global and never sees the account.

## Principal semantics

**A condition applies to a principal if and only if the principal is `Authenticatable`.** One rule,
the same at every entry point; no entry point decides it differently.

| Principal | Typical shape | Conditions? | Why |
|---|---|:---:|---|
| User | `Authenticatable`, `HasRoles` / `HasRolesAndPermissions` | ✓ | an account |
| Machine user (API key) | `Authenticatable`, `HasPermissions` | ✓ | an account; `#[RequiresMFA]` therefore denies it (fail closed) |
| Account with its own `hasPermissionTo()` (administrator short-circuit) | `Authenticatable` | ✓ at the Gate and in `effectivePermissions()`; its own `hasPermissionTo()` answers what it answers | the library cannot intervene inside a method the application wrote — the same as for restrictions today |
| Role | `AuthControllable`, `HasPermissions` or `HoldsGrants`, **not** `Authenticatable` | ✗ | a grant container |
| Non-authenticatable `AuthControllable` holding roles (a team, a tenant) | `HasRoles`, not `Authenticatable` | ✗ | not an account |
| System / internal caller | no user | ✗ | the Gate refuses `null` as `Unauthenticated.` before anything else; internal code calling `hasPermissionTo()` on a non-authenticatable object gets no conditions |

## Invariants

The implementation must keep all of these; each has a test.

1. A condition never changes stored grants.
2. A condition never propagates through rules: a condition on X withholds X only; it does not make
   what X implies, what requires X, or what conflicts with X behave differently.
3. A principal that is not `Authenticatable` is never evaluated against conditions.
4. `effectivePermissions($principal)` never contains a permission with a restriction or an unmet
   condition for that principal.
5. For a principal using the library's traits, `hasPermissionTo($p)` is true exactly when `$p` is
   in `effectivePermissions()`.
6. `getPermissions()` always returns stored grants, whatever the rules, restrictions or conditions.
7. A staged change never touches persisted grants until it is saved.
8. `problemDetails()` and `problems()` describe the same declaration problems, one to one.
9. `explain()` and `explainer()` produce the same resolution for the same stored state.
10. A refusal caused by a condition carries the same message as a missing permission.
11. Diagrams are an enhancement: with `->diagrams(false)`, or with mermaid.js failing to load, the
    matrix, tooltips, rules, problems and conditions all keep working.

## Part 1 — laravel-access-control 3.1.0

Branch `feat/permission-conditions` from `3.x`, released as `v3.1.0`. Additive only.

### Contract

```php
namespace Happenv\LaravelAccessControl\Contracts;

interface PermissionCondition
{
    /** True when the account satisfies the condition for this permission. */
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool;
}

interface DescribesPermissionCondition
{
    /** A short, translated label for UIs and diagrams, e.g. "Requires MFA". */
    public function describe(): string;
}
```

- `$principal` is `Authenticatable` because only accounts are evaluated (*Principal semantics*).
- `$permission` is there **on purpose**: one attribute class may sit on many enums and cases, and a
  condition may depend on which permission it guards — for example one that reads the case's other
  attributes, or a condition that applies only to permissions that write data.
- A condition **must** be free of side effects and idempotent within a request, and **should** avoid
  I/O: it may run on every check, and twice in one Gate check (see *Enforcement*). Results are never
  cached by the library — a cache would outlive a change in the account's state (MFA enabled mid-request).

An application's attribute:

```php
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
final readonly class RequiresVerifiedEmail implements PermissionCondition, DescribesPermissionCondition
{
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
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
  not change at runtime, so this is safe under Octane.
- `for(PermissionDefinition $permission): list<PermissionCondition>`
- `unmet(PermissionDefinition $permission, object $principal): list<PermissionCondition>` — `[]` for
  a principal that is not `Authenticatable`.

### Enforcement — the last step

Order for an account: restrictions → rules → **conditions**.

| Place | Behaviour |
|---|---|
| `GateConfigurator` | After `hasPermissionTo()` allows, refuse when a condition of the permission is unmet for `$user`. This covers accounts with their own `hasPermissionTo()`. |
| `HasPermissions`, `HasRoles`, `HasRolesAndPermissions` — `hasPermissionTo()` | When `$this` is `Authenticatable`, refuse when a condition is unmet. Checked outside the per-instance memo of `HasRoles`, like restrictions, so it is never remembered. |
| `AccessControl::effectivePermissions()` | Excludes restricted permissions and permissions with an unmet condition, also for accounts answering `hasPermissionTo()` themselves — so the list matches what the Gate lets through. (Restricted ones are excluded today only for principals using the traits; for the others this is a narrowing, called out in the CHANGELOG.) |

A condition therefore may run twice in one Gate check (once in the trait, once in the Gate) — hence
the side-effect rule above.

### Resolution for UIs — the domain stays in the library

- `PermissionResolutionDto` gains two fields, with defaults so existing constructions keep working:
  `restricted: bool` and `unmetConditions: list<PermissionCondition>`. `allowed` keeps its meaning
  ("effective by the rules"); a new `effective` field is `allowed && ! restricted && unmetConditions === []`.
- `PermissionResolver::explainer(Closure $stored, ?Authenticatable $principal = null): Closure(PermissionDefinition): PermissionResolutionDto`
  — one evaluation answering many permissions (today every `explain()` builds a new one; a matrix
  asks permissions × roles times). With a principal, `unmetConditions` is filled.
- `AccessControl::storedGrantsOf(AuthControllable $principal): Closure(PermissionDefinition): bool`
  — what the principal stores: direct grants plus the grants of its roles, `HoldsGrants` roles read
  as stored and other roles through `hasPermissionTo()` — exactly the logic `HasRoles` uses today,
  made public so a UI can put staged changes on top instead of re-implementing it.
- `PermissionDto::$conditions` — `list<PermissionCondition>`.
- `AccessControl::unmetConditions(PermissionDefinition $permission, object $principal): list<PermissionCondition>`.
- `PermissionGraph::problemDetails(): list<PermissionProblemDto>` — `type`
  (`PermissionProblemType::RequiresConflicting`, `ImpliesConflicting`, `UnregisteredTarget`),
  `permission`, `other`, `ruleType`. `problems()` keeps returning the same sentences, built from these.
- Principal diagrams: new state `unmet-condition` (between `restricted` and `denied`); the JSON
  schema moves to version 2 because a new state value appears.

### Meaning of the resolution fields

For a permission X and a stored state:

| Field | Meaning |
|---|---|
| `stored` | X itself is stored |
| `granted` | X is stored, **or** stored by something that implies it, transitively (the library's existing meaning) |
| `grantedBy` | the permissions that imply X and are granted |
| `allowed` | granted, every requirement active, and no conflict it declares is active |
| `missing` | its requirements that are not active |
| `conflicting` | the permissions it conflicts with that are active |
| `restricted` | a runtime restriction withholds X |
| `unmetConditions` | X's conditions the principal fails (principal given and `Authenticatable`) |
| `effective` | `allowed`, not `restricted`, no `unmetConditions` |

So for `B #[ImpliedBy(A)]` with only A stored: `B.stored = false`, `B.granted = true`,
`B.allowed = true`, `B.grantedBy = [A]`.

### Documentation and tests

- README: a section "Conditions: your own attributes" with the contract, the example above, the
  principal table, the enforcement table, and the side-effect rule.
- Pest:
  - every row of *Principal semantics*, including `HasPermissions` + `Authenticatable`,
    `HasPermissions` on a role, `HasRoles` on a non-authenticatable holder, an account with its own
    `hasPermissionTo()`, and a `null` Gate user;
  - condition on the class, on the case, and both summed;
  - every invariant (1–10) as its own test;
  - every row of *Interaction examples* below, asserted through `hasPermissionTo()`, the Gate,
    `effectivePermissions()` and `explainer()` alike;
  - `problemDetails()` equivalent to `problems()`; `explainer()` equivalent to `explain()`;
  - the diagram state `unmet-condition`.

## Interaction examples

### Rules, restrictions and conditions (account without MFA; `C` = `#[RequiresMFA]`)

| Declarations | Stored | Result |
|---|---|---|
| A `#[Requires(B)]`, B carries C | A, B | B not effective (condition). A **effective**: its requirement B is active by the rules — conditions do not propagate. |
| A carries C, A `#[Requires(B)]` | A, B | A not effective (condition); B effective. |
| B `#[ImpliedBy(A)]`, A carries C | A | A not effective (condition); B **effective** — granted through A, and A's condition does not travel. |
| A `#[ConflictsWith(B)]`, B carries C | A, B | A **not effective** — B is active by the rules, so A loses the conflict even though the account cannot use B. B not effective (condition). |
| A `#[Requires(B)]`, B restricted | A, B | B not effective (restriction). A effective when B is read as stored (`HasRoles` with `HoldsGrants` roles, `HasPermissions`); for a role that is not `HoldsGrants`, a restricted permission reads as absent — existing library behaviour, unchanged. |
| A carries C | A | `hasPermissionTo(A)` false, `Gate::allows(A)` false, A absent from `effectivePermissions()`, `explainer()` gives `allowed = true`, `effective = false`, `unmetConditions = [C]`. |

### Staged changes (deferred mode, a role)

| Stored | Staged | Cell of A shows |
|---|---|---|
| A | revoke A | not granted (staged) |
| — | grant A | stored and in effect (staged) |
| B, with A `#[ImpliedBy(B)]` | grant A | stored and in effect (staged) — A is now stored as well as implied |
| A, with A `#[ConflictsWith(B)]` | grant B | A: blocked by B, **immediately**, before saving |
| A, with A `#[Requires(B)]` | grant B | A turns from "missing requirement" to "stored and in effect" |

The staged state is what `explainer()` receives: `fn (PermissionDefinition $p): bool` returns the
staged decision for `$p` when there is one, and the stored grant otherwise.

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

- `check()`: `true` only when at least one multi-factor provider of the panel reports
  `isEnabled($principal)`.
- The panel: the one named by the optional `panel:` argument, otherwise the current panel,
  otherwise the default panel.
- Fail closed: no panel, or a panel without providers → `false`.
- Free of side effects; `isEnabled()` reads the account's own attributes.

### Layering

| Layer | Owns |
|---|---|
| laravel-access-control | what is stored for a principal (`storedGrantsOf`), what that resolves to (`explainer` → `PermissionResolutionDto`), restrictions, conditions, declaration problems |
| filament-access-control | staged changes on top of stored grants; `PermissionCellState` — a **presentation** object: which icon, colour and tooltip lines a `PermissionResolutionDto` becomes |

The Filament package never decides whether a permission is in effect; it only shows what the
library resolved.

### Cell state — `PermissionCellState` (presentation)

Built from a `PermissionResolutionDto` and whether the cell is staged:

- a **role's** cell: `explainer()` over the role's stored grants plus staged changes, without a
  principal (rules resolved within that role alone; no conditions);
- an **account's** effective cell: `explainer()` over `storedGrantsOf($account)` plus staged direct
  grants, with the account as principal.

One explainer per holder per request.

### Reading and writing

Screens read and write **stored** grants only (`getPermissions()`), as the library's README warns.
Clicking an implied permission grants it explicitly; clicking a stored but inactive one
(missing requirement, conflict) revokes it. The writer is unchanged.

### `PermissionTree`

Rules from `PermissionDto::$rules` (both ends of every rule), conditions from
`PermissionDto::$conditions`. Search also matches the names of related permissions.

### Plugin options (new)

- `->diagrams(bool|Closure)` — default `true`.
- `->declarationProblems(bool|Closure)` — show declaration problems; default `true`.

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

| State | Resolution | Icon | Colour | Tooltip |
|---|---|---|---|---|
| stored and in effect | `stored`, `effective` | check-circle | success | — |
| in effect, implied | `! stored`, `effective` | check-circle | info | Implied by: X (click grants it explicitly) |
| requirement missing | `granted`, `missing ≠ []` | exclamation-triangle | warning | Missing requirement: X |
| loses a conflict | `granted`, `conflicting ≠ []` | no-symbol | danger | Blocked by: Y |
| restricted at runtime | `granted`, `restricted` | lock-closed | gray | Restricted by the application |
| condition unmet (accounts) | `granted`, `unmetConditions ≠ []` | shield-exclamation | warning | Requires MFA — this account has none |
| not granted | `! granted` | x-circle | danger | — |

A staged cell (deferred mode) takes the `primary` colour — `warning` now means a missing
requirement — and its state is computed with the staged changes (see *Staged changes*), so the
consequence of a change is visible before it is saved. Subject rows keep all / some / none.

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
`bin/build.js`, the assets workflow). If the script fails to load, the modal shows the Mermaid
source text instead (invariant 11).

### Translations

Every new string in all 64 shipped locales; `TranslationsTest` and `LocalesTest` keep holding them.

## Part 4 — testing, releases, risks

### Tests

- Library: see Part 1.
- Package, test-first:
  - `PermissionCellState`: each of the seven states from a hand-built `PermissionResolutionDto`;
  - every row of *Staged changes*, through the Livewire components;
  - `#[RequiresMFA]`: account with MFA, without, panel without providers, `panel:` argument;
  - Livewire: dependency badges, cell icons and tooltips, "In effect" column, conditions callout,
    problems callout, graph action producing Mermaid text, and everything but the graph with
    `->diagrams(false)`.
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
| mermaid.js adds 2–3 MB to `dist` | Lazy-loaded in the modal only; `->diagrams(false)`; nothing else depends on it. |
| Cost of states in a large matrix | `explainer()`; one evaluation per holder per request. |
| Diagram JSON gains a state value | Schema version 2, noted in the CHANGELOG. |
| A condition doing I/O runs on every check, possibly twice | The contract: side-effect free and idempotent (must), no I/O (should). |
| UI drifting from the library's semantics | Layering: the library resolves, the UI only presents; invariant 5 and 9 tested in the library. |
