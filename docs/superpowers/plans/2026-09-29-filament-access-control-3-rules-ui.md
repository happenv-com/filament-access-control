# filament-access-control 3.x — Rules, Conditions and Graphs UI Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Show on every permission screen *why* a permission is or is not in effect — rules in both directions, restrictions, account conditions (with the package's own `#[RequiresMFA]`), declaration problems and a permission graph — on top of laravel-access-control 3.1.

**Architecture:** The library resolves; the package presents. Each holder gets one `PermissionResolver::explainer()` per request over its stored grants with staged changes on top; a `PermissionCell` (presentation object: `PermissionCellState` + staged flag + reasons) turns each `PermissionResolutionDto` into Filament's icon, colour and tooltip. New columns (Dependencies, In effect), callouts (declaration problems, unmet conditions) and a Mermaid modal are plain Filament components; mermaid.js is a lazily loaded Alpine component built with esbuild.

**Tech Stack:** PHP 8.3+, Laravel 12/13, Filament 4.13+/5.8+ (Livewire 3/4), laravel-access-control 3.1, Pest 4/5 + pest-plugin-livewire, PHPStan (Larastan, level 6), Pint, Rector, esbuild + mermaid (npm).

**Spec:** `docs/superpowers/specs/2026-09-29-access-control-3-conditions-and-rules-ui-design.md` (Parts 2–4). Read it before starting.

**Repository:** `/Users/bgajda/Packages/filament-access-control` (GitHub `happenv-com/filament-access-control`), branch `feat/access-control-3`. Every path below is relative to it.

**Prerequisite:** the library plan `docs/superpowers/plans/2026-09-29-laravel-access-control-3-1-conditions.md` is implemented in `/Users/bgajda/Packages/laravel-access-control` (branch `feat/permission-conditions`, or `3.x` once merged). This plan uses its API: `PermissionCondition`, `DescribesPermissionCondition`, `PermissionResolver::explainer()`, `PermissionResolutionDto::{restricted, unmetConditions, effective}`, `AccessControl::roleGrantsOf()`, `PermissionDto::$conditions`, `PermissionGraph::problemDetails()`, `PermissionProblemDto`, `PermissionProblemType`.

## Global Constraints

- Branches: `3.x` exists locally, created from `2.x` (21f66ff); work on `feat/access-control-3`; the pull request goes into `3.x`. Branch alias `dev-3.x: 3.x-dev`.
- Dependencies: `happenv-com/laravel-access-control: ^3.1` (a `path` repository to `../laravel-access-control` during development, removed in Task 11); `filament/filament: ^4.13.3 || ^5.8.3` unchanged; PHP `^8.3` — no PHP 8.4 syntax (`new X()->m()`; write `(new X)->m()`; Pint enforces `new_expression_parentheses`).
- Layering: the package **never decides** whether a permission is in effect — it presents what the library resolved (`PermissionResolutionDto`). Screens read and write **stored** grants only (`getPermissions()` / `PermissionWriter`); the writer is unchanged.
- Filament components only (badges, icons, tooltips, callouts, modal). No new CSS; inline `style` only where the existing code already does it (indentation) and for the graph's `<pre>`.
- Cell states, icons and colours exactly as the spec's *Cell states* table; a staged cell takes `primary`; precedence missing → conflict → restricted → condition, the tooltip lists all.
- Plugin defaults: `->diagrams()` **true**, `->declarationProblems()` **true**.
- Every new string exists in all 64 locales at every commit (English placeholders written by `bin/sync-locales.php` until Task 9 translates them). `TranslationsTest` and `LocalesTest` stay green.
- Every PHP file starts with `declare(strict_types=1);`; docblocks explain *why*, in the voice of the surrounding code.
- Gates before the PR: `composer ci` (normalize, Rector, Pint, PHPStan, PSR-4, tests) green on Filament 5 and on Filament 4.

## Review Focus

1. **Live mode** (not deferred): after a click, the cells of *related* permissions (one requiring the clicked one, one it conflicts with) are resolved again in the same response — the per-request memo is forgotten after a write (test in Task 4).
2. **Discard** in deferred mode returns every cell to the saved resolution, including cells a staged grant had put in conflict (test in Task 4).
3. An operator who **cannot edit** a holder is not told "a click grants it explicitly" on an implied cell (test in Task 4).
4. The **super-admin** column stays fully in effect whatever the rules say — never a missing requirement or a conflict (test in Task 4).
5. `#[RequiresMFA]` on an account **the MFA providers cannot ask** (an API key; Filament's app authentication throws `LogicException` for a model without its contract) — not met, never a 500 (test in Task 2).

---

## File Structure

| File | Responsibility |
|---|---|
| `composer.json` | `^3.1`, path repository (temporary), branch alias. |
| `bin/sync-locales.php` (new) | Adds every English line a locale lacks (in English) and applies JSON translations; writes files in English key order. |
| `src/Attributes/RequiresMFA.php` (new) | The package's condition: some MFA provider of the panel enabled for the account; fails closed. |
| `src/Support/PermissionCellState.php` (new) | The seven states: from a `PermissionResolutionDto`, with icon and colour. |
| `src/Support/PermissionCell.php` (new) | A state + staged flag + reasons; colour and tooltip for Filament. |
| `src/Support/DependencyBadge.php` (new) | A badge (`HasLabel`, `HasColor`) with the rule's reason. |
| `src/Support/DeclarationProblems.php` (new, scoped) | `problemDetails()` worded in the request's locale; which permissions declare a problem. |
| `src/Support/PermissionTree.php` | `nameOf()`, `describeCondition()`, `dependencies()`, `hasDependencies()`, search over related names. |
| `src/Livewire/Concerns/EditsPermissions.php` | Per-holder explainer and cell memo, rule-aware holder cells, Dependencies column, problems, graph action. |
| `src/Livewire/RecordPermissions.php` | Direct cells for accounts, "In effect" column, unmet-conditions summary, principal graph. |
| `src/Livewire/RolePermissionMatrix.php` | Dependencies column, catalogue graph. |
| `src/FilamentAccessControlPlugin.php` | `diagrams()`, `declarationProblems()`. |
| `src/FilamentAccessControlServiceProvider.php` | `DeclarationProblems` scoped; the Alpine component asset. |
| `resources/views/livewire/*.blade.php`, `resources/views/partials/{declaration-problems,permission-graph}.blade.php` | Callouts and the graph modal. |
| `resources/js/components/permission-graph.js`, `resources/dist/components/permission-graph.js`, `package.json`, `package-lock.json`, `bin/build.js`, `.nvmrc`, `.prettierrc`, `.prettierignore`, `.github/workflows/assets.yml` | The asset pipeline, restored from the skeleton (commit `4660746`). |
| `resources/lang/*/editor.php` | New strings, 64 locales. |
| `tests/Fixtures/...` | `GalleryPermission`, `BrokenPermission`, `UnregisteredPermission`, `RequiresOfficeHours`; `User` with app authentication; MFA on the panel; `Role` as `HoldsGrants`. |
| `tests/Feature/...` | New tests per task; `TranslationsTest` gains a placeholder check. |
| `README.md`, `UPGRADING.md`, `CHANGELOG.md` | Documentation. |

---

### Task 1: laravel-access-control 3.1 and a green baseline

**Files:**
- Modify: `composer.json`

**Interfaces:**
- Produces: the package resolved against the local library checkout (3.1 API available).

- [ ] **Step 1: Make sure you are on the working branch**

```bash
cd /Users/bgajda/Packages/filament-access-control
git switch feat/access-control-3
git status --short   # expect nothing
git -C ../laravel-access-control branch --show-current   # expect feat/permission-conditions (or 3.x after its merge)
```

- [ ] **Step 2: Point composer at the library**

Edit `composer.json`:
- `require`: `"happenv-com/laravel-access-control": "^3.1"`.
- `extra.branch-alias`: replace `"dev-2.x": "2.x-dev"` with `"dev-3.x": "3.x-dev"`.
- add a top-level `repositories` (removed again in Task 11):

```json
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-access-control",
            "options": {
                "symlink": true,
                "versions": {
                    "happenv-com/laravel-access-control": "3.1.0"
                }
            }
        }
    ],
```

Then:

```bash
composer normalize
composer update -W
composer show happenv-com/laravel-access-control | grep -E "^versions|^source|^dist"
```

Expected: `versions : * 3.1.0`, installed from the path (a symlink in `vendor/happenv-com/laravel-access-control`).

- [ ] **Step 3: Run the whole suite and PHPStan**

Run: `vendor/bin/pest --testsuite=Unit,Feature && vendor/bin/phpstan analyse`
Expected: PASS / no errors. laravel-access-control 3 changes nothing until a permission declares a rule or a condition, and the package's fixtures declare none. If something fails, fix it in the package (the library is additive) and describe the fix in the commit.

- [ ] **Step 4: Commit**

```bash
git add composer.json
git commit -m "build: target laravel-access-control 3.1 on the 3.x line"
```

---

### Task 2: `#[RequiresMFA]` and the locale sync tool

**Files:**
- Create: `src/Attributes/RequiresMFA.php`, `bin/sync-locales.php`, `tests/Fixtures/Permissions/GalleryPermission.php`, `tests/Feature/Attributes/RequiresMFATest.php`
- Modify: `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (English placeholders), `resources/lang/uz/*.php` (quoting only), `tests/Fixtures/User.php`, `tests/Fixtures/database/migrations/2026_01_01_000000_create_access_control_tables.php`, `tests/Fixtures/AdminPanelProvider.php`, `tests/Pest.php`

**Interfaces:**
- Consumes: `Happenv\LaravelAccessControl\Contracts\PermissionCondition`, `DescribesPermissionCondition`.
- Produces: `Happenv\FilamentAccessControl\Attributes\RequiresMFA(?string $panel = null)` — `check()`, `describe()` ("Requires MFA"); `GalleryPermission` fixture (`View` requires `ProductPermission::View` with reason "The gallery shows products.", `Manage` implied by `ProductPermission::Update`, `Archive` conflicts with `ProductPermission::Delete`, `Publish` carries `#[RequiresMFA]`); test helper `registerPermissions(string ...$enums): void`; `User` implements `HasAppAuthentication` (`saveAppAuthenticationSecret()`); the admin panel has `AppAuthentication`; `php bin/sync-locales.php [<json-dir>]`.

- [ ] **Step 1: Write the locale sync tool**

`bin/sync-locales.php`:

```php
<?php

declare(strict_types=1);

/*
 * Keeps every locale's translation files in step with the English ones.
 *
 *   php bin/sync-locales.php              add every English line a locale lacks, in English
 *   php bin/sync-locales.php <json-dir>   ...and apply the translations in <json-dir>/<locale>.json
 *
 * A JSON file mirrors the PHP files: {"editor": {"columns": {"in_effect": "..."}}}. Every file is
 * written back in the English key order, so a diff shows only what changed.
 */

$root = dirname(__DIR__);
$translations = $argv[1] ?? null;

$export = static function (array $english, array $lines, int $depth) use (&$export): string {
    $indent = str_repeat('    ', $depth);
    $php = '';

    foreach ($english as $key => $line) {
        $php .= $indent . var_export($key, true) . ' => ';
        $php .= is_array($line)
            ? "[\n" . $export($line, is_array($lines[$key] ?? null) ? $lines[$key] : [], $depth + 1) . $indent . "],\n"
            : var_export(is_string($lines[$key] ?? null) ? $lines[$key] : $line, true) . ",\n";
    }

    return $php;
};

foreach (glob("{$root}/resources/lang/*", GLOB_ONLYDIR) ?: [] as $directory) {
    $locale = basename($directory);

    if ($locale === 'en') {
        continue;
    }

    $given = $translations !== null && is_file("{$translations}/{$locale}.json")
        ? json_decode((string) file_get_contents("{$translations}/{$locale}.json"), true, flags: JSON_THROW_ON_ERROR)
        : [];

    foreach (glob("{$root}/resources/lang/en/*.php") ?: [] as $englishFile) {
        $file = basename($englishFile, '.php');
        $current = is_file("{$directory}/{$file}.php") ? require "{$directory}/{$file}.php" : [];

        file_put_contents(
            "{$directory}/{$file}.php",
            "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n"
                . $export(require $englishFile, array_replace_recursive($current, $given[$file] ?? []), 1)
                . "];\n",
        );
    }
}
```

- [ ] **Step 2: Prove it reproduces the files, and normalise `uz`**

Run: `php bin/sync-locales.php && git status --short resources/lang`
Expected: only the four `resources/lang/uz/*.php` files change — they write apostrophes in double-quoted strings, every other locale in single quotes with `\'`. Check the diff is quoting only (`git diff --word-diff resources/lang/uz | head -40`), then:

```bash
git add bin/sync-locales.php resources/lang/uz
git commit -m "chore: bin/sync-locales.php keeps every locale in step with English"
```

- [ ] **Step 3: Give the test panel MFA and the test user an app-authentication secret**

`tests/Fixtures/database/migrations/2026_01_01_000000_create_access_control_tables.php` — in the `users` table change:

```php
        Schema::table('users', function (Blueprint $table): void {
            $table->json('permissions')->nullable();
            $table->text('app_authentication_secret')->nullable();
        });
```

`tests/Fixtures/User.php` — implement `Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication`; add `'app_authentication_secret'` to `$fillable` and `$hidden`; add `@property string|null $app_authentication_secret` to the class docblock; add:

```php
    public function getAppAuthenticationSecret(): ?string
    {
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(#[SensitiveParameter] ?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }
```

(`use SensitiveParameter;` at the top.)

`tests/Fixtures/AdminPanelProvider.php` — add `use Filament\Auth\MultiFactor\App\AppAuthentication;` and, after `->login()`:

```php
            ->multiFactorAuthentication([
                AppAuthentication::make(),
            ])
```

`tests/Pest.php` — add (with `use Happenv\LaravelAccessControl\PermissionRegistry;`):

```php
/**
 * Register permission enums for one test — the fixtures with rules and conditions stay out of the
 * catalogue every other test draws.
 *
 * @param  class-string  ...$enums
 */
function registerPermissions(string ...$enums): void
{
    resolve(PermissionRegistry::class)->register($enums);
}
```

- [ ] **Step 4: Write the fixture enum**

`tests/Fixtures/Permissions/GalleryPermission.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\LaravelAccessControl\Attributes\ConflictsWith;
use Happenv\LaravelAccessControl\Attributes\ImpliedBy;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Attributes\Requires;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Every rule, and a condition, against the products. Registered only by the tests that need it
 * ({@see registerPermissions()}), so every other test's catalogue stays as it was.
 */
#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Gallery')]
enum GalleryPermission: string implements PermissionDefinition
{
    #[PermissionName('View gallery')]
    #[Requires(ProductPermission::View, reason: 'The gallery shows products.')]
    case View = 'catalogue.gallery.view';

    #[PermissionName('Manage gallery')]
    #[ImpliedBy(ProductPermission::Update)]
    case Manage = 'catalogue.gallery.manage';

    #[PermissionName('Archive gallery')]
    #[ConflictsWith(ProductPermission::Delete)]
    case Archive = 'catalogue.gallery.archive';

    #[PermissionName('Publish gallery')]
    #[RequiresMFA]
    case Publish = 'catalogue.gallery.publish';
}
```

- [ ] **Step 5: Write the failing test**

`tests/Feature/Attributes/RequiresMFATest.php`:

```php
<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\LaravelAccessControl\GateConfigurator;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;

covers(RequiresMFA::class);

const MFA_SECRET = 'JBSWY3DPEHPK3PXP';

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');
});

it('is met by an account with multi-factor authentication enabled on the panel', function (): void {
    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('is not met by an account without it', function (): void {
    expect((new RequiresMFA)->check(GalleryPermission::Publish, createUser()))->toBeFalse();
});

it('is not met by an account the providers cannot ask — an API key, say', function (): void {
    // Filament's app authentication throws for a model without its contract.
    expect((new RequiresMFA)->check(GalleryPermission::Publish, new GenericUser(['id' => 1])))->toBeFalse();
});

it('is not met on a panel without multi-factor authentication', function (): void {
    Filament::registerPanel(Panel::make()->id('plain')->path('plain'));

    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA(panel: 'plain'))->check(GalleryPermission::Publish, $user))->toBeFalse();

    Filament::setCurrentPanel('plain');

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeFalse()
        ->and((new RequiresMFA(panel: 'admin'))->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('is not met on a panel that does not exist', function (): void {
    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA(panel: 'missing'))->check(GalleryPermission::Publish, $user))->toBeFalse();
});

it('asks the default panel outside of any', function (): void {
    Filament::setCurrentPanel(null);

    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('names itself for the screens', function (): void {
    expect((new RequiresMFA)->describe())->toBe('Requires MFA');
});

it('withholds its permission from an account without MFA at the gate and in hasPermissionTo()', function (): void {
    registerPermissions(GalleryPermission::class);
    resolve(GateConfigurator::class)->configure();

    $user = createUser(permissions: [GalleryPermission::Publish->value]);

    expect($user->hasPermissionTo(GalleryPermission::Publish))->toBeFalse()
        ->and(Gate::forUser($user)->allows(GalleryPermission::Publish))->toBeFalse();

    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect($user->hasPermissionTo(GalleryPermission::Publish))->toBeTrue()
        ->and(Gate::forUser($user)->allows(GalleryPermission::Publish))->toBeTrue();
});
```

- [ ] **Step 6: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/Attributes/RequiresMFATest.php`
Expected: FAIL — `Class "Happenv\FilamentAccessControl\Attributes\RequiresMFA" not found`.

- [ ] **Step 7: Write the attribute and its label**

`src/Attributes/RequiresMFA.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Attributes;

use Attribute;
use Filament\Auth\MultiFactor\Contracts\MultiFactorAuthenticationProvider;
use Filament\Facades\Filament;
use Filament\Panel;
use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;
use Throwable;

/**
 * Withholds a permission from an account that has no multi-factor authentication enabled — placed on
 * a permission enum (every case) or on one case.
 *
 * Met when at least one multi-factor provider of the panel reports it enabled for the account: the
 * panel named here, otherwise the current one, otherwise the default one. It fails CLOSED — no panel,
 * a panel without multi-factor authentication, or an account the providers cannot even ask (an API
 * key, a machine user) does not meet it.
 *
 * Free of side effects, as laravel-access-control requires of a condition: a provider's
 * `isEnabled()` reads the account's own attributes.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT)]
final readonly class RequiresMFA implements DescribesPermissionCondition, PermissionCondition
{
    public function __construct(
        /** The id of the panel whose providers count — the current panel, or the default one, when null. */
        public ?string $panel = null,
    ) {}

    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        foreach ($this->providers() as $provider) {
            try {
                if ($provider->isEnabled($principal)) {
                    return true;
                }
            } catch (Throwable) {
                // A provider that cannot ask this account — Filament's app authentication throws for
                // a model without its contract — has enabled nothing for it.
            }
        }

        return false;
    }

    public function describe(): string
    {
        return __('filament-access-control::editor.conditions.requires_mfa');
    }

    /**
     * @return array<MultiFactorAuthenticationProvider>
     */
    private function providers(): array
    {
        try {
            $panel = $this->panel === null ? Filament::getCurrentOrDefaultPanel() : Filament::getPanel($this->panel);
        } catch (Throwable) {
            // No panel to ask: nothing can vouch for the account.
            return [];
        }

        return $panel instanceof Panel ? $panel->getMultiFactorAuthenticationProviders() : [];
    }
}
```

`resources/lang/en/editor.php` — append at the end of the returned array:

```php
    'conditions' => [
        'requires_mfa' => 'Requires MFA',
    ],
```

Run: `php bin/sync-locales.php` (every locale gets the English line for now).

- [ ] **Step 8: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Attributes/RequiresMFATest.php && vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS; `TranslationsTest` and `LocalesTest` still green; `PluginTest` "serves the panel login page" still green with MFA on the panel.

- [ ] **Step 9: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/rector process && vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/Attributes resources/lang tests
git commit -m "feat: #[RequiresMFA] — a permission withheld from an account without multi-factor authentication"
```

---

### Task 3: `PermissionCellState` and `PermissionCell`

**Files:**
- Create: `src/Support/PermissionCellState.php`, `src/Support/PermissionCell.php`, `tests/Fixtures/Permissions/UnregisteredPermission.php`, `tests/Fixtures/Conditions/RequiresOfficeHours.php`, `tests/Feature/Support/PermissionCellTest.php`
- Modify: `src/Support/PermissionTree.php`, `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (sync), `tests/Pest.php`

**Interfaces:**
- Consumes: `PermissionResolutionDto` (library), `RequiresMFA` (Task 2).
- Produces:
  - `enum PermissionCellState: string` — `Effective = 'effective'`, `Implied = 'implied'`, `MissingRequirement = 'missing-requirement'`, `Conflict = 'conflict'`, `Restricted = 'restricted'`, `UnmetCondition = 'unmet-condition'`, `NotGranted = 'not-granted'`; `static of(PermissionResolutionDto): self`, `icon(): Heroicon`, `color(): string`.
  - `final readonly class PermissionCell(PermissionCellState $state, bool $staged = false, list<string> $reasons = [])` — `static of(PermissionResolutionDto $resolution, PermissionTree $tree, bool $staged = false): self`, `withReason(string): self`, `icon(): Heroicon`, `color(): string` (`primary` when staged), `tooltip(): ?string` (lines joined with ` · `, "Unsaved" first when staged).
  - `PermissionTree::nameOf(PermissionDefinition): string` (full name, or the value for an unregistered permission); `PermissionTree::describeCondition(PermissionCondition): string` (`describe()`, or the class name made readable).
  - Test helper `resolutionOf(bool $stored = true, bool $granted = true, bool $allowed = true, array $grantedBy = [], array $missing = [], array $conflicting = [], bool $restricted = false, array $unmetConditions = []): PermissionResolutionDto`.

- [ ] **Step 1: Write the fixtures and the helper**

`tests/Fixtures/Permissions/UnregisteredPermission.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Registered by nobody — a rule may still point at it.
 */
enum UnregisteredPermission: string implements PermissionDefinition
{
    case Orphan = 'nowhere.orphan';
}
```

`tests/Fixtures/Conditions/RequiresOfficeHours.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Conditions;

use Attribute;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * A condition that does not describe itself — the screens name it after its class.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
final readonly class RequiresOfficeHours implements PermissionCondition
{
    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        return true;
    }
}
```

Append to `tests/Pest.php` (with `use` lines for `Happenv\LaravelAccessControl\Contracts\PermissionCondition`, `Happenv\LaravelAccessControl\Contracts\PermissionDefinition`, `Happenv\LaravelAccessControl\Dto\PermissionResolutionDto`):

```php
/**
 * A resolution built by hand — "stored and in effect" unless told otherwise.
 *
 * @param  list<PermissionDefinition>  $grantedBy
 * @param  list<PermissionDefinition>  $missing
 * @param  list<PermissionDefinition>  $conflicting
 * @param  list<PermissionCondition>  $unmetConditions
 */
function resolutionOf(
    bool $stored = true,
    bool $granted = true,
    bool $allowed = true,
    array $grantedBy = [],
    array $missing = [],
    array $conflicting = [],
    bool $restricted = false,
    array $unmetConditions = [],
): PermissionResolutionDto {
    return new PermissionResolutionDto(
        allowed: $allowed,
        stored: $stored,
        granted: $granted,
        grantedBy: $grantedBy,
        missing: $missing,
        conflicting: $conflicting,
        restricted: $restricted,
        unmetConditions: $unmetConditions,
    );
}
```

- [ ] **Step 2: Write the failing test**

`tests/Feature/Support/PermissionCellTest.php`:

```php
<?php

declare(strict_types=1);

use Filament\Support\Icons\Heroicon;
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\FilamentAccessControl\Support\PermissionCell;
use Happenv\FilamentAccessControl\Support\PermissionCellState;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Tests\Fixtures\Conditions\RequiresOfficeHours;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UnregisteredPermission;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;

covers(PermissionCell::class, PermissionCellState::class);

it('turns a resolution into one of seven states', function (PermissionResolutionDto $resolution, PermissionCellState $state, Heroicon $icon, string $color): void {
    expect(PermissionCellState::of($resolution))->toBe($state)
        ->and($state->icon())->toBe($icon)
        ->and($state->color())->toBe($color);
})->with([
    'stored and in effect' => [resolutionOf(), PermissionCellState::Effective, Heroicon::CheckCircle, 'success'],
    'in effect, implied' => [resolutionOf(stored: false, grantedBy: [ProductPermission::Update]), PermissionCellState::Implied, Heroicon::CheckCircle, 'info'],
    'a requirement missing' => [resolutionOf(allowed: false, missing: [ProductPermission::View]), PermissionCellState::MissingRequirement, Heroicon::ExclamationTriangle, 'warning'],
    'a conflict lost' => [resolutionOf(allowed: false, conflicting: [ProductPermission::Delete]), PermissionCellState::Conflict, Heroicon::NoSymbol, 'danger'],
    'restricted at runtime' => [resolutionOf(restricted: true), PermissionCellState::Restricted, Heroicon::LockClosed, 'gray'],
    'a condition unmet' => [resolutionOf(unmetConditions: [new RequiresMFA]), PermissionCellState::UnmetCondition, Heroicon::ShieldExclamation, 'warning'],
    'not granted' => [resolutionOf(stored: false, granted: false, allowed: false), PermissionCellState::NotGranted, Heroicon::XCircle, 'danger'],
]);

it('shows the first reason and lists every one', function (): void {
    $cell = PermissionCell::of(resolutionOf(
        allowed: false,
        missing: [ProductPermission::View],
        conflicting: [ProductPermission::Delete],
        restricted: true,
        unmetConditions: [new RequiresMFA],
    ), resolve(PermissionTree::class));

    expect($cell->state)->toBe(PermissionCellState::MissingRequirement)
        ->and($cell->reasons)->toBe([
            'Missing requirement: View products',
            'Blocked by: Delete products',
            'Restricted by the application right now',
            'Requires MFA — this account does not meet it',
        ]);
});

it('names what implies a permission', function (): void {
    $cell = PermissionCell::of(
        resolutionOf(stored: false, grantedBy: [ProductPermission::Update, ProductPermission::Delete]),
        resolve(PermissionTree::class),
    );

    expect($cell->tooltip())->toBe('Implied by: Update products, Delete products');
});

it('has nothing to say about a plain grant or a plain absence', function (): void {
    $tree = resolve(PermissionTree::class);

    expect(PermissionCell::of(resolutionOf(), $tree)->tooltip())->toBeNull()
        ->and(PermissionCell::of(resolutionOf(stored: false, granted: false, allowed: false), $tree)->tooltip())->toBeNull();
});

it('does not explain what would withhold a permission that is not granted', function (): void {
    $cell = PermissionCell::of(
        resolutionOf(stored: false, granted: false, allowed: false, restricted: true, unmetConditions: [new RequiresMFA]),
        resolve(PermissionTree::class),
    );

    expect($cell->reasons)->toBe([]);
});

it('marks a staged cell with the primary colour and says it is unsaved', function (): void {
    $cell = PermissionCell::of(resolutionOf(allowed: false, missing: [ProductPermission::View]), resolve(PermissionTree::class), staged: true);

    expect($cell->color())->toBe('primary')
        ->and($cell->icon())->toBe(Heroicon::ExclamationTriangle)
        ->and($cell->tooltip())->toBe('Unsaved · Missing requirement: View products');
});

it('adds a reason without touching the rest', function (): void {
    $cell = (new PermissionCell(PermissionCellState::Implied, reasons: ['Implied by: Update products']))->withReason('A click grants it explicitly');

    expect($cell->reasons)->toBe(['Implied by: Update products', 'A click grants it explicitly'])
        ->and($cell->state)->toBe(PermissionCellState::Implied);
});

it('names a permission nobody registered by its value', function (): void {
    $tree = resolve(PermissionTree::class);

    expect($tree->nameOf(UnregisteredPermission::Orphan))->toBe('nowhere.orphan')
        ->and($tree->nameOf(ProductPermission::View))->toBe('View products');
});

it('names a condition by its description, or after its class', function (): void {
    $tree = resolve(PermissionTree::class);

    expect($tree->describeCondition(new RequiresMFA))->toBe('Requires MFA')
        ->and($tree->describeCondition(new RequiresOfficeHours))->toBe('Requires Office Hours');
});
```

- [ ] **Step 3: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/Support/PermissionCellTest.php`
Expected: FAIL — `PermissionCellState` / `PermissionCell` not found.

- [ ] **Step 4: Write the state**

`src/Support/PermissionCellState.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Filament\Support\Icons\Heroicon;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;

/**
 * What a permission's cell shows — read off the library's resolution, never decided here: the
 * package presents what laravel-access-control resolved.
 */
enum PermissionCellState: string
{
    case Effective = 'effective';
    case Implied = 'implied';
    case MissingRequirement = 'missing-requirement';
    case Conflict = 'conflict';
    case Restricted = 'restricted';
    case UnmetCondition = 'unmet-condition';
    case NotGranted = 'not-granted';

    /**
     * The first reason that applies, in the order an operator can act on them — grant what is
     * missing, resolve a conflict — before what the application decides at runtime.
     */
    public static function of(PermissionResolutionDto $resolution): self
    {
        return match (true) {
            ! $resolution->granted => self::NotGranted,
            $resolution->effective => $resolution->stored ? self::Effective : self::Implied,
            $resolution->missing !== [] => self::MissingRequirement,
            $resolution->conflicting !== [] => self::Conflict,
            $resolution->restricted => self::Restricted,
            $resolution->unmetConditions !== [] => self::UnmetCondition,
            // Granted, not in effect and no reason given — nothing the library resolves ends here.
            default => self::NotGranted,
        };
    }

    /**
     * The shape says WHY; the tooltip names what is involved.
     */
    public function icon(): Heroicon
    {
        return match ($this) {
            self::Effective, self::Implied => Heroicon::CheckCircle,
            self::MissingRequirement => Heroicon::ExclamationTriangle,
            self::Conflict => Heroicon::NoSymbol,
            self::Restricted => Heroicon::LockClosed,
            self::UnmetCondition => Heroicon::ShieldExclamation,
            self::NotGranted => Heroicon::XCircle,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Effective => 'success',
            self::Implied => 'info',
            self::MissingRequirement, self::UnmetCondition => 'warning',
            self::Conflict, self::NotGranted => 'danger',
            self::Restricted => 'gray',
        };
    }
}
```

- [ ] **Step 5: Write the cell**

`src/Support/PermissionCell.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Filament\Support\Icons\Heroicon;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;

/**
 * One cell of a permission screen: its {@see PermissionCellState}, whether it shows a change not
 * saved yet, and every reason behind it — the icon shows the first, the tooltip lists them all.
 */
final readonly class PermissionCell
{
    /**
     * @param  list<string>  $reasons
     */
    public function __construct(
        public PermissionCellState $state,
        public bool $staged = false,
        public array $reasons = [],
    ) {}

    public static function of(PermissionResolutionDto $resolution, PermissionTree $tree, bool $staged = false): self
    {
        $reasons = [];

        if (! $resolution->stored && $resolution->grantedBy !== []) {
            $reasons[] = __('filament-access-control::editor.cells.implied_by', ['permissions' => self::names($resolution->grantedBy, $tree)]);
        }

        // What withholds a permission matters only for one that is granted.
        if ($resolution->granted) {
            if ($resolution->missing !== []) {
                $reasons[] = __('filament-access-control::editor.cells.missing', ['permissions' => self::names($resolution->missing, $tree)]);
            }

            if ($resolution->conflicting !== []) {
                $reasons[] = __('filament-access-control::editor.cells.blocked_by', ['permissions' => self::names($resolution->conflicting, $tree)]);
            }

            if ($resolution->restricted) {
                $reasons[] = __('filament-access-control::editor.cells.restricted');
            }

            foreach ($resolution->unmetConditions as $condition) {
                $reasons[] = __('filament-access-control::editor.cells.unmet_condition', ['condition' => $tree->describeCondition($condition)]);
            }
        }

        return new self(PermissionCellState::of($resolution), $staged, $reasons);
    }

    public function withReason(string $reason): self
    {
        return new self($this->state, $this->staged, [...$this->reasons, $reason]);
    }

    public function icon(): Heroicon
    {
        return $this->state->icon();
    }

    /**
     * A staged cell takes the primary colour until it is saved — `warning` already means a missing
     * requirement.
     */
    public function color(): string
    {
        return $this->staged ? 'primary' : $this->state->color();
    }

    public function tooltip(): ?string
    {
        $lines = $this->staged
            ? [__('filament-access-control::editor.staged_marker'), ...$this->reasons]
            : $this->reasons;

        return $lines === [] ? null : implode(' · ', $lines);
    }

    /**
     * @param  list<PermissionDefinition>  $permissions
     */
    private static function names(array $permissions, PermissionTree $tree): string
    {
        return implode(', ', array_map($tree->nameOf(...), $permissions));
    }
}
```

- [ ] **Step 6: Name permissions and conditions in `PermissionTree`**

In `src/Support/PermissionTree.php` add `use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;`, `use Happenv\LaravelAccessControl\Contracts\PermissionCondition;`, `use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;` and, after `find()`:

```php
    /**
     * A permission's full name — or, for one nobody registered (a rule may point at it), its value,
     * which is what is granted anyway.
     */
    public function nameOf(PermissionDefinition $permission): string
    {
        return $this->find((string) $permission->value)?->name ?? (string) $permission->value;
    }

    /**
     * A condition's label: its own description, or its class name made readable.
     */
    public function describeCondition(PermissionCondition $condition): string
    {
        return $condition instanceof DescribesPermissionCondition
            ? $condition->describe()
            : Str::headline(class_basename($condition));
    }
```

- [ ] **Step 7: Add the English lines**

`resources/lang/en/editor.php` — append:

```php
    'cells' => [
        'blocked_by' => 'Blocked by: :permissions',
        'grant_explicitly' => 'A click grants it explicitly',
        'implied_by' => 'Implied by: :permissions',
        'missing' => 'Missing requirement: :permissions',
        'restricted' => 'Restricted by the application right now',
        'unmet_condition' => ':condition — this account does not meet it',
    ],
```

Run: `php bin/sync-locales.php`

- [ ] **Step 8: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Support/PermissionCellTest.php && vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS.

- [ ] **Step 9: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/Support resources/lang tests
git commit -m "feat: PermissionCell — the library's resolution as an icon, a colour and its reasons"
```

---

### Task 4: Rule-aware holder cells, staged changes included

**Files:**
- Modify: `src/Livewire/Concerns/EditsPermissions.php`, `src/Livewire/RecordPermissions.php`
- Modify (existing assertions): `tests/Feature/Livewire/RolePermissionMatrixTest.php:117,118,224`, `tests/Feature/Livewire/RecordPermissionsTest.php:41`
- Test: `tests/Feature/Livewire/RuleAwareCellsTest.php` (new)

**Interfaces:**
- Consumes: `PermissionResolver::explainer()` (library), `PermissionCell`, `PermissionCellState` (Task 3), `GalleryPermission`, `registerPermissions()` (Task 2).
- Produces (in `EditsPermissions`): `public function resolvesHolderCells(): bool` (true; `RecordPermissions` answers `isRole()`); `public function holderResolution(string $holderKey, PermissionDefinition $permission): PermissionResolutionDto`; `public function holderCell(string $holderKey, string $slug): PermissionCell`; `protected function forgetResolutions(): void`; protected memos `$permissionExplainers` (`array<array-key, Closure(PermissionDefinition): PermissionResolutionDto>`) and `$permissionCells` (`array<array-key, array<string, PermissionCell>>`). A permission row's holder-cell state is a `PermissionCellState` value for resolving holders, `granted` / `revoked` for an account's direct column; subjects keep `all` / `some` / `none`.

- [ ] **Step 1: Write the failing test**

`tests/Feature/Livewire/RuleAwareCellsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\PermissionRestrictions;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->admin = createRole(Role::ADMINISTRATOR, name: 'Administrator');
    $this->editor = createRole('editor', name: 'Editor');
    $this->key = holderKey($this->editor);
    $this->column = 'holder_' . $this->key;
});

describe('a role\'s cells', function (): void {
    it('show what the rules make of the role\'s grants', function (): void {
        $this->editor->update(['permissions' => [
            ProductPermission::Update->value,
            ProductPermission::Delete->value,
            GalleryPermission::View->value,
            GalleryPermission::Archive->value,
        ]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . ProductPermission::Update->value)
            ->assertTableColumnStateSet($this->column, 'implied', 'permission:' . GalleryPermission::Manage->value)
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'not-granted', 'permission:' . ProductPermission::Create->value);
    });

    it('never show a condition — a role does not sign in', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Publish->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Publish->value);
    });

    it('show a permission the application restricts', function (): void {
        resolve(PermissionRestrictions::class)->restrictUsing(fn (PermissionDefinition $permission): bool => $permission === ProductPermission::Delete);

        $this->editor->update(['permissions' => [ProductPermission::Delete->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'restricted', 'permission:' . ProductPermission::Delete->value);
    });

    it('name what is involved in the tooltip', function (): void {
        $this->editor->update(['permissions' => [
            GalleryPermission::View->value,
            GalleryPermission::Archive->value,
            ProductPermission::Delete->value,
            ProductPermission::Update->value,
        ]]);

        $matrix = livewire(RolePermissionMatrix::class)->instance();

        expect($matrix->holderCell($this->key, GalleryPermission::View->value)->tooltip())->toBe('Missing requirement: View products')
            ->and($matrix->holderCell($this->key, GalleryPermission::Archive->value)->tooltip())->toBe('Blocked by: Delete products')
            ->and($matrix->holderCell($this->key, GalleryPermission::Manage->value)->tooltip())->toBe('Implied by: Update products · A click grants it explicitly');
    });

    it('do not promise a click to an operator who cannot make it', function (): void {
        signInOperator([RolePermission::View->value]);

        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        expect(livewire(RolePermissionMatrix::class)->instance()->holderCell($this->key, GalleryPermission::Manage->value)->tooltip())
            ->toBe('Implied by: Update products');
    });

    it('keep the super-admin role fully in effect, whatever the rules say', function (): void {
        $column = 'holder_' . holderKey($this->admin);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($column, 'effective', 'permission:' . GalleryPermission::View->value)
            ->assertTableColumnStateSet($column, 'effective', 'permission:' . GalleryPermission::Archive->value);
    });

    it('are drawn the same in the editor of one role', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RecordPermissions::class, ['record' => $this->editor])
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value);
    });

    it('revoke a stored permission that is not in effect when clicked', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, GalleryPermission::View->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([]);
    });

    it('grant an implied permission explicitly when clicked', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        livewire(RolePermissionMatrix::class)->call('toggle', $this->key, GalleryPermission::Manage->value);

        expect($this->editor->fresh()->getPermissions()->all())
            ->toEqualCanonicalizing([ProductPermission::Update->value, GalleryPermission::Manage->value]);
    });
});

describe('staged changes (deferred) — the spec\'s table', function (): void {
    it('show a staged revocation as not granted', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'not-granted', 'permission:' . GalleryPermission::Archive->value);

        expect($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->color())->toBe('primary');
    });

    it('show a staged grant as stored and in effect', function (): void {
        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, GalleryPermission::Archive->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Archive->value);

        expect($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->staged)->toBeTrue();
    });

    it('show a staged grant of an implied permission as stored', function (): void {
        $this->editor->update(['permissions' => [ProductPermission::Update->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->assertTableColumnStateSet($this->column, 'implied', 'permission:' . GalleryPermission::Manage->value)
            ->call('toggle', $this->key, GalleryPermission::Manage->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Manage->value);
    });

    it('show at once, before saving, the conflict a staged grant causes', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        $component = livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Delete->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value);

        expect($this->editor->fresh()->getPermissions()->all())->toBe([GalleryPermission::Archive->value])
            ->and($component->instance()->holderCell($this->key, GalleryPermission::Archive->value)->staged)->toBeFalse();
    });

    it('show the requirement a staged grant satisfies', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::View->value);
    });

    it('go back to what is saved on discard', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::Archive->value]]);

        livewire(RolePermissionMatrix::class, ['deferred' => true])
            ->call('toggle', $this->key, ProductPermission::Delete->value)
            ->assertTableColumnStateSet($this->column, 'conflict', 'permission:' . GalleryPermission::Archive->value)
            ->call('discard')
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::Archive->value);
    });
});

describe('live changes', function (): void {
    it('resolve the related cells again in the same response', function (): void {
        $this->editor->update(['permissions' => [GalleryPermission::View->value]]);

        livewire(RolePermissionMatrix::class)
            ->assertTableColumnStateSet($this->column, 'missing-requirement', 'permission:' . GalleryPermission::View->value)
            ->call('toggle', $this->key, ProductPermission::View->value)
            ->assertTableColumnStateSet($this->column, 'effective', 'permission:' . GalleryPermission::View->value);
    });
});
```

- [ ] **Step 2: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/Livewire/RuleAwareCellsTest.php`
Expected: FAIL — states are still `granted` / `revoked`; `holderCell()` undefined.

- [ ] **Step 3: Resolve holder cells in `EditsPermissions`**

In `src/Livewire/Concerns/EditsPermissions.php` add imports `Happenv\FilamentAccessControl\Support\PermissionCell`, `Happenv\FilamentAccessControl\Support\PermissionCellState`, `Happenv\LaravelAccessControl\Contracts\PermissionDefinition`, `Happenv\LaravelAccessControl\Dto\PermissionResolutionDto`, `Happenv\LaravelAccessControl\PermissionResolver`.

Add properties after `$editableHolders`:

```php
    /**
     * One resolution per holder for the life of one request: the library resolves the rules over a
     * stored state once and answers every permission from it. Forgotten whenever what the screen
     * treats as stored changes — a click staged, a write, a discard.
     *
     * @var array<array-key, Closure(PermissionDefinition): PermissionResolutionDto>
     */
    protected array $permissionExplainers = [];

    /**
     * The cells built from those resolutions — a cell's state, colour and tooltip are asked for
     * separately.
     *
     * @var array<array-key, array<string, PermissionCell>>
     */
    protected array $permissionCells = [];
```

Add, in the "Reading" section after `isRestricted()`:

```php
    /**
     * Whether a holder's cells show what the rules make of its grants, or only whether it holds each
     * one. A role's do; an account's direct grants do not — its "In effect" column does that.
     */
    public function resolvesHolderCells(): bool
    {
        return true;
    }

    /**
     * What the library resolves a holder's grants to — staged changes included, and without an
     * account, so without conditions: a role holds grants, it does not sign in.
     */
    public function holderResolution(string $holderKey, PermissionDefinition $permission): PermissionResolutionDto
    {
        $explain = $this->permissionExplainers[$holderKey] ??= resolve(PermissionResolver::class)->explainer(
            fn (PermissionDefinition $candidate): bool => $this->isGranted($holderKey, (string) $candidate->value),
        );

        return $explain($permission);
    }

    /**
     * A holder's cell for one permission: its resolution as an icon, a colour and the reasons.
     */
    public function holderCell(string $holderKey, string $slug): PermissionCell
    {
        return $this->permissionCells[$holderKey][$slug] ??= $this->makeHolderCell($holderKey, $slug);
    }

    protected function makeHolderCell(string $holderKey, string $slug): PermissionCell
    {
        $permission = $this->tree()->find($slug);

        // A super-admin role holds everything, whatever the rules would say about a list it does not have.
        if ($this->isLockedKey($holderKey) || ! $permission instanceof PermissionDto) {
            return new PermissionCell($this->isGranted($holderKey, $slug) ? PermissionCellState::Effective : PermissionCellState::NotGranted);
        }

        $cell = PermissionCell::of(
            $this->holderResolution($holderKey, $permission->enum),
            $this->tree(),
            staged: $this->isStaged($holderKey, $slug),
        );

        return $cell->state === PermissionCellState::Implied && $this->canEditHolder($holderKey)
            ? $cell->withReason(__('filament-access-control::editor.cells.grant_explicitly'))
            : $cell;
    }

    protected function forgetResolutions(): void
    {
        $this->permissionExplainers = [];
        $this->permissionCells = [];
    }
```

Replace the permission branch of `cellState()`:

```php
        if ($record['type'] !== 'subject') {
            $slug = (string) $record['slug'];

            if (! $this->resolvesHolderCells()) {
                return $this->isGranted($holderKey, $slug) ? 'granted' : 'revoked';
            }

            return $this->holderCell($holderKey, $slug)->state->value;
        }
```

and update `cellState()`'s docblock: "What one holder's cell shows for a row: a {@see PermissionCellState} value for a permission — or `granted` / `revoked` where the screen shows only what is held — and `all`, `some` or `none` for a subject."

In `holderColumn()` replace the `icon`, `color` and `tooltip` calls:

```php
            ->icon(fn (string $state): Heroicon => PermissionCellState::tryFrom($state)?->icon() ?? match ($state) {
                'granted', 'all' => Heroicon::CheckCircle,
                'some' => Heroicon::MinusCircle,
                default => Heroicon::XCircle,
            })
            // A staged cell keeps the shape of what it will become and takes the primary colour until
            // it is saved — Filament's own palette, no styles of our own. `warning` means a missing
            // requirement.
            ->color(fn (string $state, array $record): string => match (true) {
                $this->isStagedRecord($holderKey, $record) => 'primary',
                PermissionCellState::tryFrom($state) instanceof PermissionCellState => PermissionCellState::from($state)->color(),
                in_array($state, ['granted', 'all'], true) => 'success',
                $state === 'some' => 'warning',
                $state === 'revoked' => 'danger',
                default => 'gray',
            })
            ->tooltip(fn (array $record): ?string => match (true) {
                $record['type'] === 'subject' => $this->isStagedRecord($holderKey, $record)
                    ? __('filament-access-control::editor.staged_marker')
                    : $this->subjectTooltip($holderKey, $record),
                $this->resolvesHolderCells() => $this->holderCell($holderKey, (string) $record['slug'])->tooltip(),
                $this->isStagedRecord($holderKey, $record) => __('filament-access-control::editor.staged_marker'),
                default => null,
            })
```

Forget the resolutions whenever the stored state the screen shows changes — first line of `stage()`, of `discard()` and of `refreshHolders()`:

```php
        $this->forgetResolutions();
```

In `permissionColumn()` the restricted marker becomes the grey lock the cells use (a red no-entry sign now means a lost conflict):

```php
            ->icon(fn (array $record): ?Heroicon => $record['restricted'] ? Heroicon::LockClosed : null)
            ->iconColor('gray')
```

- [ ] **Step 4: An account's direct column stays a plain "held or not"**

In `src/Livewire/RecordPermissions.php` add:

```php
    /**
     * A role's cells show what the rules make of its grants; an account's "Granted" column is what it
     * holds directly, and its "In effect" column the rest.
     */
    public function resolvesHolderCells(): bool
    {
        return $this->isRole();
    }
```

- [ ] **Step 5: Update the existing assertions**

A role's permission cells are now resolved: in `tests/Feature/Livewire/RolePermissionMatrixTest.php` change `'granted'` → `'effective'` on lines 117 and 224 and `'revoked'` → `'not-granted'` on line 118; in `tests/Feature/Livewire/RecordPermissionsTest.php` change `'granted'` → `'effective'` on line 41 (a role record). Assertions on a **user's** direct column keep `granted` / `revoked`.

- [ ] **Step 6: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Livewire && vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS. If another existing assertion expects `granted`/`revoked` on a role column or `warning` on a staged cell, update it the same way and say so in the commit message.

- [ ] **Step 7: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src/Livewire tests/Feature/Livewire
git commit -m "feat: a role's cells show what the rules make of its grants, staged changes included"
```

---

### Task 5: The "Dependencies" column

**Files:**
- Create: `src/Support/DependencyBadge.php`, `tests/Feature/Livewire/DependenciesTest.php`
- Modify: `src/Support/PermissionTree.php`, `src/Livewire/Concerns/EditsPermissions.php`, `src/Livewire/RolePermissionMatrix.php:table`, `src/Livewire/RecordPermissions.php:table`, `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (sync), `tests/Feature/Livewire/RolePermissionMatrixTest.php`

**Interfaces:**
- Consumes: `PermissionDto::$rules` / `$conditions` (library), `PermissionTree::nameOf()` / `describeCondition()` (Task 3).
- Produces: `final readonly class DependencyBadge(string $label, string $color, ?string $reason = null) implements HasLabel, HasColor`; `PermissionTree::dependencies(PermissionDto): list<DependencyBadge>`, `PermissionTree::hasDependencies(): bool`; search matches the names of related permissions; `EditsPermissions::dependenciesColumn(): TextColumn` (name `dependencies`), `dependencyBadges(array $record): list<DependencyBadge>`, `dependencyTooltip(array $record): ?string`.

- [ ] **Step 1: Write the failing test**

`tests/Feature/Livewire/DependenciesTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\DependencyBadge;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

it('shows every rule on both of its ends, and every condition', function (string $slug, array $badges): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect(array_map(
        fn (DependencyBadge $badge): array => [$badge->label, $badge->color],
        $matrix->dependencyBadges(['type' => 'permission', 'slug' => $slug]),
    ))->toBe($badges);
})->with([
    'requires' => [GalleryPermission::View->value, [['Requires: View products', 'gray']]],
    'required by' => [ProductPermission::View->value, [['Required by: View gallery', 'gray']]],
    'implied by' => [GalleryPermission::Manage->value, [['Implied by: Update products', 'info']]],
    'implies' => [ProductPermission::Update->value, [['Implies: Manage gallery', 'info']]],
    'blocked by' => [GalleryPermission::Archive->value, [['Blocked by: Delete products', 'danger']]],
    'blocks' => [ProductPermission::Delete->value, [['Blocks: Archive gallery', 'danger']]],
    'a condition' => [GalleryPermission::Publish->value, [['Requires MFA', 'warning']]],
    'nothing' => [ProductPermission::Create->value, []],
]);

it('gives the declared reason in the tooltip', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect($matrix->dependencyTooltip(['type' => 'permission', 'slug' => GalleryPermission::View->value]))
        ->toBe('Requires: View products — The gallery shows products.')
        ->and($matrix->dependencyTooltip(['type' => 'permission', 'slug' => GalleryPermission::Manage->value]))->toBeNull();
});

it('draws the column in the matrix and in the editor of one record', function (): void {
    livewire(RolePermissionMatrix::class)
        ->assertTableColumnVisible('dependencies')
        ->assertSee('Requires: View products');

    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->assertTableColumnVisible('dependencies')
        ->assertSee('Blocked by: Delete products');
});

it('has nothing to say on a subject row', function (): void {
    expect(livewire(RolePermissionMatrix::class)->instance()->dependencyBadges(['type' => 'subject', 'slug' => null]))->toBe([]);
});

it('finds a subject through the permissions its rules tie it to', function (): void {
    $records = array_keys(livewire(RolePermissionMatrix::class)->instance()->permissionRecords('gallery'));

    expect($records)->toContain('subject:' . ProductPermission::class)
        ->toContain('subject:' . GalleryPermission::class)
        ->not->toContain('subject:' . CategoryPermission::class);
});
```

Append to `tests/Feature/Livewire/RolePermissionMatrixTest.php`, inside `describe('rendering', ...)`:

```php
    it('needs no dependencies column while no permission declares a rule or a condition', function (): void {
        livewire(RolePermissionMatrix::class)->assertTableColumnHidden('dependencies');
    });
```

- [ ] **Step 2: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/Livewire/DependenciesTest.php`
Expected: FAIL — `DependencyBadge` not found, `dependencyBadges()` undefined.

- [ ] **Step 3: Write the badge**

`src/Support/DependencyBadge.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * One badge next to a permission's name: a rule from this permission's side, or a condition. Filament
 * reads its label and colour itself; the reason goes into the tooltip.
 */
final readonly class DependencyBadge implements HasColor, HasLabel
{
    public function __construct(
        public string $label,
        public string $color,
        /** Why the rule exists, as declared — null when nobody said. */
        public ?string $reason = null,
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
```

- [ ] **Step 4: Badges and related-name search in `PermissionTree`**

In `src/Support/PermissionTree.php` add `use Happenv\LaravelAccessControl\PermissionRuleType;` and a property `private ?bool $hasDependencies = null;`. Add after `describeCondition()`:

```php
    /**
     * What the screens say next to a permission's name: every rule it declares or is the target of,
     * from its own side, and every condition it carries.
     *
     * @return list<DependencyBadge>
     */
    public function dependencies(PermissionDto $permission): array
    {
        $badges = [];

        foreach ($permission->rules as $rule) {
            $declares = $rule->permission === $permission->enum;

            $badges[] = new DependencyBadge(
                label: __('filament-access-control::editor.dependencies.' . $this->dependencyKey($rule->type, $declares), [
                    'permission' => $this->nameOf($declares ? $rule->other : $rule->permission),
                ]),
                color: match ($rule->type) {
                    PermissionRuleType::Requires => 'gray',
                    PermissionRuleType::ImpliedBy => 'info',
                    PermissionRuleType::ConflictsWith => 'danger',
                },
                reason: $rule->reason,
            );
        }

        foreach ($permission->conditions as $condition) {
            $badges[] = new DependencyBadge($this->describeCondition($condition), 'warning');
        }

        return $badges;
    }

    /**
     * Whether any permission declares a rule or carries a condition — without one, the screens need
     * no column for them.
     */
    public function hasDependencies(): bool
    {
        return $this->hasDependencies ??= $this->flatten()->contains(
            fn (PermissionDto $permission): bool => $permission->rules !== [] || $permission->conditions !== [],
        );
    }

    /**
     * A rule seen from one of its ends: the declaring side requires, is implied by or is blocked by;
     * the other side is required by, implies or blocks.
     */
    private function dependencyKey(PermissionRuleType $type, bool $declares): string
    {
        return match ($type) {
            PermissionRuleType::Requires => $declares ? 'requires' : 'required_by',
            PermissionRuleType::ImpliedBy => $declares ? 'implied_by' : 'implies',
            PermissionRuleType::ConflictsWith => $declares ? 'blocked_by' : 'blocks',
        };
    }
```

In `subjectMatches()` extend the permission predicate:

```php
        return $subject->children->contains(fn (PermissionDto $permission): bool => str_contains($this->normalise($permission->name), $needle)
            || str_contains($this->normalise($this->actionLabel($permission)), $needle)
            || str_contains($this->normalise($permission->slug), $needle)
            || $this->relatedMatches($permission, $needle));
```

and add:

```php
    /**
     * Whether a permission a rule ties this one to matches — so searching for the gallery finds the
     * products it requires.
     */
    private function relatedMatches(PermissionDto $permission, string $needle): bool
    {
        foreach ($permission->rules as $rule) {
            $related = $rule->permission === $permission->enum ? $rule->other : $rule->permission;

            if (str_contains($this->normalise($this->nameOf($related)), $needle)) {
                return true;
            }
        }

        return false;
    }
```

Update `groups()`'s docblock: "A term is matched against the group, the subject, every permission under it and the permissions its rules tie them to, but selection happens at SUBJECT granularity…"

- [ ] **Step 5: The column**

In `src/Livewire/Concerns/EditsPermissions.php` add `use Happenv\FilamentAccessControl\Support\DependencyBadge;` and, after `permissionColumn()`:

```php
    /**
     * The rules and conditions next to a permission's name — both ends of every rule — so an operator
     * sees why a cell shows what it shows. Hidden while no permission declares any.
     */
    protected function dependenciesColumn(): TextColumn
    {
        return TextColumn::make('dependencies')
            ->label(__('filament-access-control::editor.columns.dependencies'))
            ->state(fn (array $record): array => $this->dependencyBadges($record))
            ->badge()
            ->tooltip(fn (array $record): ?string => $this->dependencyTooltip($record))
            ->wrap()
            ->visible(fn (): bool => $this->tree()->hasDependencies());
    }

    /**
     * @param  array<string, mixed>  $record
     * @return list<DependencyBadge>
     */
    public function dependencyBadges(array $record): array
    {
        $permission = $record['type'] === 'permission' ? $this->tree()->find((string) $record['slug']) : null;

        return $permission instanceof PermissionDto ? $this->tree()->dependencies($permission) : [];
    }

    /**
     * Why each rule exists, where its declaration says.
     *
     * @param  array<string, mixed>  $record
     */
    public function dependencyTooltip(array $record): ?string
    {
        $reasons = [];

        foreach ($this->dependencyBadges($record) as $badge) {
            if (filled($badge->reason)) {
                $reasons[] = $badge->label . ' — ' . $badge->reason;
            }
        }

        return $reasons === [] ? null : implode(' · ', $reasons);
    }
```

Add the column right after `$this->permissionColumn(),` in the `->columns([...])` of `RolePermissionMatrix::table()` and of `RecordPermissions::table()`:

```php
                $this->dependenciesColumn(),
```

- [ ] **Step 6: English lines**

`resources/lang/en/editor.php`: add `'dependencies' => 'Dependencies',` to `columns` (keep the keys alphabetical there: `dependencies`, `granted`, `inherited`, `permission`), and append:

```php
    'dependencies' => [
        'blocked_by' => 'Blocked by: :permission',
        'blocks' => 'Blocks: :permission',
        'implied_by' => 'Implied by: :permission',
        'implies' => 'Implies: :permission',
        'required_by' => 'Required by: :permission',
        'requires' => 'Requires: :permission',
    ],
```

Run: `php bin/sync-locales.php`

- [ ] **Step 7: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Livewire tests/Feature/Support && vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS (`PermissionTreeTest` included).

- [ ] **Step 8: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src resources/lang tests
git commit -m "feat: a Dependencies column — every rule on both of its ends, every condition"
```

---

### Task 6: Declaration problems

**Files:**
- Create: `src/Support/DeclarationProblems.php`, `resources/views/partials/declaration-problems.blade.php`, `tests/Fixtures/Permissions/BrokenPermission.php`, `tests/Feature/Livewire/DeclarationProblemsTest.php`
- Modify: `src/FilamentAccessControlPlugin.php`, `src/FilamentAccessControlServiceProvider.php:packageRegistered`, `src/Livewire/Concerns/EditsPermissions.php`, `resources/views/livewire/role-permission-matrix.blade.php`, `resources/views/livewire/record-permissions.blade.php`, `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (sync), `tests/Feature/PluginTest.php`, `tests/Feature/Livewire/RolePermissionMatrixTest.php`

**Interfaces:**
- Consumes: `PermissionGraph::problemDetails()`, `PermissionProblemDto`, `PermissionProblemType`, `PermissionRuleType` (library); `PermissionTree::nameOf()`; `DependencyBadge`.
- Produces: `DeclarationProblems` (scoped): `all(): list<PermissionProblemDto>`, `sentences(): list<string>`, `declares(PermissionDefinition): bool`; plugin `declarationProblems(bool|Closure $condition = true): static`, `showsDeclarationProblems(): bool` (default true); `EditsPermissions::declarationProblemSentences(): list<string>`; `abstract protected function plugin(): FilamentAccessControlPlugin` on the trait; `BrokenPermission` fixture (`Merge` requires and conflicts with `Split`; `Adopt` requires `UnregisteredPermission::Orphan`).

- [ ] **Step 1: Write the fixture**

`tests/Fixtures/Permissions/BrokenPermission.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Permissions;

use Happenv\LaravelAccessControl\Attributes\ConflictsWith;
use Happenv\LaravelAccessControl\Attributes\PermissionGroup;
use Happenv\LaravelAccessControl\Attributes\PermissionName;
use Happenv\LaravelAccessControl\Attributes\Requires;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

/**
 * Declarations that can never work — registered only by the tests about them.
 */
#[PermissionGroup(CatalogueGroup::class)]
#[PermissionName('Broken')]
enum BrokenPermission: string implements PermissionDefinition
{
    #[PermissionName('Merge everything')]
    #[Requires(self::Split)]
    #[ConflictsWith(self::Split)]
    case Merge = 'catalogue.broken.merge';

    #[PermissionName('Split everything')]
    case Split = 'catalogue.broken.split';

    #[PermissionName('Adopt orphans')]
    #[Requires(UnregisteredPermission::Orphan)]
    case Adopt = 'catalogue.broken.adopt';
}
```

- [ ] **Step 2: Write the failing tests**

`tests/Feature/Livewire/DeclarationProblemsTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\DependencyBadge;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\BrokenPermission;

use function Pest\Livewire\livewire;

const MERGE_PROBLEM = 'Merge everything can never be allowed: it requires Split everything, which it conflicts with.';
const ADOPT_PROBLEM = 'Adopt orphans declares “Requires” about nowhere.orphan, whose enum is not registered.';

beforeEach(function (): void {
    registerPermissions(BrokenPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

/**
 * @return list<string>
 */
function badgeLabels(object $component, string $slug): array
{
    return array_map(
        fn (DependencyBadge $badge): string => $badge->label,
        $component->dependencyBadges(['type' => 'permission', 'slug' => $slug]),
    );
}

it('lists every declaration problem above the matrix, in words', function (): void {
    livewire(RolePermissionMatrix::class)
        ->assertSee(__('filament-access-control::editor.problems.heading'))
        ->assertSee(MERGE_PROBLEM)
        ->assertSee(ADOPT_PROBLEM);
});

it('marks the rows whose declaration is wrong', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect(badgeLabels($matrix, BrokenPermission::Merge->value))->toContain('Invalid declaration')
        ->and(badgeLabels($matrix, BrokenPermission::Adopt->value))->toContain('Invalid declaration')
        ->and(badgeLabels($matrix, BrokenPermission::Split->value))->not->toContain('Invalid declaration');
});

it('lists them above the editor of one record too', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])->assertSee(MERGE_PROBLEM);
});

it('keeps quiet when the plugin is told to', function (): void {
    plugin()->declarationProblems(false);

    $matrix = livewire(RolePermissionMatrix::class)
        ->assertDontSee(__('filament-access-control::editor.problems.heading'))
        ->assertDontSee(MERGE_PROBLEM);

    expect(badgeLabels($matrix->instance(), BrokenPermission::Merge->value))->not->toContain('Invalid declaration');
});
```

Append to `tests/Feature/Livewire/RolePermissionMatrixTest.php`, inside `describe('rendering', ...)`:

```php
    it('says nothing about declarations that are sound', function (): void {
        livewire(RolePermissionMatrix::class)->assertDontSee(__('filament-access-control::editor.problems.heading'));
    });
```

Append to `tests/Feature/PluginTest.php`:

```php
it('shows declaration problems unless told not to', function (): void {
    expect(plugin()->showsDeclarationProblems())->toBeTrue()
        ->and(plugin()->declarationProblems(false)->showsDeclarationProblems())->toBeFalse()
        ->and(plugin()->declarationProblems(fn (): bool => true)->showsDeclarationProblems())->toBeTrue();
});
```

- [ ] **Step 3: Run them to see them fail**

Run: `vendor/bin/pest tests/Feature/Livewire/DeclarationProblemsTest.php tests/Feature/PluginTest.php`
Expected: FAIL — no callout; `declarationProblems()` undefined.

- [ ] **Step 4: The plugin option**

In `src/FilamentAccessControlPlugin.php`, property next to `$hasCounters`:

```php
    protected bool | Closure $showsDeclarationProblems = true;
```

and, in the Behaviour section after `hasCounters()`:

```php
    /**
     * Whether the screens list the permissions declared in a way that can never work, and mark their
     * rows. On by default: such a declaration is a bug in the application's permission enums.
     */
    public function declarationProblems(bool | Closure $condition = true): static
    {
        $this->showsDeclarationProblems = $condition;

        return $this;
    }

    public function showsDeclarationProblems(): bool
    {
        return (bool) $this->evaluate($this->showsDeclarationProblems);
    }
```

- [ ] **Step 5: The problems, worded**

`src/Support/DeclarationProblems.php`:

```php
<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionProblemDto;
use Happenv\LaravelAccessControl\PermissionGraph;

/**
 * The permissions declared in a way that can never work — the library's `problemDetails()`, worded
 * in the request's locale with the catalogue's names.
 *
 * Scoped to the request, like {@see PermissionTree}: the words are translated.
 */
class DeclarationProblems
{
    /** @var list<PermissionProblemDto>|null */
    private ?array $problems = null;

    public function __construct(
        private readonly PermissionGraph $graph,
        private readonly PermissionTree $tree,
    ) {}

    /**
     * @return list<PermissionProblemDto>
     */
    public function all(): array
    {
        return $this->problems ??= $this->graph->problemDetails();
    }

    /**
     * @return list<string>
     */
    public function sentences(): array
    {
        return array_map(fn (PermissionProblemDto $problem): string => __(
            'filament-access-control::editor.problems.' . str_replace('-', '_', $problem->type->value),
            [
                'permission' => $this->tree->nameOf($problem->permission),
                'other' => $this->tree->nameOf($problem->other),
                'rule' => __('filament-access-control::editor.problems.rules.' . str_replace('-', '_', $problem->ruleType->value)),
            ],
        ), $this->all());
    }

    /**
     * Whether the permission's own declaration is at fault.
     */
    public function declares(PermissionDefinition $permission): bool
    {
        foreach ($this->all() as $problem) {
            if ($problem->permission === $permission) {
                return true;
            }
        }

        return false;
    }
}
```

In `src/FilamentAccessControlServiceProvider.php::packageRegistered()` add (same reason as the comment there):

```php
        $this->app->scoped(DeclarationProblems::class);
```

- [ ] **Step 6: Show them**

In `src/Livewire/Concerns/EditsPermissions.php` add `use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;`, `use Happenv\FilamentAccessControl\Support\DeclarationProblems;`, declare next to the other abstract methods:

```php
    abstract protected function plugin(): FilamentAccessControlPlugin;
```

add:

```php
    /**
     * The declaration problems, worded — nothing when the plugin keeps quiet about them.
     *
     * @return list<string>
     */
    public function declarationProblemSentences(): array
    {
        return $this->plugin()->showsDeclarationProblems()
            ? resolve(DeclarationProblems::class)->sentences()
            : [];
    }
```

and extend `dependencyBadges()`:

```php
    public function dependencyBadges(array $record): array
    {
        $permission = $record['type'] === 'permission' ? $this->tree()->find((string) $record['slug']) : null;

        if (! $permission instanceof PermissionDto) {
            return [];
        }

        $badges = $this->tree()->dependencies($permission);

        if ($this->plugin()->showsDeclarationProblems() && resolve(DeclarationProblems::class)->declares($permission->enum)) {
            $badges[] = new DependencyBadge(__('filament-access-control::editor.dependencies.invalid_declaration'), 'danger');
        }

        return $badges;
    }
```

`resources/views/partials/declaration-problems.blade.php`:

```blade
@php
    $problems = $this->declarationProblemSentences();
@endphp

@if ($problems !== [])
    <x-filament::callout
        icon="heroicon-o-exclamation-triangle"
        color="danger"
        :heading="__('filament-access-control::editor.problems.heading')"
    >
        <x-slot name="description">
            @foreach ($problems as $problem)
                {{ $problem }}@if (! $loop->last)<br />@endif
            @endforeach
        </x-slot>
    </x-filament::callout>
@endif
```

`resources/views/livewire/role-permission-matrix.blade.php`:

```blade
<div class="grid gap-y-4" @include('filament-access-control::partials.unsaved-changes-guard')>
    @include('filament-access-control::partials.declaration-problems')

    {{ $this->table }}

    <x-filament-actions::modals />
</div>
```

`resources/views/livewire/record-permissions.blade.php` — first line inside the outer `<div ...>`:

```blade
    @include('filament-access-control::partials.declaration-problems')
```

- [ ] **Step 7: English lines**

`resources/lang/en/editor.php`: add `'invalid_declaration' => 'Invalid declaration',` to `dependencies`, and append:

```php
    'problems' => [
        'heading' => 'Some permissions are declared in a way that can never work',
        'implies_conflicting' => ':permission can never be allowed: it implies :other, which it conflicts with.',
        'requires_conflicting' => ':permission can never be allowed: it requires :other, which it conflicts with.',
        'unregistered_target' => ':permission declares “:rule” about :other, whose enum is not registered.',
        'rules' => [
            'conflicts_with' => 'Conflicts with',
            'implied_by' => 'Implied by',
            'requires' => 'Requires',
        ],
    ],
```

Run: `php bin/sync-locales.php`

- [ ] **Step 8: Run the tests to see them pass**

Run: `vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS.

- [ ] **Step 9: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src resources tests
git commit -m "feat: declaration problems — listed above the screens, their rows marked"
```

---

### Task 7: The account's "In effect" column and unmet conditions

**Files:**
- Modify: `src/Livewire/RecordPermissions.php`, `resources/views/livewire/record-permissions.blade.php`, `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (sync), `tests/Fixtures/Models/Role.php`
- Test: `tests/Feature/Livewire/InEffectTest.php` (new)

**Interfaces:**
- Consumes: `PermissionResolver::explainer($stored, $principal)`, `AccessControl::roleGrantsOf()` (library); `PermissionCell`, `holderCell` memo `$permissionCells`, `$permissionExplainers`, `forgetResolutions()` (Task 4); `RequiresMFA` (Task 2).
- Produces: in `RecordPermissions`: `effectiveResolution(PermissionDefinition): PermissionResolutionDto`, `effectiveCell(string $slug): ?PermissionCell`, `unmetConditionSummary(): array<string, int>`, protected `effectiveStored(): Closure`, `roleStored(): Closure`, `heldRoles(): list<Model>`, `holdsSuperAdminRole(): bool`; column `in_effect`. The fixture `Role` implements `HoldsGrants` (the library's recommended setup, so a role's grants are read raw and an implied permission shows as implied).

- [ ] **Step 1: Make the fixture role hand over its grants**

`tests/Fixtures/Models/Role.php`: `class Role extends Model implements AuthControllable, HasEditablePermissions, HoldsGrants` with `use Happenv\LaravelAccessControl\Contracts\HoldsGrants;` — `HasPermissions` already provides `getGrants()`.

Run: `vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS (nothing reads a role differently until rules exist).

- [ ] **Step 2: Write the failing test**

`tests/Feature/Livewire/InEffectTest.php`:

```php
<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

covers(RecordPermissions::class);

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
    $this->user = createUser('member@example.com');
});

function unmetLine(int $count): string
{
    return trans_choice('filament-access-control::editor.conditions.unmet', $count, ['condition' => 'Requires MFA']);
}

it('shows what the account has in effect next to what it holds', function (): void {
    $this->editor->update(['permissions' => [ProductPermission::Update->value]]);
    $this->user->roles()->attach($this->editor);
    $this->user->update(['permissions' => [GalleryPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnVisible('in_effect')
        ->assertTableColumnStateSet('holder_' . holderKey($this->user), 'granted', 'permission:' . GalleryPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'missing-requirement', 'permission:' . GalleryPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::Update->value)
        ->assertTableColumnStateSet('in_effect', 'implied', 'permission:' . GalleryPermission::Manage->value)
        ->assertTableColumnStateSet('in_effect', 'not-granted', 'permission:' . ProductPermission::Delete->value);
});

it('shows a permission the account holds but fails a condition of, and says so above the table', function (): void {
    $this->user->update(['permissions' => [GalleryPermission::Publish->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user])
        ->assertTableColumnStateSet('in_effect', 'unmet-condition', 'permission:' . GalleryPermission::Publish->value)
        ->assertSee(unmetLine(1));

    $this->user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . GalleryPermission::Publish->value)
        ->assertDontSee(unmetLine(1));
});

it('keeps a permission in effect when a staged revocation leaves a role granting it', function (): void {
    $this->editor->update(['permissions' => [ProductPermission::View->value]]);
    $this->user->roles()->attach($this->editor);
    $this->user->update(['permissions' => [ProductPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user->fresh(), 'deferred' => true])
        ->call('toggle', holderKey($this->user), ProductPermission::View->value)
        ->assertTableColumnStateSet('holder_' . holderKey($this->user), 'revoked', 'permission:' . ProductPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::View->value);
});

it('shows the consequence of a staged grant before it is saved', function (): void {
    $this->user->update(['permissions' => [GalleryPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user, 'deferred' => true])
        ->assertTableColumnStateSet('in_effect', 'missing-requirement', 'permission:' . GalleryPermission::View->value)
        ->call('toggle', holderKey($this->user), ProductPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . GalleryPermission::View->value);

    expect($this->user->fresh()->getPermissions()->all())->toBe([GalleryPermission::View->value]);
});

it('counts a super-admin role as holding everything, conditions still applying', function (): void {
    $this->user->roles()->attach(createRole(Role::ADMINISTRATOR, name: 'Administrator'));

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::Delete->value)
        ->assertTableColumnStateSet('in_effect', 'unmet-condition', 'permission:' . GalleryPermission::Publish->value);
});

it('draws no In effect column for a role', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->assertTableColumnHidden('in_effect')
        ->assertDontSee(unmetLine(1));
});
```

- [ ] **Step 3: Run it to see it fail**

Run: `vendor/bin/pest tests/Feature/Livewire/InEffectTest.php`
Expected: FAIL — column `in_effect` does not exist.

- [ ] **Step 4: Resolve what the account has in effect**

In `src/Livewire/RecordPermissions.php` add imports `Closure` (already there), `Filament\Tables\Columns\IconColumn`, `Happenv\FilamentAccessControl\Support\PermissionCell`, `Happenv\FilamentAccessControl\Support\PermissionCellState`, `Happenv\LaravelAccessControl\Contracts\AuthControllable`, `Happenv\LaravelAccessControl\Contracts\PermissionDefinition`, `Happenv\LaravelAccessControl\Dto\PermissionResolutionDto`, `Happenv\LaravelAccessControl\Facades\AccessControl`, `Happenv\LaravelAccessControl\PermissionResolver`, `Happenv\LaravelAccessControl\Traits\HasRoles`, `Illuminate\Contracts\Auth\Authenticatable`.

Add a constant and the methods:

```php
    /** The key of the account's "In effect" resolution among the per-holder ones. */
    private const string IN_EFFECT = 'in-effect';

    /**
     * What the account has in effect — its roles, the rules, restrictions and its conditions
     * together — with the staged changes of its direct grants on top.
     */
    public function effectiveResolution(PermissionDefinition $permission): PermissionResolutionDto
    {
        $explain = $this->permissionExplainers[self::IN_EFFECT] ??= resolve(PermissionResolver::class)->explainer(
            $this->effectiveStored(),
            $this->record instanceof Authenticatable ? $this->record : null,
        );

        return $explain($permission);
    }

    public function effectiveCell(string $slug): ?PermissionCell
    {
        $permission = $this->tree()->find($slug);

        if (! $permission instanceof PermissionDto) {
            return null;
        }

        return $this->permissionCells[self::IN_EFFECT][$slug] ??= PermissionCell::of(
            $this->effectiveResolution($permission->enum),
            $this->tree(),
        );
    }

    /**
     * The conditions the account fails, each with how many of the permissions it would otherwise
     * have in effect it withholds — for the callout above the table.
     *
     * @return array<string, int>
     */
    public function unmetConditionSummary(): array
    {
        if ($this->isRole() || ! $this->record instanceof Authenticatable) {
            return [];
        }

        $unmet = [];

        foreach ($this->tree()->permissions() as $permission) {
            $resolution = $this->effectiveResolution($permission->enum);

            // Only what the conditions ALONE withhold: a permission the rules block or a restriction
            // withholds would stay out with every condition met.
            if (! $resolution->allowed || $resolution->restricted) {
                continue;
            }

            foreach ($resolution->unmetConditions as $condition) {
                $label = $this->tree()->describeCondition($condition);
                $unmet[$label] = ($unmet[$label] ?? 0) + 1;
            }
        }

        return $unmet;
    }

    /**
     * What the account stores as the screen shows it: its direct grants with the staged changes on
     * top, and what its roles store.
     *
     * @return Closure(PermissionDefinition): bool
     */
    protected function effectiveStored(): Closure
    {
        $key = $this->recordKey();
        $roles = $this->roleStored();

        return fn (PermissionDefinition $permission): bool => $this->isGranted($key, (string) $permission->value) || $roles($permission);
    }

    /**
     * What the account's roles store: everything for a super-admin role; read by the library when
     * the account uses its `HasRoles`; otherwise as this screen edits the roles.
     *
     * @return Closure(PermissionDefinition): bool
     */
    protected function roleStored(): Closure
    {
        if ($this->holdsSuperAdminRole()) {
            return fn (PermissionDefinition $permission): bool => true;
        }

        if ($this->record instanceof AuthControllable && isset(class_uses_recursive($this->record)[HasRoles::class])) {
            return AccessControl::roleGrantsOf($this->record);
        }

        $stored = [];

        foreach ($this->heldRoles() as $role) {
            if ($role instanceof HasEditablePermissions) {
                foreach ($role->getPermissions() as $slug) {
                    $stored[$slug] = true;
                }
            }
        }

        return fn (PermissionDefinition $permission): bool => isset($stored[(string) $permission->value]);
    }

    protected function holdsSuperAdminRole(): bool
    {
        foreach ($this->heldRoles() as $role) {
            if ($this->plugin()->isSuperAdminRole($role)) {
                return true;
            }
        }

        return false;
    }
```

Split `roles()` so what the account holds does not depend on whether the screen shows it:

```php
    /**
     * The roles the record holds, shown or not.
     *
     * @return list<Model>
     */
    protected function heldRoles(): array
    {
        if ($this->isRole() || ! method_exists($this->record, 'getRoles')) {
            return [];
        }

        $roles = [];

        foreach ($this->record->getRoles() as $role) {
            if ($role instanceof Model) {
                $roles[] = $role;
            }
        }

        return $roles;
    }

    /**
     * The roles the screen shows — none when told not to show what they grant.
     *
     * @return list<Model>
     */
    public function roles(): array
    {
        return $this->showInherited ? $this->heldRoles() : [];
    }
```

Add the column at the end of `table()`'s `->columns([...])`, after `inherited`:

```php
                IconColumn::make('in_effect')
                    ->label(__('filament-access-control::editor.columns.in_effect'))
                    ->alignCenter()
                    ->state(fn (array $record): ?string => $record['type'] === 'permission'
                        ? $this->effectiveCell((string) $record['slug'])?->state->value
                        : null)
                    ->icon(fn (?string $state): ?Heroicon => PermissionCellState::tryFrom((string) $state)?->icon())
                    ->color(fn (?string $state): ?string => PermissionCellState::tryFrom((string) $state)?->color())
                    ->tooltip(fn (array $record): ?string => $record['type'] === 'permission'
                        ? $this->effectiveCell((string) $record['slug'])?->tooltip()
                        : null)
                    ->visible(fn (): bool => ! $this->isRole()),
```

- [ ] **Step 5: The callout**

`resources/views/livewire/record-permissions.blade.php` — add `$unmetConditions = $this->unmetConditionSummary();` to the `@php` block, and after the `@if ($locked) … @endif` chain:

```blade
    @if ($unmetConditions !== [])
        <x-filament::callout icon="heroicon-o-shield-exclamation" color="warning">
            <x-slot name="description">
                @foreach ($unmetConditions as $condition => $count)
                    {{ trans_choice('filament-access-control::editor.conditions.unmet', $count, ['condition' => $condition]) }}@if (! $loop->last)<br />@endif
                @endforeach
            </x-slot>
        </x-filament::callout>
    @endif
```

- [ ] **Step 6: English lines**

`resources/lang/en/editor.php`: add `'in_effect' => 'In effect',` to `columns` (between `granted` and `inherited`), and to `conditions`:

```php
        'unmet' => ':condition: one permission this account holds is not in effect until it meets this condition.|:condition: :count permissions this account holds are not in effect until it meets this condition.',
```

Run: `php bin/sync-locales.php`

- [ ] **Step 7: Run the tests to see them pass**

Run: `vendor/bin/pest tests/Feature/Livewire && vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS.

- [ ] **Step 8: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse`

```bash
git add src resources tests
git commit -m "feat: an account's permissions In effect — roles, rules, restrictions and conditions together"
```

---

### Task 8: The permission graph

**Files:**
- Restore from `4660746`: `package.json`, `bin/build.js`, `.nvmrc`, `.prettierrc`, `.prettierignore`, `.github/workflows/assets.yml`
- Create: `resources/js/components/permission-graph.js`, `resources/dist/components/permission-graph.js` (built), `package-lock.json`, `resources/views/partials/permission-graph.blade.php`, `tests/Feature/Livewire/PermissionGraphTest.php`
- Modify: `src/FilamentAccessControlPlugin.php`, `src/FilamentAccessControlServiceProvider.php:packageBooted`, `src/Livewire/Concerns/EditsPermissions.php`, `src/Livewire/RolePermissionMatrix.php`, `src/Livewire/RecordPermissions.php`, `resources/lang/en/editor.php`, `resources/lang/*/editor.php` (sync), `tests/Feature/PluginTest.php`

**Interfaces:**
- Consumes: `AccessControl::diagram()->catalogue() / forPrincipal() / render($diagram, 'mermaid')` (library).
- Produces: plugin `diagrams(bool|Closure $condition = true): static`, `hasDiagrams(): bool` (default true); table toolbar action `permissionGraph`; `abstract protected function permissionDiagram(): PermissionDiagram` on the trait; Alpine component `permission-graph` of package `happenv-com/filament-access-control`.

- [ ] **Step 1: Write the failing tests**

`tests/Feature/Livewire/PermissionGraphTest.php`:

```php
<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\BrokenPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class, BrokenPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

it('draws the catalogue as a Mermaid graph, its source on the page until mermaid.js draws it', function (): void {
    livewire(RolePermissionMatrix::class)
        ->mountAction(TestAction::make('permissionGraph')->table())
        ->assertSee('flowchart LR')
        ->assertSeeHtml('x-ref="source"')
        ->assertSeeHtml('permissionGraph(');
});

it('draws what one account holds and why', function (): void {
    $user = createUser('member@example.com', [GalleryPermission::View->value]);

    livewire(RecordPermissions::class, ['record' => $user])
        ->mountAction(TestAction::make('permissionGraph')->table())
        ->assertSee('flowchart LR')
        ->assertSee('View gallery');
});

it('ships the graph script as a lazily loaded Alpine component', function (): void {
    expect(FilamentAsset::getAlpineComponentSrc('permission-graph', 'happenv-com/filament-access-control'))->toContain('permission-graph.js')
        ->and(dirname(__DIR__, 3) . '/resources/dist/components/permission-graph.js')->toBeFile();
});

it('offers no graph when told not to, and everything else keeps working (invariant 11)', function (): void {
    plugin()->diagrams(false);

    $this->editor->update(['permissions' => [GalleryPermission::Archive->value, ProductPermission::Delete->value]]);

    livewire(RolePermissionMatrix::class)
        ->assertActionHidden(TestAction::make('permissionGraph')->table())
        ->assertTableColumnVisible('dependencies')
        ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'conflict', 'permission:' . GalleryPermission::Archive->value)
        ->assertSee('Merge everything can never be allowed: it requires Split everything, which it conflicts with.');
});
```

Append to `tests/Feature/PluginTest.php`:

```php
it('draws permission graphs unless told not to', function (): void {
    expect(plugin()->hasDiagrams())->toBeTrue()
        ->and(plugin()->diagrams(false)->hasDiagrams())->toBeFalse();
});
```

- [ ] **Step 2: Run them to see them fail**

Run: `vendor/bin/pest tests/Feature/Livewire/PermissionGraphTest.php`
Expected: FAIL — action `permissionGraph` does not exist.

- [ ] **Step 3: Restore the asset pipeline and build the component**

```bash
git checkout 4660746 -- package.json bin/build.js .nvmrc .prettierrc .prettierignore .github/workflows/assets.yml
```

Edit `package.json` so `format` and `lint` cover `resources/js bin` only (there is no CSS), then install — this pins the current versions in `package-lock.json`:

```bash
npm install
npm install --save-dev mermaid
```

Replace the `entries` and the context options in `bin/build.js`:

```js
const entries = [
    // The permission graph: mermaid.js, loaded only when the graph's modal opens (`x-load`).
    { in: 'resources/js/components/permission-graph.js', out: 'components/permission-graph' },
]
```

```js
const context = await esbuild.context({
    entryPoints: entries,
    outdir: 'resources/dist',
    bundle: true,
    // An ES module Filament imports on demand; `browser`, because mermaid's dependencies resolve
    // browser builds.
    format: 'esm',
    platform: 'browser',
    target: ['es2020'],
    minify: !isDev,
    sourcemap: isDev ? 'inline' : false,
    sourcesContent: isDev,
    treeShaking: true,
    define: {
        'process.env.NODE_ENV': isDev ? `'development'` : `'production'`,
    },
    logLevel: 'info',
})
```

Update the file's header comment: "Builds the package's assets into resources/dist, which is committed and served by Filament as is (see the FilamentAsset registration in the service provider). CI rebuilds and fails when the committed files differ."

`resources/js/components/permission-graph.js`:

```js
import mermaid from 'mermaid'

let drawn = 0

/**
 * Draws a permission graph with mermaid.js. The Mermaid source stays on the page until the drawing
 * succeeds — and for good when it cannot: the graph is an enhancement, never the only way to read it.
 */
export default function permissionGraph({ source }) {
    return {
        async init() {
            try {
                mermaid.initialize({
                    startOnLoad: false,
                    securityLevel: 'strict',
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'default',
                })

                const { svg } = await mermaid.render(`filament-access-control-graph-${++drawn}`, source)

                this.$refs.canvas.innerHTML = svg
                this.$refs.source.hidden = true
            } catch (error) {
                console.warn('filament-access-control: the permission graph could not be drawn.', error)
            }
        },
    }
}
```

```bash
npm run format
npm run lint
npm run build
ls -lh resources/dist/components/permission-graph.js
```

Expected: the build succeeds and writes one file of roughly 2–4 MB. If esbuild fails to resolve a mermaid dependency, add that package's name to `external` only if mermaid loads it lazily and the graph still renders in Task 10's visual check; otherwise report the error.

- [ ] **Step 4: Register the component**

`src/FilamentAccessControlServiceProvider.php` — add `use Filament\Support\Assets\AlpineComponent;`, `use Filament\Support\Facades\FilamentAsset;` and, at the end of `packageBooted()`:

```php
        // Loaded by the permission graph's modal alone (`x-load`), never with the panel: mermaid.js
        // is megabytes, and nothing else needs it.
        FilamentAsset::register([
            AlpineComponent::make('permission-graph', __DIR__ . '/../resources/dist/components/permission-graph.js'),
        ], package: 'happenv-com/filament-access-control');
```

- [ ] **Step 5: The plugin option and the action**

`src/FilamentAccessControlPlugin.php` — property `protected bool | Closure $hasDiagrams = true;` and:

```php
    /**
     * Whether the screens offer a permission graph, drawn by mermaid.js in a modal. The script is
     * loaded only when the modal opens, and nothing else depends on it.
     */
    public function diagrams(bool | Closure $condition = true): static
    {
        $this->hasDiagrams = $condition;

        return $this;
    }

    public function hasDiagrams(): bool
    {
        return (bool) $this->evaluate($this->hasDiagrams);
    }
```

`resources/views/partials/permission-graph.blade.php`:

```blade
<div
    x-load
    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('permission-graph', 'happenv-com/filament-access-control') }}"
    x-data="permissionGraph({ source: @js($source) })"
    wire:ignore
>
    <div x-ref="canvas" style="overflow: auto"></div>

    {{-- The Mermaid source, until mermaid.js has drawn it — and for good if it cannot. --}}
    <pre x-ref="source" style="margin: 0; overflow: auto; font-size: 0.75rem; line-height: 1.25rem">{{ $source }}</pre>
</div>
```

In `src/Livewire/Concerns/EditsPermissions.php` add `use Filament\Support\Enums\Width;`, `use Happenv\LaravelAccessControl\Diagram\PermissionDiagram;`, `use Happenv\LaravelAccessControl\Facades\AccessControl;`, `use Illuminate\Contracts\View\View;`, the abstract method:

```php
    /**
     * What the permission graph draws: the catalogue, or one holder.
     */
    abstract protected function permissionDiagram(): PermissionDiagram;
```

the action:

```php
    protected function permissionGraphAction(): Action
    {
        return Action::make('permissionGraph')
            ->label(__('filament-access-control::editor.actions.permission_graph.label'))
            ->icon(Heroicon::OutlinedShare)
            ->link()
            ->color('gray')
            ->visible(fn (): bool => $this->plugin()->hasDiagrams())
            ->modalHeading(__('filament-access-control::editor.actions.permission_graph.heading'))
            ->modalDescription(__('filament-access-control::editor.actions.permission_graph.description'))
            ->modalWidth(Width::SevenExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('filament-access-control::editor.actions.permission_graph.close'))
            ->modalContent(fn (): View => view('filament-access-control::partials.permission-graph', [
                'source' => AccessControl::diagram()->render($this->permissionDiagram(), 'mermaid'),
            ]));
    }
```

and in `configurePermissionTable()`'s `toolbarActions([...])`, right after the `collapseAll` action:

```php
                $this->permissionGraphAction(),
```

`src/Livewire/RolePermissionMatrix.php` (imports `Happenv\LaravelAccessControl\Diagram\PermissionDiagram`, `Happenv\LaravelAccessControl\Facades\AccessControl`):

```php
    protected function permissionDiagram(): PermissionDiagram
    {
        return AccessControl::diagram()->catalogue();
    }
```

`src/Livewire/RecordPermissions.php` (imports `Happenv\LaravelAccessControl\Diagram\PermissionDiagram`, `InvalidArgumentException` already there):

```php
    /**
     * What this record holds and why — for a record the library can ask; otherwise the catalogue,
     * which still explains the rules.
     */
    protected function permissionDiagram(): PermissionDiagram
    {
        if (! $this->record instanceof AuthControllable) {
            return AccessControl::diagram()->catalogue();
        }

        try {
            return AccessControl::diagram()->forPrincipal($this->record);
        } catch (InvalidArgumentException) {
            // A role of the record cannot be asked what it holds.
            return AccessControl::diagram()->catalogue();
        }
    }
```

- [ ] **Step 6: English lines**

`resources/lang/en/editor.php`, inside `actions`:

```php
        'permission_graph' => [
            'close' => 'Close',
            'description' => 'Drawn from what is saved — changes not saved yet are not in it.',
            'heading' => 'Permission graph',
            'label' => 'Permission graph',
        ],
```

Run: `php bin/sync-locales.php`

- [ ] **Step 7: Run the tests to see them pass**

Run: `vendor/bin/pest --testsuite=Unit,Feature`
Expected: PASS.

- [ ] **Step 8: Static checks and commit**

Run: `vendor/bin/pint && vendor/bin/phpstan analyse && npm run lint`

```bash
git add package.json package-lock.json bin/build.js .nvmrc .prettierrc .prettierignore .github/workflows/assets.yml resources src tests
git commit -m "feat: a permission graph in a modal — Mermaid, loaded only when it opens"
```

---

### Task 9: Translations into every shipped locale

**Files:**
- Modify: `resources/lang/<63 locales>/editor.php`, `tests/Unit/TranslationsTest.php`

**Interfaces:**
- Consumes: `bin/sync-locales.php <json-dir>` (Task 2).

Until now every locale holds the new lines in English. This task translates them. The content is produced here, locale by locale; the plan fixes the source, the terms and the checks.

The 28 new lines, all in `editor.php` (English source in `resources/lang/en/editor.php`):
`columns.dependencies`, `columns.in_effect`; `dependencies.{blocked_by, blocks, implied_by, implies, invalid_declaration, required_by, requires}`; `cells.{blocked_by, grant_explicitly, implied_by, missing, restricted, unmet_condition}`; `conditions.{requires_mfa, unmet}`; `problems.{heading, implies_conflicting, requires_conflicting, unregistered_target}`, `problems.rules.{conflicts_with, implied_by, requires}`; `actions.permission_graph.{close, description, heading, label}`.

- [ ] **Step 1: Pin placeholders in a test first**

Append to `tests/Unit/TranslationsTest.php`:

```php
it('keeps every placeholder of the English line', function (?string $english, ?string $translation): void {
    if ($english === null) {
        expect(true)->toBeTrue();

        return;
    }

    $placeholders = static function (string $line): array {
        preg_match_all('/:([a-z_]+)/', $line, $matches);
        $names = array_values(array_unique($matches[1]));
        sort($names);

        return $names;
    };

    $actual = translationKeys($translation);

    foreach (translationKeys($english) as $key => $line) {
        expect($placeholders((string) ($actual[$key] ?? '')))->toBe($placeholders((string) $line), $key);
    }
})->with('translation files');
```

Run: `vendor/bin/pest tests/Unit/TranslationsTest.php`
Expected: PASS (the new lines are still English; existing translations already keep their placeholders). If an existing line fails, fix that translation.

```bash
git add tests/Unit/TranslationsTest.php
git commit -m "test: every translation keeps the placeholders of its English line"
```

- [ ] **Step 2: Translate, locale by locale**

For each locale directory in `resources/lang` except `en` (63 of them), write `/tmp/fac-translations/<locale>.json` (use the session scratchpad directory instead of `/tmp` when one is given) with exactly the 28 lines, nested as in the PHP file:

```json
{
    "editor": {
        "columns": { "dependencies": "…", "in_effect": "…" },
        "dependencies": { "blocked_by": "…", "blocks": "…", "implied_by": "…", "implies": "…", "invalid_declaration": "…", "required_by": "…", "requires": "…" },
        "cells": { "blocked_by": "…", "grant_explicitly": "…", "implied_by": "…", "missing": "…", "restricted": "…", "unmet_condition": "…" },
        "conditions": { "requires_mfa": "…", "unmet": "…" },
        "problems": {
            "heading": "…", "implies_conflicting": "…", "requires_conflicting": "…", "unregistered_target": "…",
            "rules": { "conflicts_with": "…", "implied_by": "…", "requires": "…" }
        },
        "actions": { "permission_graph": { "close": "…", "description": "…", "heading": "…", "label": "…" } }
    }
}
```

Rules for every locale:
- Reuse the locale's own terms already in its `editor.php` — the words for *permission* (`columns.permission`), *granted* (`columns.granted`), *role* (`columns.inherited`), *unsaved* (`staged_marker`), *restricted* (`restricted_hint`) — so the new lines match the screen they appear on.
- Keep every `:placeholder` exactly (`:permission`, `:permissions`, `:other`, `:rule`, `:condition`, `:count`).
- `conditions.unmet` is a `trans_choice` line: write as many `|`-separated forms as the locale's plural rules need (Laravel's pluralizer picks by locale — e.g. three for `pl`, `ru`, `uk`, `cs`; one for `ja`, `zh_*`, `ko`), each form keeping `:condition`; the forms for more than one keep `:count`.
- "MFA" stays an acronym unless the locale has an established one (e.g. `de` "MFA", `fr` "MFA" or "A2F" — prefer the one Filament's own `vendor/filament/filament/resources/lang/<locale>` uses for multi-factor authentication, when it does).
- `actions.permission_graph.close`: use the word Filament itself uses for closing a modal in that locale (`vendor/filament/actions/resources/lang/<locale>/*.php` or `vendor/filament/support/resources/lang/<locale>/*.php`; grep for the English "Close").
- Typographic quotes in `problems.unregistered_target`: use the locale's own quotation marks around `:rule` („…“, «…», 「…」, "…").

Apply and check, in batches of about ten locales:

```bash
php bin/sync-locales.php /tmp/fac-translations
vendor/bin/pest tests/Unit/TranslationsTest.php tests/Unit/LocalesTest.php
```

Expected: PASS after every batch.

- [ ] **Step 3: Find what is still English**

```bash
php -r '
$flat = function (array $a, string $p = "") use (&$flat): array { $o = []; foreach ($a as $k => $v) { $o += is_array($v) ? $flat($v, "$p$k.") : ["$p$k" => $v]; } return $o; };
$en = $flat(require "resources/lang/en/editor.php");
foreach (glob("resources/lang/*/editor.php") as $file) {
    if (str_contains($file, "/en/")) continue;
    $same = array_keys(array_intersect_assoc($flat(require $file), $en));
    if ($same !== []) echo basename(dirname($file)), ": ", implode(", ", $same), PHP_EOL;
}'
```

Expected: only lines that legitimately stay the same (an acronym like `conditions.requires_mfa` in a locale that keeps "MFA" and nothing else, a loanword). Translate anything else and re-run Step 2's commands.

- [ ] **Step 4: Commit**

```bash
git add resources/lang
git commit -m "feat: translate the rules, conditions and graph screens into every shipped locale"
```

---

### Task 10: Documentation, all gates on Filament 4 and 5, visual check

**Files:**
- Modify: `README.md`, `UPGRADING.md`, `CHANGELOG.md`

- [ ] **Step 1: README**

- *Requirements* table: `happenv-com/laravel-access-control` → `3.1+`.
- *Key features*: add three bullets — "**Why, not just whether.** Every cell shows what laravel-access-control resolves: in effect, implied, missing a requirement, blocked by a conflict, restricted, or withheld by a condition — the tooltip names the permissions involved. See [Rules, conditions and graphs](#rules-conditions-and-graphs)."; "**`#[RequiresMFA]`.** A permission that needs multi-factor authentication on the account."; "**Permission graphs.** The catalogue, or one account, drawn with Mermaid in a modal."
- *Installation*: after the theme step add: "Publish the package's script (the permission graph's mermaid.js) with Filament's other assets — `php artisan filament:assets`, which Filament's own install already runs on `composer update`."
- New section `### Rules, conditions and graphs` under *Usage*, after *Surfaces*:

````markdown
### Rules, conditions and graphs

laravel-access-control 3 lets permissions depend on each other (`#[Requires]`, `#[ImpliedBy]`, `#[ConflictsWith]`) and on the account (conditions). The screens show all of it; they never decide anything themselves.

**Cells.** A role's cell shows what the rules make of the role's grants; a user's *In effect* column what its roles, the rules, runtime restrictions and its conditions leave it:

| Icon | Colour | Means |
|---|---|---|
| check-circle | success | stored and in effect |
| check-circle | info | in effect, implied by another permission (a click grants it explicitly) |
| exclamation-triangle | warning | granted, but a permission it requires is not in effect |
| no-symbol | danger | granted, but blocked by a permission it conflicts with |
| lock-closed | gray | granted, but the application restricts it right now |
| shield-exclamation | warning | granted, but the account does not meet a condition |
| x-circle | danger | not granted |

The tooltip names the permissions involved. In deferred mode a changed cell takes the primary colour, and every other cell already shows the consequence of the change.

**Dependencies.** A column next to the permission's name lists every rule from that permission's side — *Requires* / *Required by*, *Implied by* / *Implies*, *Blocked by* / *Blocks* — and every condition. The rule's `reason` is its tooltip. Searching also finds the permissions a rule ties to what you typed.

**Conditions — `#[RequiresMFA]`.** Put it on a permission enum or case to withhold the permission from any account without multi-factor authentication enabled on the panel:

```php
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;

enum OrderPermission: string implements PermissionDefinition
{
    #[RequiresMFA]
    case Refund = 'order.refund';

    // The providers of a named panel, rather than the current one:
    #[RequiresMFA(panel: 'admin')]
    case Export = 'order.export';
}
```

It fails closed: an account without MFA, an account the panel's providers cannot ask (an API key) and a panel without multi-factor authentication do not meet it. The user editor says above the table how many permissions a condition withholds. Your own conditions are attributes implementing laravel-access-control's `PermissionCondition` — see its README; implement `DescribesPermissionCondition` to name them on these screens. A `Gate::before()` that answers first skips conditions like any other gate check.

**Declaration problems.** A permission declared so that it can never be allowed (it requires what it conflicts with), or a rule pointing at an enum nobody registered, is listed above the screens and marked *Invalid declaration*. `->declarationProblems(false)` hides both.

**Graphs.** *Permission graph* opens the catalogue (on the access control page) or the edited record (on an editor) as a Mermaid diagram, drawn from what is saved. mermaid.js loads only when the modal opens; if it cannot, the Mermaid source is shown instead. `->diagrams(false)` removes the action.

For the *In effect* column to tell implied permissions from stored ones, roles using `HasPermissions` should implement laravel-access-control's `HoldsGrants`.
````

- [ ] **Step 2: UPGRADING**

Under the intro of `UPGRADING.md` add:

````markdown
## From 2.x to 3.0

3.0 follows laravel-access-control 3 — rules between permissions and conditions on accounts — and needs its 3.1:

```bash
composer require happenv-com/filament-access-control:^3.0 happenv-com/laravel-access-control:^3.1
php artisan filament:assets
```

Nothing in your code has to change. What looks different:

- A role's permission cells show what the rules make of its grants — new icons for *implied*, *missing requirement*, *blocked*, *restricted* (see the README's *Rules, conditions and graphs*). Without rules or conditions every cell looks as before.
- A staged (unsaved) cell is `primary`; it was `warning`, which now means a missing requirement.
- A restricted permission's marker next to its name is a grey lock; it was a red no-entry sign, which now means a lost conflict.
- The user editor has an *In effect* column, and a callout when the account fails a condition.
- A *Dependencies* column appears once any permission declares a rule or a condition; declaration problems are listed above the screens (`->declarationProblems(false)` hides them).
- A *Permission graph* action (`->diagrams(false)` removes it).

If a test of yours asserts a role cell's state, `granted` is now `effective` and `revoked` is `not-granted`; a user's direct column keeps `granted` / `revoked`.
````

- [ ] **Step 3: CHANGELOG**

Above `## v2.0.0 - 2026-09-29` add:

```markdown
## v3.0.0 - Unreleased

### Features

- Cells show what laravel-access-control 3 resolves: in effect, implied, missing requirement, blocked by a conflict, restricted, condition unmet, not granted — with the permissions involved in the tooltip. Staged changes are resolved before they are saved.
- A *Dependencies* column: every rule in both directions, with its reason, and every condition.
- `#[RequiresMFA]`: a condition met only by an account with multi-factor authentication enabled on the panel; fails closed.
- The user editor's *In effect* column and a callout for unmet conditions.
- Declaration problems listed above the screens and marked on their rows — `->declarationProblems()`.
- A *Permission graph* modal (Mermaid, lazily loaded) — `->diagrams()`.
- Search also matches the permissions a rule ties to.

### Requirements

- happenv-com/laravel-access-control 3.1+.
```

- [ ] **Step 4: Every gate, on Filament 5 and on Filament 4**

```bash
composer ci
composer update "filament/*" --with "filament/filament:^4.13.3" -W
vendor/bin/pest --testsuite=Unit,Feature
vendor/bin/phpstan analyse
composer update "filament/*" -W
composer ci
```

Expected: green each time. A Filament 4 difference (a contract method, a component prop) is fixed in the package so both pass.

- [ ] **Step 5: Commit**

```bash
git add README.md UPGRADING.md CHANGELOG.md
git commit -m "docs: rules, conditions and graphs; upgrading from 2.x"
```

- [ ] **Step 6: Visual check in filament-playground**

`/Users/bgajda/Lab/filament-playground` has uncommitted work of its own: change only what is listed here and commit nothing there.

1. In its `composer.json`, add a path repository for the library and give both packages a version (edit the JSON; keep the existing `filament-access-control` entry's `url`):

```json
        {
            "name": "laravel-access-control",
            "type": "path",
            "url": "/Users/bgajda/Packages/laravel-access-control",
            "options": { "symlink": true, "versions": { "happenv-com/laravel-access-control": "3.1.0" } }
        },
```

and add `"versions": { "happenv-com/filament-access-control": "3.0.0" }` to the `filament-access-control` entry's `options`. Then `composer update happenv-com/filament-access-control happenv-com/laravel-access-control -W && php artisan filament:assets && php artisan optimize:clear`.
2. In `app/Permissions/ProjectPermission.php` / `IssuePermission.php` add a `#[Requires]`, an `#[ImpliedBy]`, a `#[ConflictsWith]` (with a `reason`) and a `#[RequiresMFA]` between existing cases; make a role store a combination that shows every cell state.
3. With the Chrome tools, sign in at https://filament-playground.test and capture: the access control page (groups expanded, one staged change in deferred mode), the role editor, the user editor (In effect column and the MFA callout), the declaration-problems callout (temporarily add a case that requires what it conflicts with), and the Permission graph modal. Send the screenshots to the user with `SendUserFile`.
4. Revert the temporary broken case.

---

### Task 11: Release

- [ ] **Step 1: Wait for laravel-access-control v3.1.0**

The library's pull request must be merged and `v3.1.0` tagged (the user's call). Check: `git -C ../laravel-access-control ls-remote --tags origin v3.1.0`.

- [ ] **Step 2: Depend on the released library**

Remove the `repositories` block from `composer.json`, then:

```bash
composer normalize
composer update happenv-com/laravel-access-control -W
composer ci
git add composer.json
git commit -m "build: require the released laravel-access-control 3.1"
```

- [ ] **Step 3: Push and open the pull request — ask the user first**

Outward-facing: confirm in chat, then:

```bash
git push -u origin 3.x
git push -u origin feat/access-control-3
gh pr create --base 3.x --title "feat: 3.x — rules, conditions and graphs on the permission screens" --body-file - <<'EOF'
## Summary
- Cells show what laravel-access-control 3.1 resolves (in effect, implied, missing requirement, conflict, restricted, condition unmet, not granted), staged changes included; a Dependencies column with every rule in both directions and every condition.
- `#[RequiresMFA]` — a permission condition met only by an account with MFA enabled on the panel; fails closed.
- The user editor's In effect column and unmet-conditions callout; declaration problems above the screens; a lazily loaded Mermaid permission graph.
- Every new string in all 64 locales.

Spec: `docs/superpowers/specs/2026-09-29-access-control-3-conditions-and-rules-ui-design.md`; plan: `docs/superpowers/plans/2026-09-29-filament-access-control-3-rules-ui.md`.

## Test plan
- [ ] `composer ci` on Filament 5 and 4
- [ ] Assets workflow: `resources/dist` matches `npm run build`
- [ ] Visual check in filament-playground (screenshots in the conversation)
EOF
```

Then follow the pull request's CI with the `ccd_pr` tools (`get_status`, `bind_pr` if needed). After the merge, tagging `v3.0.0` is the user's call.
