# laravel-access-control 3.1.0 — Permission Conditions Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Let an attribute implementing `PermissionCondition` switch a permission off for an account that does not meet it — enforced by the Gate, the traits and `effectivePermissions()` — and give UIs everything they need to explain it (`explainer()`, `storedGrantsOf()`, `problemDetails()`, `PermissionDto::$conditions`, a diagram state).

**Architecture:** A new singleton `PermissionConditions` discovers condition attributes by interface (`ReflectionAttribute::IS_INSTANCEOF`) on the enum and on the case, memoises the attribute instances per process and evaluates them for `Authenticatable` principals only. Every entry point applies conditions last (restrictions → rules → conditions). The resolver gains `explainer()` — one `PermissionEvaluation` answering many permissions — and its DTO gains `restricted`, `unmetConditions` and `effective`.

**Tech Stack:** PHP 8.3+, Laravel 12/13 (`illuminate/support`), Pest 4, Orchestra Testbench, PHPStan (Larastan, level 4), Pint, Rector.

**Spec:** `docs/superpowers/specs/2026-09-29-access-control-3-conditions-and-rules-ui-design.md` in `happenv-com/filament-access-control` (Part 1, *Principal semantics*, *Invariants*, *Interaction examples*). Read it before starting.

**Repository:** `/Users/bgajda/Packages/laravel-access-control` (GitHub `happenv-com/laravel-access-control`). Every path below is relative to it.

## Global Constraints

- Branch `feat/permission-conditions` from `origin/3.x`; released as `v3.1.0`. **Additive only** — every existing test keeps passing unchanged, except the two schema-version assertions Task 7 updates.
- PHP `^8.3`: no PHP 8.4 syntax (`new X()->method()` is 8.4 — write `(new X)->method()`), no property hooks.
- A condition applies **iff the principal is `Illuminate\Contracts\Auth\Authenticatable`**. Roles and other non-authenticatable holders are never evaluated.
- Order for an account: **restrictions → rules → conditions**. A condition withholds its own permission only; it never propagates through rules.
- A refusal caused by a condition carries **the same message** as a missing permission (`GateConfigurator::refusal()`).
- Condition answers are **never cached** by the library; only the attribute instances are memoised.
- Every file starts with `declare(strict_types=1);`; match the surrounding docblock density and voice (full sentences explaining *why*).
- Quality gates that must pass before the PR: `vendor/bin/pest`, `vendor/bin/phpstan analyse`, `vendor/bin/pint --test`, `vendor/bin/rector --dry-run`, `composer test:type-coverage` (keep 100 %).

## Review Focus

1. A condition whose `check()` throws — the exception propagates out of `hasPermissionTo()` and the Gate; it is neither swallowed as `false` nor as `true` (test in Task 2).
2. A permission whose enum nobody registered, carrying a condition — `hasPermissionTo()` still enforces it (test in Task 2).
3. The account's state changing between two checks on the same `HasRoles` instance (MFA switched on mid-request) — the second answer reflects it; the memo never remembers a condition (test in Task 2).
4. An account answering `hasPermissionTo()` itself with a restricted permission — `effectivePermissions()` now leaves it out, matching the Gate (test in Task 3; CHANGELOG calls out the narrowing).
5. An `explainer()` built before the stored state changes keeps answering the old state; a new one sees the change (test in Task 4; documented on the method).

---

## File Structure

| File | Responsibility |
|---|---|
| `src/Contracts/PermissionCondition.php` (new) | The contract an attribute implements: `check(PermissionDefinition, Authenticatable): bool`. |
| `src/Contracts/DescribesPermissionCondition.php` (new) | Optional: `describe(): string` for UIs and diagrams. |
| `src/PermissionConditions.php` (new) | Discovery (class + case, by interface), per-process memo of instances, `for()`, `unmet()`, `metBy()`. |
| `src/AccessControlServiceProvider.php` | Singleton binding; resolved at boot for Octane. |
| `src/Traits/HasPermissions.php`, `HasRoles.php`, `HasRolesAndPermissions.php` | Conditions as the last step of `hasPermissionTo()`, outside the `HasRoles` memo. |
| `src/GateConfigurator.php` | Conditions after `hasPermissionTo()`, for every account. |
| `src/AccessControl.php` | `effectivePermissions()` excludes restricted + unmet; `unmetConditions()`, `storedGrantsOf()`, `roleGrantsOf()`. |
| `src/Facades/AccessControl.php` | `@method` docblock for the new methods. |
| `src/Dto/PermissionResolutionDto.php` | `restricted`, `unmetConditions`, computed `effective`. |
| `src/PermissionResolver.php` | `explainer()`; `explain()` delegates to it. |
| `src/Dto/PermissionDto.php`, `src/PermissionCollection.php` | `PermissionDto::$conditions`, attached by the collection. |
| `src/PermissionProblemType.php`, `src/Dto/PermissionProblemDto.php` (new), `src/PermissionGraph.php` | `problemDetails()`; `problems()` worded from it. |
| `src/Diagram/PermissionState.php`, `Diagram/PrincipalDiagramBuilder.php`, `Diagram/Renderer/Support/StatePalette.php`, `Diagram/PermissionDiagram.php`, `Commands/PermissionGraphCommand.php` | State `unmet-condition`, schema version 2. |
| `tests/Fixtures/Conditions/{RequiresFlag,Flags,ThrowsOnCheck}.php` (new) | A test condition whose outcome a test controls per principal; one that throws. |
| `tests/Fixtures/Permissions/Conditions/{ConditionedPermission,ConditionRulePermission,ThrowingConditionPermission}.php` (new) | Enums with conditions on the class, the case, and every interaction row of the spec. |
| `tests/Fixtures/Models/Concerns/AuthenticatesInMemory.php`, `Models/{InMemoryAccount,RoleHoldingAccount,RoleHoldingUser,SelfAnsweringAccount}.php` (new) | One fixture per row of *Principal semantics*. |
| `tests/Unit/PermissionConditionsTest.php`, `tests/Feature/PermissionConditionsTest.php`, `tests/Feature/StoredGrantsTest.php`, `tests/Feature/ConditionInteractionsTest.php` (new); `tests/Unit/PermissionResolverTest.php`, `tests/Unit/PermissionGraphTest.php`, `tests/Unit/PermissionCollectionTest.php`, `tests/Feature/Diagram/PrincipalDiagramTest.php`, `tests/Unit/Diagram/PermissionDiagramTest.php`, `tests/Feature/Diagram/PermissionGraphCommandTest.php`, `tests/Pest.php` | Tests. |
| `README.md`, `CHANGELOG.md` | Section 9 "Conditions on Accounts"; 3.1.0 entry. |

---

### Task 0: Branch and baseline

**Files:** none changed.

- [ ] **Step 1: Create the branch from the released 3.x line**

```bash
cd /Users/bgajda/Packages/laravel-access-control
git fetch origin
git switch -c feat/permission-conditions origin/3.x
composer update
```

- [ ] **Step 2: Confirm the baseline is green**

Run: `vendor/bin/pest && vendor/bin/phpstan analyse && composer test:type-coverage`
Expected: all tests PASS, PHPStan `[OK] No errors`, type coverage 100 %. If type coverage is below 100 % on `origin/3.x` already, note the number and keep it from dropping.

---

### Task 1: The contract and `PermissionConditions`

**Files:**
- Create: `src/Contracts/PermissionCondition.php`, `src/Contracts/DescribesPermissionCondition.php`, `src/PermissionConditions.php`
- Create: `tests/Fixtures/Conditions/RequiresFlag.php`, `tests/Fixtures/Conditions/Flags.php`
- Create: `tests/Fixtures/Permissions/Conditions/ConditionedPermission.php`, `tests/Fixtures/Permissions/Conditions/ConditionRulePermission.php`
- Create: `tests/Fixtures/Models/Concerns/AuthenticatesInMemory.php`, `tests/Fixtures/Models/InMemoryAccount.php`
- Modify: `src/AccessControlServiceProvider.php`
- Test: `tests/Unit/PermissionConditionsTest.php`

**Interfaces:**
- Produces: `Contracts\PermissionCondition::check(PermissionDefinition $permission, Authenticatable $principal): bool`; `Contracts\DescribesPermissionCondition::describe(): string`; `PermissionConditions::for(PermissionDefinition): list<PermissionCondition>`, `::unmet(PermissionDefinition, object $principal): list<PermissionCondition>`, `::metBy(PermissionDefinition, object $principal): bool` (singleton).
- Produces (fixtures): `RequiresFlag(string $flag = 'mfa')`; `Flags::raise(object, string = 'mfa')`, `Flags::lower(object, string = 'mfa')`, `Flags::raised(object, string): bool`, `Flags::reset()`, `Flags::$checks` (int), `Flags::$checked` (?PermissionDefinition); `InMemoryAccount(array $grants = [])` (Authenticatable + HasPermissions); trait `AuthenticatesInMemory`; enums `ConditionedPermission` and `ConditionRulePermission` (cases listed below).

- [ ] **Step 1: Write the fixtures**

`tests/Fixtures/Conditions/Flags.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Conditions;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use WeakMap;

/**
 * Which principal meets which {@see RequiresFlag} — held per object, so every test builds the
 * accounts it needs and nothing outlives them.
 */
final class Flags
{
    /** How many times a condition was checked — to prove an answer is never remembered. */
    public static int $checks = 0;

    /** The permission the last check was asked about. */
    public static ?PermissionDefinition $checked = null;

    /** @var WeakMap<object, array<string, true>>|null */
    private static ?WeakMap $raised = null;

    public static function raise(object $principal, string $flag = 'mfa'): void
    {
        self::$raised ??= new WeakMap;
        self::$raised[$principal] = [...(self::$raised[$principal] ?? []), $flag => true];
    }

    public static function lower(object $principal, string $flag = 'mfa'): void
    {
        if (self::$raised === null || ! isset(self::$raised[$principal])) {
            return;
        }

        $flags = self::$raised[$principal];
        unset($flags[$flag]);
        self::$raised[$principal] = $flags;
    }

    public static function raised(object $principal, string $flag): bool
    {
        return self::$raised !== null && isset(self::$raised[$principal][$flag]);
    }

    public static function reset(): void
    {
        self::$raised = null;
        self::$checks = 0;
        self::$checked = null;
    }
}
```

`tests/Fixtures/Conditions/RequiresFlag.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Conditions;

use Attribute;
use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * Met by an account {@see Flags} raised the flag for — a stand-in for "has MFA", "verified e-mail".
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT | Attribute::IS_REPEATABLE)]
final readonly class RequiresFlag implements DescribesPermissionCondition, PermissionCondition
{
    public function __construct(
        public string $flag = 'mfa',
    ) {}

    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        Flags::$checks++;
        Flags::$checked = $permission;

        return Flags::raised($principal, $this->flag);
    }

    public function describe(): string
    {
        return 'Requires ' . $this->flag;
    }
}
```

`tests/Fixtures/Permissions/Conditions/ConditionedPermission.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions;

use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Groups\ProductGroup;

/**
 * A condition on the enum (every case) and one more on a case — summed.
 */
#[PermissionGroup(ProductGroup::class)]
#[RequiresFlag('verified')]
enum ConditionedPermission: string implements PermissionDefinition
{
    case Plain = 'conditioned.plain';

    #[RequiresFlag('mfa')]
    case Guarded = 'conditioned.guarded';
}
```

`tests/Fixtures/Permissions/Conditions/ConditionRulePermission.php` — one pair of cases per row of the spec's *Interaction examples* (the case order matters: `effectivePermissions()` lists in registration order):

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions;

use Happenv\LaravelAccessControl\Attributes\ConflictsWith;
use Happenv\LaravelAccessControl\Attributes\ImpliedBy;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\Requires;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Groups\ProductGroup;

/**
 * The spec's interaction examples: rules, a restriction and a condition (`RequiresFlag`, "mfa").
 */
#[PermissionGroup(ProductGroup::class)]
enum ConditionRulePermission: string implements PermissionDefinition
{
    // A requires B; B carries the condition.
    #[Requires(self::GuardedRequirement)]
    case RequiresGuarded = 'condition-rule.requires-guarded';

    #[RequiresFlag]
    case GuardedRequirement = 'condition-rule.guarded-requirement';

    // A carries the condition and requires B.
    #[RequiresFlag]
    #[Requires(self::PlainRequirement)]
    case GuardedRequirer = 'condition-rule.guarded-requirer';

    case PlainRequirement = 'condition-rule.plain-requirement';

    // B is implied by A; A carries the condition.
    #[RequiresFlag]
    case GuardedImplier = 'condition-rule.guarded-implier';

    #[ImpliedBy(self::GuardedImplier)]
    case ImpliedByGuarded = 'condition-rule.implied-by-guarded';

    // A conflicts with B; B carries the condition.
    #[ConflictsWith(self::GuardedConflict)]
    case ConflictsWithGuarded = 'condition-rule.conflicts-with-guarded';

    #[RequiresFlag]
    case GuardedConflict = 'condition-rule.guarded-conflict';

    // A requires B; a test restricts B.
    #[Requires(self::Restricted)]
    case RequiresRestricted = 'condition-rule.requires-restricted';

    case Restricted = 'condition-rule.restricted';

    // A condition and nothing else.
    #[RequiresFlag]
    case Alone = 'condition-rule.alone';
}
```

`tests/Fixtures/Models/Concerns/AuthenticatesInMemory.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Models\Concerns;

/**
 * Just enough of `Authenticatable` for an account living in memory: the conditions and the gate
 * need somebody who signs in, not a users table.
 */
trait AuthenticatesInMemory
{
    public function getAuthIdentifierName(): string
    {
        return 'id';
    }

    public function getAuthIdentifier(): int
    {
        return spl_object_id($this);
    }

    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    public function getAuthPassword(): string
    {
        return '';
    }

    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void {}

    public function getRememberTokenName(): string
    {
        return '';
    }
}
```

`tests/Fixtures/Models/InMemoryAccount.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Models;

use Happenv\LaravelAccessControl\Tests\Fixtures\Models\Concerns\AuthenticatesInMemory;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * An account holding its grants directly — a machine user, an API key.
 */
class InMemoryAccount extends InMemoryRole implements Authenticatable
{
    use AuthenticatesInMemory;
}
```

- [ ] **Step 2: Write the failing test**

`tests/Unit/PermissionConditionsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\LaravelAccessControl\PermissionConditions;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\Flags;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionedPermission;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionRulePermission;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\ProductPermission;

beforeEach(function (): void {
    Flags::reset();
});

describe('PermissionConditions', function (): void {
    it('reads a condition declared on the enum for every case', function (): void {
        expect(resolve(PermissionConditions::class)->for(ConditionedPermission::Plain))
            ->toEqual([new RequiresFlag('verified')]);
    });

    it('reads a condition declared on one case', function (): void {
        expect(resolve(PermissionConditions::class)->for(ConditionRulePermission::Alone))
            ->toEqual([new RequiresFlag('mfa')]);
    });

    it('sums the enum\'s conditions and the case\'s, the enum\'s first', function (): void {
        expect(resolve(PermissionConditions::class)->for(ConditionedPermission::Guarded))
            ->toEqual([new RequiresFlag('verified'), new RequiresFlag('mfa')]);
    });

    it('finds nothing on a permission without conditions', function (): void {
        expect(resolve(PermissionConditions::class)->for(ProductPermission::View))->toBe([]);
    });

    it('keeps the attribute instances for the life of the process', function (): void {
        $conditions = resolve(PermissionConditions::class);

        expect($conditions->for(ConditionedPermission::Guarded)[0])->toBe($conditions->for(ConditionedPermission::Guarded)[0])
            ->and(resolve(PermissionConditions::class))->toBe($conditions);
    });

    it('names the conditions an account fails, and none it meets', function (): void {
        $conditions = resolve(PermissionConditions::class);
        $account = new InMemoryAccount;

        Flags::raise($account, 'verified');

        expect($conditions->unmet(ConditionedPermission::Guarded, $account))->toEqual([new RequiresFlag('mfa')])
            ->and($conditions->metBy(ConditionedPermission::Guarded, $account))->toBeFalse();

        Flags::raise($account, 'mfa');

        expect($conditions->unmet(ConditionedPermission::Guarded, $account))->toBe([])
            ->and($conditions->metBy(ConditionedPermission::Guarded, $account))->toBeTrue();
    });

    it('never evaluates a principal that is not Authenticatable', function (): void {
        $conditions = resolve(PermissionConditions::class);

        expect($conditions->unmet(ConditionedPermission::Guarded, new InMemoryRole))->toBe([])
            ->and($conditions->metBy(ConditionedPermission::Guarded, new InMemoryRole))->toBeTrue()
            ->and(Flags::$checks)->toBe(0);
    });

    it('asks again on every check — an answer is never remembered', function (): void {
        $conditions = resolve(PermissionConditions::class);
        $account = new InMemoryAccount;

        expect($conditions->metBy(ConditionRulePermission::Alone, $account))->toBeFalse();

        Flags::raise($account);

        expect($conditions->metBy(ConditionRulePermission::Alone, $account))->toBeTrue()
            ->and(Flags::$checks)->toBe(2);
    });

    it('stops at the first condition the account fails', function (): void {
        resolve(PermissionConditions::class)->metBy(ConditionedPermission::Guarded, new InMemoryAccount);

        expect(Flags::$checks)->toBe(1);
    });

    it('hands each condition the permission it guards', function (): void {
        resolve(PermissionConditions::class)->metBy(ConditionedPermission::Plain, new InMemoryAccount);

        expect(Flags::$checked)->toBe(ConditionedPermission::Plain);
    });
});
```

- [ ] **Step 3: Run it to see it fail**

Run: `vendor/bin/pest tests/Unit/PermissionConditionsTest.php`
Expected: FAIL — `Class "Happenv\LaravelAccessControl\PermissionConditions" not found` (and the interfaces the fixture implements).

- [ ] **Step 4: Write the contracts**

`src/Contracts/PermissionCondition.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;

/**
 * A condition an ACCOUNT must meet for a permission to be in effect — implemented by an attribute
 * placed on a permission enum (it then guards every case) or on one case. An application writes its
 * own; nothing is registered, the attribute is found by this interface.
 *
 * Checked last — after runtime restrictions and the rules between permissions — and only for a
 * principal that is `Authenticatable`: a role stores grants, it does not sign in.
 *
 * `check()` MUST be free of side effects and idempotent within a request, and SHOULD avoid I/O. It
 * runs on every check, twice in one gate check (once in the trait, once in the gate), and its answer
 * is never cached: a cache would outlive a change in the account, such as MFA switched on mid-request.
 */
interface PermissionCondition
{
    /**
     * True when the account meets the condition for this permission.
     *
     * The permission is handed over on purpose: one attribute class may guard many permissions, and
     * a condition may depend on which one it guards — reading the case's other attributes, say.
     */
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool;
}
```

`src/Contracts/DescribesPermissionCondition.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Contracts;

/**
 * A condition that names itself for a UI or a diagram. Optional: a condition without it is shown by
 * its class name.
 */
interface DescribesPermissionCondition
{
    /**
     * A short label in the current locale — "Requires MFA".
     */
    public function describe(): string;
}
```

- [ ] **Step 5: Write `PermissionConditions`**

`src/PermissionConditions.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl;

use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionEnumUnitCase;

/**
 * The conditions declared on permissions: every attribute implementing {@see PermissionCondition},
 * found by that interface — on the enum, for every case, and on the case, adding to the enum's.
 *
 * The attribute INSTANCES are kept for the life of the process: they are facts about the code, which
 * does not change at runtime, so this is safe under Octane. Their ANSWERS are never kept.
 */
final class PermissionConditions
{
    /**
     * @var array<string, list<PermissionCondition>>
     */
    private array $declared = [];

    /**
     * Every condition of the permission, the enum's first. The account must meet all of them.
     *
     * @return list<PermissionCondition>
     */
    public function for(PermissionDefinition $permission): array
    {
        return $this->declared[$permission::class . '::' . $permission->name] ??= $this->read($permission);
    }

    /**
     * The conditions of the permission the principal fails — none for a principal that is not
     * `Authenticatable`, which is never evaluated against conditions.
     *
     * @return list<PermissionCondition>
     */
    public function unmet(PermissionDefinition $permission, object $principal): array
    {
        if (! $principal instanceof Authenticatable) {
            return [];
        }

        return array_values(array_filter(
            $this->for($permission),
            fn (PermissionCondition $condition): bool => ! $condition->check($permission, $principal),
        ));
    }

    /**
     * Whether the principal meets every condition of the permission — asking no further than the
     * first it fails. Always true for a principal that is not `Authenticatable`.
     */
    public function metBy(PermissionDefinition $permission, object $principal): bool
    {
        if (! $principal instanceof Authenticatable) {
            return true;
        }

        foreach ($this->for($permission) as $condition) {
            if (! $condition->check($permission, $principal)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<PermissionCondition>
     */
    private function read(PermissionDefinition $permission): array
    {
        $attributes = [
            ...(new ReflectionClass($permission))->getAttributes(PermissionCondition::class, ReflectionAttribute::IS_INSTANCEOF),
            ...(new ReflectionEnumUnitCase($permission, $permission->name))->getAttributes(PermissionCondition::class, ReflectionAttribute::IS_INSTANCEOF),
        ];

        return array_map(
            fn (ReflectionAttribute $attribute): PermissionCondition => $attribute->newInstance(),
            $attributes,
        );
    }
}
```

- [ ] **Step 6: Bind it as a singleton, resolved at boot**

In `src/AccessControlServiceProvider.php`, `register()`, after the `PermissionRestrictions` singleton:

```php
        $this->app->singleton(fn (): PermissionConditions => new PermissionConditions);
```

In `boot()`, next to `resolve(PermissionResolver::class);` (same Octane reason as the comment above it):

```php
        resolve(PermissionResolver::class);
        resolve(PermissionConditions::class);
```

- [ ] **Step 7: Run the test to see it pass**

Run: `vendor/bin/pest tests/Unit/PermissionConditionsTest.php`
Expected: PASS (10 tests).

- [ ] **Step 8: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse && vendor/bin/pest`
Expected: Pint fixes nothing important, PHPStan OK, full suite PASS.

```bash
git add src/Contracts/PermissionCondition.php src/Contracts/DescribesPermissionCondition.php src/PermissionConditions.php src/AccessControlServiceProvider.php tests/Fixtures/Conditions tests/Fixtures/Permissions/Conditions tests/Fixtures/Models/Concerns tests/Fixtures/Models/InMemoryAccount.php tests/Unit/PermissionConditionsTest.php
git commit -m "feat: PermissionCondition — conditions an account must meet, declared as attributes"
```

---

### Task 2: Conditions in the traits' `hasPermissionTo()`

**Files:**
- Modify: `src/Traits/HasPermissions.php:hasPermissionTo`, `src/Traits/HasRoles.php:hasPermissionTo`, `src/Traits/HasRolesAndPermissions.php:hasPermissionTo`
- Create: `tests/Fixtures/Models/RoleHoldingAccount.php`, `tests/Fixtures/Models/RoleHoldingUser.php`, `tests/Fixtures/Models/SelfAnsweringAccount.php`, `tests/Fixtures/Conditions/ThrowsOnCheck.php`, `tests/Fixtures/Permissions/Conditions/ThrowingConditionPermission.php`
- Test: `tests/Feature/PermissionConditionsTest.php` (new)

**Interfaces:**
- Consumes: `PermissionConditions::metBy()` (Task 1), fixtures from Task 1.
- Produces (fixtures): `RoleHoldingAccount(array $roles = [])` (Authenticatable + HasRoles), `RoleHoldingUser` (the Eloquent `User` fixture with `public array $heldRoles` returned by `getRoles()`), `SelfAnsweringAccount` (Authenticatable + AuthControllable answering `true` itself), `ThrowingConditionPermission::Broken`.

- [ ] **Step 1: Write the fixtures**

`tests/Fixtures/Models/RoleHoldingAccount.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Models;

use Happenv\LaravelAccessControl\Tests\Fixtures\Models\Concerns\AuthenticatesInMemory;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * An account holding roles and nothing directly — `HasRoles` on somebody who signs in.
 */
class RoleHoldingAccount extends RoleHolder implements Authenticatable
{
    use AuthenticatesInMemory;
}
```

`tests/Fixtures/Models/RoleHoldingUser.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Models;

/**
 * The user fixture with roles a test hands it — `HasRolesAndPermissions` with both sources.
 */
class RoleHoldingUser extends User
{
    /**
     * @var list<object>
     */
    public array $heldRoles = [];

    public function getRoles(): iterable
    {
        return $this->heldRoles;
    }
}
```

`tests/Fixtures/Models/SelfAnsweringAccount.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Models;

use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\Concerns\AuthenticatesInMemory;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * An account answering `hasPermissionTo()` itself — an administrator short-circuit that never
 * reaches the library's traits.
 */
class SelfAnsweringAccount implements AuthControllable, Authenticatable
{
    use AuthenticatesInMemory;

    public function hasPermissionTo(PermissionDefinition $permission): bool
    {
        return true;
    }
}
```

`tests/Fixtures/Conditions/ThrowsOnCheck.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Conditions;

use Attribute;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;
use RuntimeException;

/**
 * A condition that cannot answer — its failure must reach the caller, not be read as a verdict.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class ThrowsOnCheck implements PermissionCondition
{
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        throw new RuntimeException('The condition could not be checked.');
    }
}
```

`tests/Fixtures/Permissions/Conditions/ThrowingConditionPermission.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions;

use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\ThrowsOnCheck;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Groups\ProductGroup;

#[PermissionGroup(ProductGroup::class)]
enum ThrowingConditionPermission: string implements PermissionDefinition
{
    #[ThrowsOnCheck]
    case Broken = 'throwing-condition.broken';
}
```

- [ ] **Step 2: Write the failing test**

`tests/Feature/PermissionConditionsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\LaravelAccessControl\PermissionRegistry;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\Flags;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryGrantRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHolder;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHoldingAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHoldingUser;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\SelfAnsweringAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\User;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionedPermission;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionRulePermission;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ThrowingConditionPermission;

beforeEach(function (): void {
    Flags::reset();

    // ConditionedPermission is left unregistered on purpose: a condition holds for any permission.
    resolve(PermissionRegistry::class)->register(ConditionRulePermission::class);
});

describe('hasPermissionTo()', function (): void {
    it('withholds a permission from an account that fails its condition', function (object $account): void {
        expect($account->hasPermissionTo(ConditionRulePermission::Alone))->toBeFalse();

        Flags::raise($account);

        expect($account->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue();
    })->with([
        'a user (HasRolesAndPermissions)' => fn (): User => tap(new User, fn (User $user) => $user->permissions = ['condition-rule.alone']),
        'a machine user (HasPermissions)' => fn (): InMemoryAccount => new InMemoryAccount(['condition-rule.alone']),
        'an account holding roles (HasRoles)' => fn (): RoleHoldingAccount => new RoleHoldingAccount([new InMemoryGrantRole(['condition-rule.alone'])]),
        'a user holding a role that is asked (HasRolesAndPermissions)' => fn (): RoleHoldingUser => tap(new RoleHoldingUser, fn (RoleHoldingUser $user) => $user->heldRoles = [new InMemoryRole(['condition-rule.alone'])]),
    ]);

    it('never evaluates a principal that is not an account', function (object $principal): void {
        expect($principal->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue()
            ->and(Flags::$checks)->toBe(0);
    })->with([
        'a role (HasPermissions)' => fn (): InMemoryRole => new InMemoryRole(['condition-rule.alone']),
        'a role handing over its grants (HoldsGrants)' => fn (): InMemoryGrantRole => new InMemoryGrantRole(['condition-rule.alone']),
        'a holder of roles that does not sign in (HasRoles)' => fn (): RoleHolder => new RoleHolder([new InMemoryGrantRole(['condition-rule.alone'])]),
    ]);

    it('lets an account answering hasPermissionTo() itself say what it says', function (): void {
        expect((new SelfAnsweringAccount)->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue();
    });

    it('asks the condition outside the memo of HasRoles, so a change within the instance counts at once', function (): void {
        $account = new RoleHoldingAccount([new InMemoryGrantRole(['condition-rule.alone'])]);

        expect($account->hasPermissionTo(ConditionRulePermission::Alone))->toBeFalse();

        Flags::raise($account);

        expect($account->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue();

        Flags::lower($account);

        expect($account->hasPermissionTo(ConditionRulePermission::Alone))->toBeFalse();
    });

    it('asks a condition only once the rules allow the permission', function (): void {
        $user = new User;
        $user->permissions = [];

        expect($user->hasPermissionTo(ConditionRulePermission::Alone))->toBeFalse()
            ->and(Flags::$checks)->toBe(0);
    });

    it('enforces a condition on a permission nobody registered', function (): void {
        $account = new InMemoryAccount(['conditioned.plain']);

        expect($account->hasPermissionTo(ConditionedPermission::Plain))->toBeFalse();

        Flags::raise($account, 'verified');

        expect($account->hasPermissionTo(ConditionedPermission::Plain))->toBeTrue();
    });

    it('leaves an ability string to the grants alone', function (): void {
        $user = new User;
        $user->permissions = ['condition-rule.alone'];

        expect($user->hasPermissionTo('condition-rule.alone'))->toBeTrue()
            ->and(Flags::$checks)->toBe(0);
    });

    it('lets a condition that cannot answer fail loudly', function (): void {
        (new InMemoryAccount(['throwing-condition.broken']))->hasPermissionTo(ThrowingConditionPermission::Broken);
    })->throws(RuntimeException::class, 'The condition could not be checked.');
});
```

- [ ] **Step 3: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/PermissionConditionsTest.php`
Expected: FAIL — the four "withholds" cases and "outside the memo", "nobody registered" and "fail loudly" fail (`hasPermissionTo()` returns true; nothing throws).

- [ ] **Step 4: Apply conditions in `HasPermissions`**

In `src/Traits/HasPermissions.php` add `use Happenv\LaravelAccessControl\PermissionConditions;` and replace the `return` of `hasPermissionTo()`:

```php
        // Read once per check: the rules may ask about several permissions, and `getPermissions()`
        // builds a new collection every time it is called.
        $grants = $this->getPermissions();

        // The conditions last, and only once the rules allow: a condition withholds this permission
        // alone — never what it implies, what requires it or what it conflicts with.
        return resolve(PermissionResolver::class)->allows(
            $permission,
            fn (PermissionDefinition $candidate): bool => $grants->contains($candidate->value),
        ) && resolve(PermissionConditions::class)->metBy($permission, $this);
```

- [ ] **Step 5: Apply conditions in `HasRoles`, outside its memo**

In `src/Traits/HasRoles.php` add `use Happenv\LaravelAccessControl\PermissionConditions;`. Replace everything in `hasPermissionTo()` after the restriction check with a call to a new private method, and move the memo into it:

```php
        if (resolve(PermissionRestrictions::class)->isRestricted($permission)) {
            return false;
        }

        // Asked after the memo and never stored in it, like the restriction: an account's state — MFA
        // switched on — can change within the life of this instance.
        return $this->allowedByRoles($permission)
            && resolve(PermissionConditions::class)->metBy($permission, $this);
    }

    /**
     * Whether the rules allow the permission over the union of the roles' grants — remembered per
     * instance, unless a restriction shaped the answer (see {@see self::hasRoleGrant()}).
     */
    private function allowedByRoles(PermissionDefinition $permission): bool
    {
        $key = (string) $permission->value;

        if (isset($this->resolvedRolePermissions[$key])) {
            return $this->resolvedRolePermissions[$key];
        }

        $this->resolutionReadRestriction = false;

        $allowed = resolve(PermissionResolver::class)->allows($permission, $this->hasRoleGrant(...));

        if (! $this->resolutionReadRestriction) {
            $this->resolvedRolePermissions[$key] = $allowed;
        }

        return $allowed;
    }
```

Also extend the docblock of `hasPermissionTo()` with one paragraph:

```php
     * An account — a principal that is `Authenticatable` — must also meet the permission's
     * conditions ({@see PermissionConditions}), asked last and never remembered.
```

- [ ] **Step 6: Apply conditions in `HasRolesAndPermissions`**

In `src/Traits/HasRolesAndPermissions.php` add `use Happenv\LaravelAccessControl\PermissionConditions;` and replace the final `return`:

```php
        $direct = $this->getPermissions();

        return resolve(PermissionResolver::class)->allows(
            $permission,
            fn (PermissionDefinition $candidate): bool => $direct->contains($candidate->value) || $this->hasRoleGrant($candidate),
        ) && resolve(PermissionConditions::class)->metBy($permission, $this);
```

- [ ] **Step 7: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/PermissionConditionsTest.php && vendor/bin/pest`
Expected: PASS, and the whole suite still PASS.

- [ ] **Step 8: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/Traits tests/Fixtures tests/Feature/PermissionConditionsTest.php
git commit -m "feat: the traits withhold a permission from an account that fails its condition"
```

---

### Task 3: The Gate, `effectivePermissions()` and `unmetConditions()`

**Files:**
- Modify: `src/GateConfigurator.php`, `src/AccessControl.php`, `src/Facades/AccessControl.php`
- Test: `tests/Feature/PermissionConditionsTest.php` (append)

**Interfaces:**
- Consumes: `PermissionConditions` (Task 1), fixtures (Tasks 1–2).
- Produces: `AccessControl::unmetConditions(PermissionDefinition $permission, object $principal): list<PermissionCondition>`; `effectivePermissions()` excludes restricted permissions and permissions with an unmet condition for every principal.

- [ ] **Step 1: Write the failing tests**

Append to `tests/Feature/PermissionConditionsTest.php` (add the `use` lines at the top of the file: `Happenv\LaravelAccessControl\Contracts\PermissionDefinition`, `Happenv\LaravelAccessControl\Facades\AccessControl`, `Happenv\LaravelAccessControl\GateConfigurator`, `Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag`, `Happenv\LaravelAccessControl\Tests\Fixtures\Models\Concerns\AuthenticatesInMemory`, `Illuminate\Contracts\Auth\Authenticatable`, `Illuminate\Support\Facades\Gate`):

```php
describe('the gate', function (): void {
    beforeEach(function (): void {
        resolve(GateConfigurator::class)->configure();
    });

    it('refuses an account failing a condition in the words of a missing permission (invariant 10)', function (bool $display, string $message): void {
        config(['access-control.display_permission_in_exception' => $display]);

        $failing = new InMemoryAccount(['condition-rule.alone']);
        $missing = new InMemoryAccount([]);

        expect(Gate::forUser($failing)->inspect(ConditionRulePermission::Alone)->message())->toBe($message)
            ->and(Gate::forUser($missing)->inspect(ConditionRulePermission::Alone)->message())->toBe($message);
    })->with([
        'the permission named' => [true, 'Unauthorized for condition-rule.alone'],
        'the permission not named' => [false, 'Unauthorized.'],
    ]);

    it('lets an account through once it meets the condition', function (): void {
        $account = new InMemoryAccount(['condition-rule.alone']);

        Flags::raise($account);

        expect(Gate::forUser($account)->allows(ConditionRulePermission::Alone))->toBeTrue();
    });

    it('refuses an account answering hasPermissionTo() itself until it meets the condition', function (): void {
        $account = new SelfAnsweringAccount;

        expect(Gate::forUser($account)->allows(ConditionRulePermission::Alone))->toBeFalse();

        Flags::raise($account);

        expect(Gate::forUser($account)->allows(ConditionRulePermission::Alone))->toBeTrue();
    });

    it('evaluates an account that is not AuthControllable too', function (): void {
        $account = new class implements Authenticatable
        {
            use AuthenticatesInMemory;
        };

        expect(Gate::forUser($account)->allows(ConditionRulePermission::Alone))->toBeFalse();

        Flags::raise($account);

        expect(Gate::forUser($account)->allows(ConditionRulePermission::Alone))->toBeTrue();
    });

    it('refuses no user at all before anything else', function (): void {
        expect(Gate::forUser(null)->inspect(ConditionRulePermission::Alone)->message())->toBe('Unauthenticated.')
            ->and(Flags::$checks)->toBe(0);
    });
});

describe('effectivePermissions()', function (): void {
    it('leaves out what an account fails a condition of', function (): void {
        $account = new InMemoryAccount(['condition-rule.alone', 'condition-rule.plain-requirement']);

        expect(AccessControl::effectivePermissions($account)->all())->toBe([ConditionRulePermission::PlainRequirement]);

        Flags::raise($account);

        expect(AccessControl::effectivePermissions($account)->all())->toBe([
            ConditionRulePermission::PlainRequirement,
            ConditionRulePermission::Alone,
        ]);
    });

    it('leaves out, for an account answering hasPermissionTo() itself, what is restricted or fails a condition', function (): void {
        AccessControl::restrictUsing(fn (PermissionDefinition $permission): bool => $permission === ConditionRulePermission::Restricted);

        $effective = AccessControl::effectivePermissions(new SelfAnsweringAccount)->all();

        expect($effective)->toContain(ConditionRulePermission::PlainRequirement)
            ->not->toContain(ConditionRulePermission::Alone)
            ->not->toContain(ConditionRulePermission::Restricted);
    });
});

describe('unmetConditions()', function (): void {
    it('names the conditions an account fails, and none for a role', function (): void {
        expect(AccessControl::unmetConditions(ConditionRulePermission::Alone, new InMemoryAccount))->toEqual([new RequiresFlag])
            ->and(AccessControl::unmetConditions(ConditionRulePermission::Alone, new InMemoryRole))->toBe([]);
    });
});
```

- [ ] **Step 2: Run them to see them fail**

Run: `vendor/bin/pest tests/Feature/PermissionConditionsTest.php`
Expected: FAIL — the self-answering and not-AuthControllable gate cases allow; `effectivePermissions()` keeps `Alone` and `Restricted` for `SelfAnsweringAccount`; `unmetConditions()` is undefined.

- [ ] **Step 3: Apply conditions in the Gate**

In `src/GateConfigurator.php`, add the constructor dependency (the container autowires it):

```php
    public function __construct(
        private PermissionRegistry $permissionRegistry,
        private VoterRegistry $voterRegistry,
        private PermissionRestrictions $restrictions,
        private PermissionConditions $conditions,
    ) {}
```

and, in the gate closure, between the `hasPermissionTo()` refusal and the voters:

```php
                    if ($user instanceof AuthControllable && ! $user->hasPermissionTo($permission)) {
                        return Response::deny($this->refusal($permission));
                    }

                    // Last, and for every account — also one answering hasPermissionTo() itself, which
                    // never reaches the traits, and one that is not AuthControllable at all. The same
                    // refusal as a missing grant: to the caller it is the same problem.
                    if (! $this->conditions->metBy($permission, $user)) {
                        return Response::deny($this->refusal($permission));
                    }
```

Extend the docblock of `refusal()`: "— for a missing grant, a restricted one and an unmet condition alike."

- [ ] **Step 4: Narrow `effectivePermissions()` and add `unmetConditions()`**

In `src/AccessControl.php` add `use Happenv\LaravelAccessControl\Contracts\PermissionCondition;` and replace `effectivePermissions()` and its docblock:

```php
    /**
     * Every registered permission the principal may act on now, in registration order: its own
     * `hasPermissionTo()` asked for each, so rules count — and so does whatever a principal
     * answering that method itself (an administrator short-circuit) decides. Restrictions and an
     * account's conditions are applied here too, whoever answered, so the list is what the gate lets
     * through.
     *
     * Voters do not count: they judge an action on a particular object, and a list has none to hand
     * them. A permission nobody registered is not listed — the registry is the only catalogue there is.
     *
     * @return Collection<int, PermissionDefinition>
     */
    public function effectivePermissions(AuthControllable $principal): Collection
    {
        $restrictions = resolve(PermissionRestrictions::class);
        $conditions = resolve(PermissionConditions::class);

        return (new Collection($this->permissionRegistry->permissions))
            ->filter(fn (PermissionDefinition $permission): bool => $principal->hasPermissionTo($permission)
                && ! $restrictions->isRestricted($permission)
                && $conditions->metBy($permission, $principal))
            ->values();
    }

    /**
     * The conditions of the permission the principal fails — none for a principal that is not
     * `Authenticatable`. See {@see PermissionConditions}.
     *
     * @return list<PermissionCondition>
     */
    public function unmetConditions(PermissionDefinition $permission, object $principal): array
    {
        return resolve(PermissionConditions::class)->unmet($permission, $principal);
    }
```

- [ ] **Step 5: Document the facade**

In `src/Facades/AccessControl.php` add to the docblock:

```php
 * @method static list<\Happenv\LaravelAccessControl\Contracts\PermissionCondition> unmetConditions(\Happenv\LaravelAccessControl\Contracts\PermissionDefinition $permission, object $principal)
```

- [ ] **Step 6: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/PermissionConditionsTest.php && vendor/bin/pest`
Expected: PASS; the whole suite PASS (`EffectivePermissionsTest` included).

- [ ] **Step 7: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/GateConfigurator.php src/AccessControl.php src/Facades/AccessControl.php tests/Feature/PermissionConditionsTest.php
git commit -m "feat: the gate and effectivePermissions() apply conditions to every account"
```

---

### Task 4: Resolution for UIs — `explainer()`, the DTO fields, `storedGrantsOf()`

**Files:**
- Modify: `src/Dto/PermissionResolutionDto.php`, `src/PermissionResolver.php`, `src/AccessControl.php`, `src/Facades/AccessControl.php`
- Test: `tests/Unit/PermissionResolverTest.php` (append), `tests/Feature/StoredGrantsTest.php` (new)

**Interfaces:**
- Consumes: `PermissionConditions::unmet()` (Task 1), `PermissionRestrictions`.
- Produces:
  - `PermissionResolutionDto` gains `public bool $restricted = false`, `public array $unmetConditions = []` (`list<PermissionCondition>`) and the computed `public bool $effective` (= `allowed && ! restricted && unmetConditions === []`). Existing named constructions keep working.
  - `PermissionResolver::explainer(Closure $stored, ?Authenticatable $principal = null): Closure(PermissionDefinition): PermissionResolutionDto`; `explain()` returns `explainer($stored)($permission)`.
  - `AccessControl::storedGrantsOf(AuthControllable $principal): Closure(PermissionDefinition): bool` — direct grants plus `roleGrantsOf()`; falls back to the principal's own `hasPermissionTo()` when it uses neither trait.
  - `AccessControl::roleGrantsOf(AuthControllable $principal): Closure(PermissionDefinition): bool` — what the principal's roles store: `HoldsGrants` roles raw, any other role asked `hasPermissionTo()`; always `false` without `HasRoles`. **Refinement of the spec:** the spec names only `storedGrantsOf()`; a UI staging changes to an account's *direct* grants needs the roles' side separately (a staged revocation of a direct grant must not hide the same permission a role stores).

- [ ] **Step 1: Write the failing resolver tests**

Append to `tests/Unit/PermissionResolverTest.php` (add `use` lines: `Happenv\LaravelAccessControl\Facades\AccessControl`, `Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\Flags`, `Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag`, `Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount`, `Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionRulePermission`, `Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Rules\ProblemPermission`):

```php
describe('explainer()', function (): void {
    beforeEach(function (): void {
        Flags::reset();
    });

    it('resolves exactly as explain() does, permission by permission (invariant 9)', function (array $stored): void {
        $resolver = resolverOver(ProblemPermission::class, BasicRulePermission::class);
        $explain = $resolver->explainer(storing(...$stored));

        foreach ([...ProblemPermission::cases(), ...BasicRulePermission::cases()] as $permission) {
            expect($explain($permission))->toEqual($resolver->explain($permission, storing(...$stored)));
        }
    })->with([
        'nothing stored' => [[]],
        'an implier stored' => [['basic-rule.update']],
        'a requirement missing' => [['basic-rule.create', 'problem.gated-p']],
        'conflicts lost' => [['problem.direct-p', 'problem.direct-c', 'problem.chain-p', 'problem.split-p', 'problem.split-x', 'problem.split-y']],
    ]);

    it('describes a permission implied by a stored one', function (): void {
        $resolution = resolverOver(BasicRulePermission::class)->explainer(storing('basic-rule.update'))(BasicRulePermission::Manage);

        expect($resolution->stored)->toBeFalse()
            ->and($resolution->granted)->toBeTrue()
            ->and($resolution->allowed)->toBeTrue()
            ->and($resolution->grantedBy)->toBe([BasicRulePermission::Update])
            ->and($resolution->restricted)->toBeFalse()
            ->and($resolution->unmetConditions)->toBe([])
            ->and($resolution->effective)->toBeTrue();
    });

    it('keeps allowed to the rules and marks a restricted permission as not in effect', function (): void {
        AccessControl::restrictUsing(fn (PermissionDefinition $permission): bool => $permission === BasicRulePermission::Plain);

        $resolution = resolverOver(BasicRulePermission::class)->explainer(storing('basic-rule.plain'))(BasicRulePermission::Plain);

        expect($resolution->allowed)->toBeTrue()
            ->and($resolution->restricted)->toBeTrue()
            ->and($resolution->effective)->toBeFalse();
    });

    it('names the conditions an account fails, and none without an account', function (): void {
        $resolver = resolverOver(ConditionRulePermission::class);

        $forAccount = $resolver->explainer(storing('condition-rule.alone'), new InMemoryAccount)(ConditionRulePermission::Alone);
        $forRole = $resolver->explainer(storing('condition-rule.alone'))(ConditionRulePermission::Alone);

        expect($forAccount->allowed)->toBeTrue()
            ->and($forAccount->unmetConditions)->toEqual([new RequiresFlag])
            ->and($forAccount->effective)->toBeFalse()
            ->and($forRole->unmetConditions)->toBe([])
            ->and($forRole->effective)->toBeTrue()
            ->and(Flags::$checks)->toBe(1);
    });

    it('reads each permission of the stored state at most once', function (): void {
        $reads = [];
        $explain = resolverOver(BasicRulePermission::class)->explainer(function (PermissionDefinition $permission) use (&$reads): bool {
            $reads[] = $permission;

            return $permission === BasicRulePermission::Update;
        });

        foreach ([...BasicRulePermission::cases(), ...BasicRulePermission::cases()] as $permission) {
            $explain($permission);
        }

        expect(count($reads))->toBe(count(array_unique(array_map(fn (PermissionDefinition $permission): string => $permission->value, $reads))));
    });

    it('answers the state it was built over; a new one sees a change', function (): void {
        $stored = ['basic-rule.plain'];

        // By reference, so only the explainer's own memo can keep the first answer.
        $isStored = function (PermissionDefinition $permission) use (&$stored): bool {
            return in_array($permission->value, $stored, true);
        };

        $resolver = resolverOver(BasicRulePermission::class);
        $before = $resolver->explainer($isStored);

        expect($before(BasicRulePermission::Plain)->stored)->toBeTrue();

        $stored = [];

        expect($before(BasicRulePermission::Plain)->stored)->toBeTrue()
            ->and($resolver->explainer($isStored)(BasicRulePermission::Plain)->stored)->toBeFalse();
    });
});
```

- [ ] **Step 2: Write the failing `storedGrantsOf()` tests**

`tests/Feature/StoredGrantsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Facades\AccessControl;
use Happenv\LaravelAccessControl\PermissionRegistry;
use Happenv\LaravelAccessControl\PermissionResolver;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryGrantRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHoldingUser;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\SelfAnsweringAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Rules\BasicRulePermission;

beforeEach(function (): void {
    resolve(PermissionRegistry::class)->register(BasicRulePermission::class);
});

describe('storedGrantsOf()', function (): void {
    it('reads the direct grants and a HoldsGrants role\'s, raw', function (): void {
        $user = new RoleHoldingUser;
        $user->permissions = ['basic-rule.plain'];
        $user->heldRoles = [new InMemoryGrantRole(['basic-rule.create'])];

        $stored = AccessControl::storedGrantsOf($user);

        // Create is read as stored although its requirement (View) is missing: raw, no rules.
        expect($stored(BasicRulePermission::Plain))->toBeTrue()
            ->and($stored(BasicRulePermission::Create))->toBeTrue()
            ->and($stored(BasicRulePermission::View))->toBeFalse()
            ->and($stored(BasicRulePermission::Update))->toBeFalse();
    });

    it('asks a role that is not HoldsGrants, which answers with its own rules applied', function (): void {
        $user = new RoleHoldingUser;
        $user->heldRoles = [new InMemoryRole(['basic-rule.update'])];

        $stored = AccessControl::storedGrantsOf($user);

        expect($stored(BasicRulePermission::Update))->toBeTrue()
            ->and($stored(BasicRulePermission::Manage))->toBeTrue()
            ->and($stored(BasicRulePermission::Plain))->toBeFalse();
    });

    it('reads a restricted permission of an asked role as absent, as HasRoles does', function (): void {
        AccessControl::restrictUsing(fn (PermissionDefinition $permission): bool => $permission === BasicRulePermission::Update);

        $user = new RoleHoldingUser;
        $user->heldRoles = [new InMemoryRole(['basic-rule.update'])];

        expect(AccessControl::storedGrantsOf($user)(BasicRulePermission::Update))->toBeFalse();
    });

    it('falls back to what a principal answering hasPermissionTo() itself says', function (): void {
        expect(AccessControl::storedGrantsOf(new SelfAnsweringAccount)(BasicRulePermission::Plain))->toBeTrue();
    });

    it('resolves, through explainer(), to what hasPermissionTo() answers', function (): void {
        $user = new RoleHoldingUser;
        $user->permissions = ['basic-rule.create'];
        $user->heldRoles = [new InMemoryGrantRole(['basic-rule.update'])];

        $explain = resolve(PermissionResolver::class)->explainer(AccessControl::storedGrantsOf($user), $user);

        foreach (BasicRulePermission::cases() as $permission) {
            expect($explain($permission)->effective)->toBe($user->hasPermissionTo($permission), $permission->name);
        }
    });
});

describe('roleGrantsOf()', function (): void {
    it('reads the roles only, never the direct grants', function (): void {
        $user = new RoleHoldingUser;
        $user->permissions = ['basic-rule.plain'];
        $user->heldRoles = [new InMemoryGrantRole(['basic-rule.create'])];

        $roles = AccessControl::roleGrantsOf($user);

        expect($roles(BasicRulePermission::Plain))->toBeFalse()
            ->and($roles(BasicRulePermission::Create))->toBeTrue();
    });

    it('reads nothing for a principal without roles', function (): void {
        expect(AccessControl::roleGrantsOf(new InMemoryAccount(['basic-rule.plain']))(BasicRulePermission::Plain))->toBeFalse();
    });
});
```

- [ ] **Step 3: Run them to see them fail**

Run: `vendor/bin/pest tests/Unit/PermissionResolverTest.php tests/Feature/StoredGrantsTest.php`
Expected: FAIL — `Call to undefined method ...PermissionResolver::explainer()`, `storedGrantsOf()`, `roleGrantsOf()`.

- [ ] **Step 4: Extend the DTO**

Replace `src/Dto/PermissionResolutionDto.php` with:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Dto;

use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Why a permission is or is not in effect — for a UI, which has to say more than `false`.
 *
 * `allowed` is the rules between permissions alone. What else withholds a permission is told apart:
 * a runtime restriction, which withholds it from everyone, and the conditions an account fails.
 * `effective` sums it up — for a principal using the library's traits, what `hasPermissionTo()`
 * answers over the same stored grants.
 */
final readonly class PermissionResolutionDto
{
    /** Allowed by the rules, not restricted, and every condition met. */
    public bool $effective;

    public function __construct(
        /** Effective by the rules. */
        public bool $allowed,
        /** In the grants as given. */
        public bool $stored,
        /** Stored, or stored by anything that implies it. */
        public bool $granted,
        /**
         * The permissions it declares itself implied by that are granted — each by something other
         * than this permission, so a cycle of implications does not name its own members.
         *
         * @var list<PermissionDefinition>
         */
        public array $grantedBy,
        /**
         * The permissions it requires that are not active.
         *
         * @var list<PermissionDefinition>
         */
        public array $missing,
        /**
         * The permissions it conflicts with that are active.
         *
         * @var list<PermissionDefinition>
         */
        public array $conflicting,
        /** A runtime restriction withholds it — from every principal. */
        public bool $restricted = false,
        /**
         * The conditions of it the account fails. Empty when no account was given: a role is never
         * evaluated against conditions.
         *
         * @var list<PermissionCondition>
         */
        public array $unmetConditions = [],
    ) {
        $this->effective = $allowed && ! $restricted && $unmetConditions === [];
    }
}
```

- [ ] **Step 5: Add `explainer()`**

In `src/PermissionResolver.php` add `use Illuminate\Contracts\Auth\Authenticatable;` and replace `explain()`:

```php
    /**
     * Why the permission is or is not effective. The closure can describe a form's UNSAVED state as
     * well as a model, which is what a role editor needs.
     *
     * @param  Closure(PermissionDefinition): bool  $stored
     */
    public function explain(PermissionDefinition $permission, Closure $stored): PermissionResolutionDto
    {
        return $this->explainer($stored)($permission);
    }

    /**
     * {@see self::explain()} for as many permissions as asked, over ONE evaluation of the stored
     * state — a permission screen asks permissions × holders times, and each `explain()` starts from
     * nothing.
     *
     * The closure reads each permission of the stored state at most once and keeps the answer: build
     * a new one after the state changes. Given an account, it names the conditions the account fails;
     * without one — a role — it names none.
     *
     * @param  Closure(PermissionDefinition): bool  $stored
     * @return Closure(PermissionDefinition): PermissionResolutionDto
     */
    public function explainer(Closure $stored, ?Authenticatable $principal = null): Closure
    {
        $evaluation = new PermissionEvaluation($this->graph, $stored);

        // Resolved now rather than injected, as AccessControl::restrictUsing() explains.
        $restrictions = resolve(PermissionRestrictions::class);
        $conditions = resolve(PermissionConditions::class);

        return fn (PermissionDefinition $permission): PermissionResolutionDto => new PermissionResolutionDto(
            allowed: $evaluation->allowed($permission),
            stored: $evaluation->stored($permission),
            granted: $evaluation->granted($permission),
            grantedBy: array_values(array_filter(
                $this->graph->directImpliers($permission),
                fn (PermissionDefinition $implier): bool => $evaluation->grantedAvoiding($implier, $permission),
            )),
            missing: array_values(array_filter(
                $this->graph->requirements($permission),
                fn (PermissionDefinition $required): bool => ! $evaluation->active($required),
            )),
            conflicting: array_values(array_filter(
                $this->graph->conflicts($permission),
                $evaluation->active(...),
            )),
            restricted: $restrictions->isRestricted($permission),
            unmetConditions: $principal === null ? [] : $conditions->unmet($permission, $principal),
        );
    }
```

Update the class docblock's paragraph "Restrictions are NOT applied here." to: "Restrictions are NOT applied to `allows()`: they belong outermost and to the permission asked about only, so the traits apply them; applied inside, a read-only mode withholding `Update` would also take away what `Update` implies. `explainer()` reports them — and an account's conditions — beside the rules, never inside them."

- [ ] **Step 6: Add `storedGrantsOf()` and `roleGrantsOf()`**

In `src/AccessControl.php` add `use Closure;`, `use Happenv\LaravelAccessControl\Contracts\HoldsGrants;`, `use Happenv\LaravelAccessControl\Traits\HasPermissions;`, `use Happenv\LaravelAccessControl\Traits\HasRoles;` and the methods:

```php
    /**
     * What the principal STORES, raw — its direct grants and its roles' — for a resolution over it
     * that a UI can put staged changes on top of (see {@see PermissionResolver::explainer()}).
     *
     * The library's traits are recognised by NAME, as the diagrams recognise them: a method called
     * `getGrants()` on any class could return anything. A principal using neither is only asked what
     * it answers.
     *
     * @return Closure(PermissionDefinition): bool
     */
    public function storedGrantsOf(AuthControllable $principal): Closure
    {
        $traits = class_uses_recursive($principal);

        // Checked inline, so static analysis sees getGrants() exists where it is called.
        $direct = isset($traits[HasPermissions::class]) && method_exists($principal, 'getGrants')
            ? $this->valuesOf($principal->getGrants())
            : null;

        // Neither trait: nothing stored can be read, and what the principal answers is all there is.
        if ($direct === null && ! isset($traits[HasRoles::class])) {
            return $principal->hasPermissionTo(...);
        }

        $direct ??= [];
        $roles = $this->roleGrantsOf($principal);

        return fn (PermissionDefinition $permission): bool => isset($direct[$permission->value]) || $roles($permission);
    }

    /**
     * What the principal's ROLES store, read as `HasRoles` reads them: a {@see HoldsGrants} role
     * hands over its raw grants, any other role is asked `hasPermissionTo()` — its own rules and any
     * restriction applied. Nothing for a principal without `HasRoles`.
     *
     * @return Closure(PermissionDefinition): bool
     */
    public function roleGrantsOf(AuthControllable $principal): Closure
    {
        if (! isset(class_uses_recursive($principal)[HasRoles::class]) || ! method_exists($principal, 'getRoles')) {
            return fn (PermissionDefinition $permission): bool => false;
        }

        $stored = [];

        /** @var list<Closure(PermissionDefinition): bool> $asked */
        $asked = [];

        foreach ($principal->getRoles() as $role) {
            if ($role instanceof HoldsGrants) {
                $stored += $this->valuesOf($role->getGrants());
            } elseif (is_object($role) && method_exists($role, 'hasPermissionTo')) {
                // HasRoles asks its roles by duck typing, so a role need not declare AuthControllable.
                $asked[] = $role->hasPermissionTo(...);
            }
        }

        return function (PermissionDefinition $permission) use ($stored, $asked): bool {
            if (isset($stored[$permission->value])) {
                return true;
            }

            foreach ($asked as $asks) {
                if ($asks($permission)) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * @param  iterable<int|string>  $values
     * @return array<array-key, true>
     */
    private function valuesOf(iterable $values): array
    {
        $set = [];

        foreach ($values as $value) {
            $set[$value] = true;
        }

        return $set;
    }
```

- [ ] **Step 7: Document the facade**

In `src/Facades/AccessControl.php` add:

```php
 * @method static \Closure storedGrantsOf(\Happenv\LaravelAccessControl\Contracts\AuthControllable $principal)
 * @method static \Closure roleGrantsOf(\Happenv\LaravelAccessControl\Contracts\AuthControllable $principal)
```

- [ ] **Step 8: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Unit/PermissionResolverTest.php tests/Feature/StoredGrantsTest.php && vendor/bin/pest`
Expected: PASS. The existing `toEqual(new PermissionResolutionDto(...))` assertions in `PermissionResolverTest` still pass (the new fields default and `effective` is computed the same way on both sides).

- [ ] **Step 9: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse && composer test:type-coverage`

```bash
git add src/Dto/PermissionResolutionDto.php src/PermissionResolver.php src/AccessControl.php src/Facades/AccessControl.php tests/Unit/PermissionResolverTest.php tests/Feature/StoredGrantsTest.php
git commit -m "feat: explainer(), storedGrantsOf() and roleGrantsOf() — resolutions a UI can stage changes on"
```

---

### Task 5: Interaction examples and invariants

**Files:**
- Modify: `tests/Pest.php` (helpers)
- Test: `tests/Feature/ConditionInteractionsTest.php` (new)

**Interfaces:**
- Consumes: everything from Tasks 1–4.
- Produces (test helpers in `tests/Pest.php`): `accountStoring(string ...$values): User`; `expectInEffect(AuthControllable&Authenticatable $account, PermissionDefinition $permission, bool $expected): void` — asserts `hasPermissionTo()`, the Gate, `effectivePermissions()` and `explainer(storedGrantsOf())` all agree.

This task adds tests only. They must pass against Tasks 1–4; a failure here is a bug in those tasks — fix it there and note the fix in the commit message.

- [ ] **Step 1: Add the helpers**

Append to `tests/Pest.php` (with `use` lines `Happenv\LaravelAccessControl\Contracts\AuthControllable`, `Happenv\LaravelAccessControl\Facades\AccessControl`, `Happenv\LaravelAccessControl\Tests\Fixtures\Models\User`, `Illuminate\Contracts\Auth\Authenticatable`, `Illuminate\Support\Facades\Gate`):

```php
/**
 * A user fixture storing the given permission values directly, holding no role.
 */
function accountStoring(string ...$values): User
{
    $user = new User;
    $user->permissions = $values;

    return $user;
}

/**
 * Every way the library answers "may this account act on it now" — the trait, the gate, the list and
 * the explainer over what it stores — must give the same answer.
 */
function expectInEffect(AuthControllable&Authenticatable $account, PermissionDefinition $permission, bool $expected): void
{
    $explained = resolve(PermissionResolver::class)->explainer(AccessControl::storedGrantsOf($account), $account)($permission);

    expect($account->hasPermissionTo($permission))->toBe($expected, $permission->name . ': hasPermissionTo()')
        ->and(Gate::forUser($account)->allows($permission))->toBe($expected, $permission->name . ': the gate')
        ->and(AccessControl::effectivePermissions($account)->contains($permission))->toBe($expected, $permission->name . ': effectivePermissions()')
        ->and($explained->effective)->toBe($expected, $permission->name . ': explainer()');
}
```

- [ ] **Step 2: Write the tests**

`tests/Feature/ConditionInteractionsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Facades\AccessControl;
use Happenv\LaravelAccessControl\GateConfigurator;
use Happenv\LaravelAccessControl\PermissionConditions;
use Happenv\LaravelAccessControl\PermissionRegistry;
use Happenv\LaravelAccessControl\PermissionResolver;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\Flags;
use Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryGrantRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryRole;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHolder;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\RoleHoldingAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Models\SelfAnsweringAccount;
use Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionRulePermission;
use Illuminate\Support\Facades\Gate;

beforeEach(function (): void {
    Flags::reset();

    resolve(PermissionRegistry::class)->register(ConditionRulePermission::class);
    resolve(GateConfigurator::class)->configure();
});

function restrictTheRestricted(): void
{
    AccessControl::restrictUsing(fn (PermissionDefinition $permission): bool => $permission === ConditionRulePermission::Restricted);
}

/**
 * @return list<string>
 */
function everyConditionRuleValue(): array
{
    return array_column(ConditionRulePermission::cases(), 'value');
}

describe('rules, restrictions and conditions (the spec\'s interaction examples)', function (): void {
    it('does not carry a requirement\'s condition to what requires it', function (): void {
        $account = accountStoring('condition-rule.requires-guarded', 'condition-rule.guarded-requirement');

        expectInEffect($account, ConditionRulePermission::GuardedRequirement, false);
        expectInEffect($account, ConditionRulePermission::RequiresGuarded, true);
    });

    it('withholds the permission carrying the condition, not its requirement', function (): void {
        $account = accountStoring('condition-rule.guarded-requirer', 'condition-rule.plain-requirement');

        expectInEffect($account, ConditionRulePermission::GuardedRequirer, false);
        expectInEffect($account, ConditionRulePermission::PlainRequirement, true);
    });

    it('does not carry an implier\'s condition to what it implies', function (): void {
        $account = accountStoring('condition-rule.guarded-implier');

        expectInEffect($account, ConditionRulePermission::GuardedImplier, false);
        expectInEffect($account, ConditionRulePermission::ImpliedByGuarded, true);
    });

    it('lets a permission the account cannot use still win a conflict', function (): void {
        $account = accountStoring('condition-rule.conflicts-with-guarded', 'condition-rule.guarded-conflict');

        expectInEffect($account, ConditionRulePermission::ConflictsWithGuarded, false);
        expectInEffect($account, ConditionRulePermission::GuardedConflict, false);
    });

    it('reads a restricted requirement as stored where grants are read raw, and as absent where a role is asked', function (): void {
        restrictTheRestricted();

        $direct = accountStoring('condition-rule.requires-restricted', 'condition-rule.restricted');
        $throughGrants = new RoleHoldingAccount([new InMemoryGrantRole(['condition-rule.requires-restricted', 'condition-rule.restricted'])]);
        $throughAnswers = new RoleHoldingAccount([new InMemoryRole(['condition-rule.requires-restricted', 'condition-rule.restricted'])]);

        expectInEffect($direct, ConditionRulePermission::Restricted, false);
        expectInEffect($direct, ConditionRulePermission::RequiresRestricted, true);
        expectInEffect($throughGrants, ConditionRulePermission::RequiresRestricted, true);
        expectInEffect($throughAnswers, ConditionRulePermission::RequiresRestricted, false);
    });

    it('explains a permission the rules allow and a condition withholds', function (): void {
        $account = accountStoring('condition-rule.alone');

        expectInEffect($account, ConditionRulePermission::Alone, false);

        $resolution = resolve(PermissionResolver::class)->explainer(AccessControl::storedGrantsOf($account), $account)(ConditionRulePermission::Alone);

        expect($resolution->allowed)->toBeTrue()
            ->and($resolution->effective)->toBeFalse()
            ->and($resolution->unmetConditions)->toEqual([new RequiresFlag]);
    });

    it('puts the permissions back once the account meets the condition', function (): void {
        $account = accountStoring('condition-rule.alone', 'condition-rule.requires-guarded', 'condition-rule.guarded-requirement');

        Flags::raise($account);

        expectInEffect($account, ConditionRulePermission::Alone, true);
        expectInEffect($account, ConditionRulePermission::GuardedRequirement, true);
        expectInEffect($account, ConditionRulePermission::RequiresGuarded, true);
    });
});

describe('invariants', function (): void {
    it('1 — a condition never changes stored grants', function (): void {
        $account = accountStoring('condition-rule.alone');

        $account->hasPermissionTo(ConditionRulePermission::Alone);
        Gate::forUser($account)->allows(ConditionRulePermission::Alone);
        AccessControl::effectivePermissions($account);

        expect($account->permissions)->toBe(['condition-rule.alone']);
    });

    it('2 — a condition withholds its own permission only', function (): void {
        $account = accountStoring('condition-rule.guarded-implier', 'condition-rule.requires-guarded', 'condition-rule.guarded-requirement');

        expectInEffect($account, ConditionRulePermission::ImpliedByGuarded, true);
        expectInEffect($account, ConditionRulePermission::RequiresGuarded, true);
    });

    it('3 — a principal that is not Authenticatable is never evaluated', function (): void {
        $role = new InMemoryRole(['condition-rule.alone']);
        $holder = new RoleHolder([new InMemoryGrantRole(['condition-rule.alone'])]);

        expect($role->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue()
            ->and(AccessControl::effectivePermissions($role)->all())->toContain(ConditionRulePermission::Alone)
            ->and($holder->hasPermissionTo(ConditionRulePermission::Alone))->toBeTrue()
            ->and(AccessControl::effectivePermissions($holder)->all())->toContain(ConditionRulePermission::Alone)
            ->and(resolve(PermissionResolver::class)->explainer(AccessControl::storedGrantsOf($role))(ConditionRulePermission::Alone)->effective)->toBeTrue()
            ->and(Flags::$checks)->toBe(0);
    });

    it('4 — effectivePermissions() holds nothing restricted or with an unmet condition', function (AuthControllable $principal): void {
        restrictTheRestricted();

        $conditions = resolve(PermissionConditions::class);

        foreach (AccessControl::effectivePermissions($principal) as $permission) {
            expect(AccessControl::isRestricted($permission))->toBeFalse($permission->name)
                ->and($conditions->unmet($permission, $principal))->toBe([], $permission->name);
        }
    })->with([
        'a user storing everything' => fn (): AuthControllable => accountStoring(...everyConditionRuleValue()),
        'an account answering hasPermissionTo() itself' => fn (): AuthControllable => new SelfAnsweringAccount,
    ]);

    it('5 — hasPermissionTo() is true exactly for what effectivePermissions() lists', function (AuthControllable $principal): void {
        restrictTheRestricted();

        $effective = AccessControl::effectivePermissions($principal)->all();

        foreach (ConditionRulePermission::cases() as $permission) {
            expect($principal->hasPermissionTo($permission))->toBe(in_array($permission, $effective, true), $permission->name);
        }
    })->with([
        'a user (HasRolesAndPermissions)' => fn (): AuthControllable => accountStoring(...everyConditionRuleValue()),
        'a machine user (HasPermissions)' => fn (): AuthControllable => new InMemoryAccount(everyConditionRuleValue()),
        'an account holding a role (HasRoles)' => fn (): AuthControllable => new RoleHoldingAccount([new InMemoryGrantRole(everyConditionRuleValue())]),
        'a user meeting the condition' => fn (): AuthControllable => tap(accountStoring(...everyConditionRuleValue()), fn (object $account) => Flags::raise($account)),
    ]);

    it('6 — getGrants() returns what is stored, whatever withholds it', function (): void {
        restrictTheRestricted();

        $stored = ['condition-rule.alone', 'condition-rule.restricted', 'condition-rule.conflicts-with-guarded', 'condition-rule.guarded-conflict'];
        $account = new InMemoryAccount($stored);

        expect(collect($account->getGrants())->all())->toBe($stored);
    });
});
```

Note on dataset closures: Pest resolves a closure in a dataset *inside* the test, after `beforeEach`, so `Flags::reset()` runs before `Flags::raise()` in the last row of test 5.

- [ ] **Step 3: Run the tests**

Run: `vendor/bin/pest tests/Feature/ConditionInteractionsTest.php`
Expected: PASS (all rows). A failing row points at a bug in Tasks 2–4; fix it there, re-run the whole suite.

- [ ] **Step 4: Commit**

```bash
git add tests/Pest.php tests/Feature/ConditionInteractionsTest.php
git commit -m "test: the spec's interaction examples and invariants, through every entry point"
```

---

### Task 6: `PermissionDto::$conditions` and `problemDetails()`

**Files:**
- Create: `src/PermissionProblemType.php`, `src/Dto/PermissionProblemDto.php`
- Modify: `src/PermissionGraph.php:problems`, `src/Dto/PermissionDto.php`, `src/PermissionCollection.php`
- Test: `tests/Unit/PermissionGraphTest.php` (append), `tests/Unit/PermissionCollectionTest.php` (append)

**Interfaces:**
- Produces: `PermissionProblemType` (backed enum: `RequiresConflicting = 'requires-conflicting'`, `ImpliesConflicting = 'implies-conflicting'`, `UnregisteredTarget = 'unregistered-target'`); `Dto\PermissionProblemDto(PermissionProblemType $type, PermissionDefinition $permission, PermissionDefinition $other, PermissionRuleType $ruleType)`; `PermissionGraph::problemDetails(): list<PermissionProblemDto>`; `PermissionDto::$conditions` (`list<PermissionCondition>`, default `[]`); `PermissionCollection::__construct(PermissionRegistry $registry, ?PermissionGraph $graph = null, ?PermissionConditions $conditions = null)`.

- [ ] **Step 1: Write the failing tests**

Append inside the `describe('problems', ...)` block of `tests/Unit/PermissionGraphTest.php` (add `use` lines: `Happenv\LaravelAccessControl\Dto\PermissionProblemDto`, `Happenv\LaravelAccessControl\PermissionProblemType`, `Happenv\LaravelAccessControl\PermissionRuleType`, `Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Rules\UnregisteredPermission` if not already imported):

```php
        it('describes each problem as data, in declaration order', function (): void {
            expect(graphOver(ProblemPermission::class)->problemDetails())->toEqual([
                new PermissionProblemDto(PermissionProblemType::RequiresConflicting, ProblemPermission::DirectP, ProblemPermission::DirectC, PermissionRuleType::ConflictsWith),
                new PermissionProblemDto(PermissionProblemType::RequiresConflicting, ProblemPermission::TransitiveP, ProblemPermission::TransitiveC, PermissionRuleType::ConflictsWith),
                new PermissionProblemDto(PermissionProblemType::ImpliesConflicting, ProblemPermission::ImpliesP, ProblemPermission::ImpliesC, PermissionRuleType::ConflictsWith),
                new PermissionProblemDto(PermissionProblemType::ImpliesConflicting, ProblemPermission::ChainP, ProblemPermission::ChainC, PermissionRuleType::ConflictsWith),
                new PermissionProblemDto(PermissionProblemType::UnregisteredTarget, ProblemPermission::OrphanP, UnregisteredPermission::Orphan, PermissionRuleType::Requires),
            ]);
        });

        it('words the same problems problems() lists, one to one (invariant 8)', function (): void {
            $graph = graphOver(ProblemPermission::class);
            $sentences = $graph->problems();

            expect($sentences)->toHaveCount(count($graph->problemDetails()));

            foreach ($graph->problemDetails() as $index => $problem) {
                expect($sentences[$index])
                    ->toStartWith(ProblemPermission::class . '::' . $problem->permission->name . ' ')
                    ->toContain($problem->other::class . '::' . $problem->other->name);
            }
        });

        it('has no details about a sound catalogue', function (): void {
            expect(graphOver(ProductPermission::class, GalleryPermission::class)->problemDetails())->toBe([]);
        });
```

Append to `tests/Unit/PermissionCollectionTest.php` (add `use` lines: `Happenv\LaravelAccessControl\PermissionCollection`, `Happenv\LaravelAccessControl\PermissionRegistry`, `Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\RequiresFlag`, `Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionedPermission` if not already imported):

```php
it('attaches every condition of a permission, the enum\'s first', function (): void {
    $registry = new PermissionRegistry;
    $registry->register(ConditionedPermission::class);

    $permissions = (new PermissionCollection($registry))->getPermissions();

    expect($permissions->firstWhere('slug', 'conditioned.plain')->conditions)->toEqual([new RequiresFlag('verified')])
        ->and($permissions->firstWhere('slug', 'conditioned.guarded')->conditions)->toEqual([new RequiresFlag('verified'), new RequiresFlag('mfa')]);
});
```

- [ ] **Step 2: Run them to see them fail**

Run: `vendor/bin/pest tests/Unit/PermissionGraphTest.php tests/Unit/PermissionCollectionTest.php`
Expected: FAIL — `problemDetails()` undefined, `PermissionProblemDto` not found, `conditions` property undefined.

- [ ] **Step 3: Write the enum and the DTO**

`src/PermissionProblemType.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl;

/**
 * What is wrong with a declaration — see {@see PermissionGraph::problemDetails()}.
 */
enum PermissionProblemType: string
{
    /** It requires, directly or through a requirement, a permission it conflicts with. */
    case RequiresConflicting = 'requires-conflicting';

    /** It implies, directly or through an implication, a permission it conflicts with that needs nothing else. */
    case ImpliesConflicting = 'implies-conflicting';

    /** A rule of it points at a permission whose enum is not registered. */
    case UnregisteredTarget = 'unregistered-target';
}
```

`src/Dto/PermissionProblemDto.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\LaravelAccessControl\Dto;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\PermissionProblemType;
use Happenv\LaravelAccessControl\PermissionRuleType;

/**
 * One declaration problem as data — for a UI that words it in its own language and marks the rows
 * involved.
 */
final readonly class PermissionProblemDto
{
    public function __construct(
        public PermissionProblemType $type,
        /** The permission whose declaration is wrong: the one that can never be allowed, or whose rule points nowhere. */
        public PermissionDefinition $permission,
        /** The permission the problem is about: the one it conflicts with, or the unregistered target. */
        public PermissionDefinition $other,
        /** The rule at fault: `ConflictsWith` for a conflict, the rule's own type for an unregistered target. */
        public PermissionRuleType $ruleType,
    ) {}
}
```

- [ ] **Step 4: Build `problems()` from `problemDetails()`**

In `src/PermissionGraph.php` add `use Happenv\LaravelAccessControl\Dto\PermissionProblemDto;`. Replace the body of `problems()` and add `problemDetails()` right after it (keep `problems()`'s existing docblock):

```php
    public function problems(): array
    {
        return array_map(fn (PermissionProblemDto $problem): string => match ($problem->type) {
            PermissionProblemType::RequiresConflicting => sprintf(
                '%s can never be allowed: it requires %s, which it conflicts with.',
                $this->describe($problem->permission),
                $this->describe($problem->other),
            ),
            PermissionProblemType::ImpliesConflicting => sprintf(
                '%s can never be allowed: it implies %s, which it conflicts with.',
                $this->describe($problem->permission),
                $this->describe($problem->other),
            ),
            PermissionProblemType::UnregisteredTarget => sprintf(
                '%s declares %s about %s, whose enum is not registered.',
                $this->describe($problem->permission),
                $problem->ruleType->name,
                $this->describe($problem->other),
            ),
        }, $this->problemDetails());
    }

    /**
     * The same problems as {@see self::problems()}, as data — for a UI that words them itself, in
     * the operator's language, and marks the rows involved.
     *
     * @return list<PermissionProblemDto>
     */
    public function problemDetails(): array
    {
        $this->compile();

        $problems = [];

        foreach ($this->conflicts as $key => $conflicting) {
            $permission = $this->declaring[$key];
            $required = $this->requirementClosure($permission);

            foreach ($conflicting as $other) {
                if (isset($required[$other->value])) {
                    $problems[] = new PermissionProblemDto(PermissionProblemType::RequiresConflicting, $permission, $other, PermissionRuleType::ConflictsWith);
                } elseif (! isset($this->requirements[$other->value]) && in_array($permission, $this->impliers[$other->value] ?? [], true)) {
                    // Certain only while the implied permission needs nothing: granted, it is active.
                    $problems[] = new PermissionProblemDto(PermissionProblemType::ImpliesConflicting, $permission, $other, PermissionRuleType::ConflictsWith);
                }
            }
        }

        foreach ($this->rules as $rule) {
            if (($this->registry->permissions[$rule->other->value] ?? null) !== $rule->other) {
                $problems[] = new PermissionProblemDto(PermissionProblemType::UnregisteredTarget, $rule->permission, $rule->other, $rule->type);
            }
        }

        return $problems;
    }
```

- [ ] **Step 5: Attach conditions to `PermissionDto`**

In `src/Dto/PermissionDto.php` add `use Happenv\LaravelAccessControl\Contracts\PermissionCondition;` and, after `$rules`, a new promoted property:

```php
        /**
         * The conditions an account must meet for it to be in effect, the enum's first — see
         * {@see PermissionCondition}. Attached by {@see PermissionCollection}; empty on a DTO built by
         * {@see PermissionReflector} alone.
         *
         * @var list<PermissionCondition>
         */
        public array $conditions = [],
```

In `src/PermissionCollection.php`:

```php
    private PermissionGraph $graph;

    private PermissionConditions $conditions;

    public function __construct(
        private PermissionRegistry $registry,
        ?PermissionGraph $graph = null,
        ?PermissionConditions $conditions = null,
    ) {
        // Optional, so a collection built over a registry of its own keeps working: its graph has to
        // index THAT registry, which the container's does not.
        $this->graph = $graph ?? new PermissionGraph($registry);

        // Facts about the code, like the rules — a collection of its own may read them afresh.
        $this->conditions = $conditions ?? new PermissionConditions;
    }
```

and in `attachRules()`, after `$permission->rules = $rules;`:

```php
            $permission->conditions = $this->conditions->for($permission->enum);
```

Extend `attachRules()`'s docblock first line to: "Put every rule on BOTH of its ends, its reason read now — in this request's locale, never in that of whoever compiled the graph — and every condition on its permission."

- [ ] **Step 6: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Unit/PermissionGraphTest.php tests/Unit/PermissionCollectionTest.php && vendor/bin/pest`
Expected: PASS; the existing `problems` tests unchanged and green.

- [ ] **Step 7: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/PermissionProblemType.php src/Dto/PermissionProblemDto.php src/PermissionGraph.php src/Dto/PermissionDto.php src/PermissionCollection.php tests/Unit/PermissionGraphTest.php tests/Unit/PermissionCollectionTest.php
git commit -m "feat: problemDetails() and PermissionDto::\$conditions for permission UIs"
```

---

### Task 7: Diagram state `unmet-condition`, schema version 2

**Files:**
- Modify: `src/Diagram/PermissionState.php`, `src/Diagram/Renderer/Support/StatePalette.php`, `src/Diagram/PrincipalDiagramBuilder.php:state`, `src/Diagram/PermissionDiagram.php`, `src/Commands/PermissionGraphCommand.php`
- Test: `tests/Feature/Diagram/PrincipalDiagramTest.php` (append), `tests/Unit/Diagram/PermissionDiagramTest.php:17`, `tests/Feature/Diagram/PermissionGraphCommandTest.php:106`

**Interfaces:**
- Consumes: `PermissionConditions::unmet()` (Task 1).
- Produces: `PermissionState::UnmetCondition = 'unmet-condition'`; `PermissionDiagram::SCHEMA_VERSION = 2`; `permission:graph --schema-version` defaults to `2`.

- [ ] **Step 1: Write the failing tests**

Append to `tests/Feature/Diagram/PrincipalDiagramTest.php` (add `use` lines: `Happenv\LaravelAccessControl\Facades\AccessControl`, `Happenv\LaravelAccessControl\PermissionRegistry`, `Happenv\LaravelAccessControl\Diagram\PermissionState`, `Happenv\LaravelAccessControl\Tests\Fixtures\Conditions\Flags`, `Happenv\LaravelAccessControl\Tests\Fixtures\Models\InMemoryAccount`, `Happenv\LaravelAccessControl\Tests\Fixtures\Models\SelfAnsweringAccount`, `Happenv\LaravelAccessControl\Tests\Fixtures\Permissions\Conditions\ConditionRulePermission` where missing):

```php
describe('conditions', function (): void {
    beforeEach(function (): void {
        Flags::reset();

        resolve(PermissionRegistry::class)->register(ConditionRulePermission::class);
    });

    it('draws a permission the account fails a condition of as unmet-condition', function (): void {
        $account = new InMemoryAccount(['condition-rule.alone']);

        expect(AccessControl::diagram()->forPrincipal($account)->node('permission:condition-rule.alone')?->state)
            ->toBe(PermissionState::UnmetCondition);

        Flags::raise($account);

        expect(AccessControl::diagram()->forPrincipal($account)->node('permission:condition-rule.alone')?->state)
            ->toBe(PermissionState::Allowed);
    });

    it('draws it so for an account answering hasPermissionTo() itself too', function (): void {
        expect(AccessControl::diagram()->forPrincipal(new SelfAnsweringAccount)->node('permission:condition-rule.alone')?->state)
            ->toBe(PermissionState::UnmetCondition);
    });

    it('colours the state in Mermaid', function (): void {
        $diagram = AccessControl::diagram()->forPrincipal(new InMemoryAccount(['condition-rule.alone']));

        expect(AccessControl::diagram()->render($diagram, 'mermaid'))->toContain('classDef state_unmet_condition');
    });
});
```

Change `tests/Unit/Diagram/PermissionDiagramTest.php:17` to expect `'version' => 2`, and `tests/Feature/Diagram/PermissionGraphCommandTest.php:106` to:

```php
        'an unsupported schema version' => [['--schema-version' => '1'], 'Unsupported schema version [1]. Supported: 2.'],
```

- [ ] **Step 2: Run them to see them fail**

Run: `vendor/bin/pest tests/Feature/Diagram tests/Unit/Diagram`
Expected: FAIL — `PermissionState::UnmetCondition` undefined; version 1 ≠ 2.

- [ ] **Step 3: Add the state and its colours**

`src/Diagram/PermissionState.php` — insert after `Restricted`:

```php
    case UnmetCondition = 'unmet-condition';
```

`src/Diagram/Renderer/Support/StatePalette.php` — add an arm to both matches:

```php
            PermissionState::UnmetCondition => '#ffedd5',
```

in `fill()`, and

```php
            PermissionState::UnmetCondition => '#ea580c',
```

in `stroke()`.

- [ ] **Step 4: Decide the state with conditions**

In `src/Diagram/PrincipalDiagramBuilder.php` add `use Happenv\LaravelAccessControl\PermissionConditions;`, a constructor dependency `private PermissionConditions $conditions,` (after `$restrictions`), and replace `state()`:

```php
    /**
     * @param  Closure(PermissionDefinition): bool  $isStored
     */
    private function state(AuthControllable $principal, PermissionDefinition $permission, Closure $isStored): PermissionState
    {
        $resolution = $this->resolver->explain($permission, $isStored);
        $acts = $principal->hasPermissionTo($permission);

        // Asked of every account — also one answering hasPermissionTo() itself — as the gate asks.
        $unmet = $this->conditions->unmet($permission, $principal) !== [];

        if ($acts && ! $unmet) {
            if ($isStored($permission)) {
                return PermissionState::Allowed;
            }

            // Implied only when the rules say so; otherwise the principal allowed it on its own say.
            return $resolution->allowed ? PermissionState::Implied : PermissionState::Overridden;
        }

        if ($resolution->allowed || $acts) {
            return match (true) {
                $this->restrictions->isRestricted($permission) => PermissionState::Restricted,
                $unmet => PermissionState::UnmetCondition,
                default => PermissionState::Denied,
            };
        }

        if (! $resolution->granted) {
            return PermissionState::NotGranted;
        }

        // Granted and not allowed: a requirement is missing, or — every requirement active — a
        // conflict is lost.
        return $resolution->missing !== [] ? PermissionState::MissingRequirement : PermissionState::Conflict;
    }
```

- [ ] **Step 5: Move the schema to version 2**

`src/Diagram/PermissionDiagram.php`: `public const int SCHEMA_VERSION = 2;` and extend the class docblock with: "Version 2 added the node state `unmet-condition`."

`src/Commands/PermissionGraphCommand.php`: change the signature line to `{--schema-version=2 : Machine API schema version}`.

- [ ] **Step 6: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Diagram tests/Unit/Diagram && vendor/bin/pest`
Expected: PASS.

- [ ] **Step 7: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/Diagram src/Commands/PermissionGraphCommand.php tests/Feature/Diagram tests/Unit/Diagram
git commit -m "feat: diagrams draw a permission withheld by a condition as unmet-condition (schema 2)"
```

---

### Task 8: Documentation, full gates, pull request

**Files:**
- Modify: `README.md`, `CHANGELOG.md`

- [ ] **Step 1: README — section 9**

Add after section "### 8. Drawing Permission Graphs (Optional)" (before "## How Voters Work"):

````markdown
### 9. Conditions on Accounts (Optional)

A condition withholds a permission from an **account** that does not meet it — one without
multi-factor authentication, one whose e-mail is not verified — whatever it was granted. It is an
attribute on a permission enum (it then guards every case) or on one case, and the attribute class
implements `Contracts\PermissionCondition`. You write your own; nothing is registered, the library
finds the attribute by its interface.

```php
use Attribute;
use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

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

```php
#[PermissionGroup(OrderGroup::class)]
enum OrderPermission: string implements PermissionDefinition
{
    case View = 'order.view';

    #[RequiresVerifiedEmail]
    case Refund = 'order.refund';
}
```

`check()` is handed the permission it guards, so one attribute can guard many permissions and still
tell them apart. A condition on the enum and one on the case add up: the account must meet both.
`DescribesPermissionCondition` is optional — it names the condition in a permission UI.

#### Who is an account

A condition applies to a principal **if and only if it is `Authenticatable`**:

| Principal | Conditions |
|---|---|
| A user (`HasRoles` / `HasRolesAndPermissions`) | ✓ |
| A machine user, an API key (`HasPermissions`) | ✓ |
| An account answering `hasPermissionTo()` itself | ✓ at the gate and in `effectivePermissions()`; its own `hasPermissionTo()` answers what it answers |
| A role (`HasPermissions`, `HoldsGrants`) | ✗ — a role stores grants, it does not sign in |
| Anything else holding roles that does not sign in (a team) | ✗ |
| No user at all | the gate refuses it as `Unauthenticated.` first |

#### Where conditions apply

Last — after restrictions and the rules between permissions:

| Place | |
|---|---|
| The gate (`can()`, `Gate::allows()`, `authorize()`) | an unmet condition is refused with the same message as a missing permission |
| `hasPermissionTo()` of the traits | `false`; never remembered in the per-instance memo of `HasRoles` |
| `effectivePermissions()` / `getEffectivePermissions()` | left out — for every account, also one answering `hasPermissionTo()` itself |

A condition withholds its own permission only. What it implies, what requires it and what it
conflicts with are resolved as if the account could use it: with `B #[ImpliedBy(A)]` and a condition
on `A`, an account storing `A` without meeting the condition has `B` but not `A`.

A `Gate::before()` callback that answers first skips the permission's gate — its conditions included.

#### Writing a condition

`check()` **must** be free of side effects and idempotent within a request, and **should** avoid
I/O: it runs on every check — twice in one gate check, once in the trait and once in the gate — and
its answer is never cached, since a cache would outlive a change in the account (MFA switched on
mid-request). An exception thrown by `check()` reaches the caller.

#### Conditions in a permission UI

- `PermissionDto::$conditions` lists a permission's conditions; `AccessControl::unmetConditions($permission, $account)` the ones an account fails.
- `PermissionResolver::explainer($stored, $account)` resolves many permissions over one stored state. Its `PermissionResolutionDto` adds `restricted`, `unmetConditions` and `effective` (allowed, not restricted, every condition met) to what `explain()` returned.
- `AccessControl::storedGrantsOf($principal)` is what a principal stores (direct and through its roles, as `HasRoles` reads them) and `roleGrantsOf($principal)` its roles' part — put staged changes on top and hand the closure to `explainer()`.
- `PermissionGraph::problemDetails()` lists the declaration problems as `PermissionProblemDto`s for a UI to word itself.
- Principal diagrams draw a permission withheld by a condition as `unmet-condition`; the JSON schema is version 2.
````

Also: in section 8 wherever the node states are listed (search `missing-requirement` in `README.md`), add `unmet-condition` with "the rules allow it, the account fails a condition"; and wherever `--schema-version=1` appears, write `2`. In "#### Rules in a permission-management UI" add one sentence pointing at `explainer()` and `problemDetails()`.

- [ ] **Step 2: CHANGELOG — 3.1.0**

Insert above `## 3.0.0 - Unreleased`:

```markdown
## 3.1.0 - Unreleased

### Added

- Conditions on accounts: an attribute implementing `Contracts\PermissionCondition` (`check($permission, $account): bool`), on a permission enum or case, withholds the permission from an `Authenticatable` principal that does not meet it — at the gate, in `hasPermissionTo()` of the traits and in `effectivePermissions()`. Roles and other principals that do not sign in are never evaluated. `Contracts\DescribesPermissionCondition` names a condition for UIs.
- `PermissionConditions`, a singleton that finds conditions by interface and keeps the attribute instances per process; `AccessControl::unmetConditions($permission, $principal)`.
- `PermissionResolver::explainer($stored, ?$account)`: one resolution answering many permissions. `PermissionResolutionDto` gains `restricted`, `unmetConditions` and `effective`.
- `AccessControl::storedGrantsOf($principal)` and `roleGrantsOf($principal)`: what a principal stores, for a UI to put staged changes on.
- `PermissionDto::$conditions`; `PermissionGraph::problemDetails()` with `PermissionProblemDto` and `PermissionProblemType`.
- Principal diagrams: the node state `unmet-condition`.

### Changed

- `effectivePermissions()` leaves out restricted permissions for every principal — before, a principal answering `hasPermissionTo()` itself could list a restricted one the gate refused.
- The diagram JSON schema is version 2 (a new state value); `permission:graph --schema-version` defaults to 2.
- `explain()` fills the new `restricted` field.

**Upgrading:** nothing changes until a permission carries a condition. A consumer of the diagram JSON that checks the schema version must accept 2.
```

- [ ] **Step 3: Run every gate**

Run: `vendor/bin/pint --test && vendor/bin/rector --dry-run && vendor/bin/phpstan analyse && composer test:type-coverage && vendor/bin/pest`
Expected: all green; type coverage unchanged from Task 0. Fix anything Rector proposes (`vendor/bin/rector` then re-run Pint).

- [ ] **Step 4: Commit**

```bash
git add README.md CHANGELOG.md
git commit -m "docs: conditions on accounts"
```

- [ ] **Step 5: Push and open the pull request — ask the user first**

Pushing and opening a PR is outward-facing: confirm with the user in chat, then:

```bash
git push -u origin feat/permission-conditions
gh pr create --base 3.x --title "feat: conditions on accounts, explainer() and problemDetails() (3.1.0)" --body-file - <<'EOF'
## Summary
- `Contracts\PermissionCondition` — an attribute on a permission enum or case that withholds the permission from an account (`Authenticatable`) not meeting it; enforced at the gate, in the traits' `hasPermissionTo()` and in `effectivePermissions()`, always last. Roles are never evaluated.
- `PermissionResolver::explainer()`, `AccessControl::storedGrantsOf()` / `roleGrantsOf()`, `PermissionDto::$conditions`, `PermissionGraph::problemDetails()` — what filament-access-control 3.x needs to show why a permission is or is not in effect.
- Principal diagrams: state `unmet-condition`, JSON schema version 2.

Spec: happenv-com/filament-access-control `docs/superpowers/specs/2026-09-29-access-control-3-conditions-and-rules-ui-design.md`.

## Test plan
- [ ] `vendor/bin/pest` — principal semantics, interaction examples and invariants 1–6, 8–10 through `hasPermissionTo()`, the gate, `effectivePermissions()` and `explainer()`
- [ ] `vendor/bin/phpstan analyse`, Pint, Rector, type coverage
EOF
```

After the user merges it, the tag `v3.1.0` is theirs to create (or confirm before creating it).
