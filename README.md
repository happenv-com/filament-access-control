# Filament Access Control

[![Latest Version](https://img.shields.io/github/v/release/happenv-com/filament-access-control?style=flat-square&label=version)](https://github.com/happenv-com/filament-access-control/releases)
[![Tests](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/tests.yml?label=tests&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/phpstan.yml?label=phpstan&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/phpstan.yml)
[![Quality](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/quality.yml?label=code%20quality&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/quality.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/happenv-com/filament-access-control.svg?style=flat-square)](https://packagist.org/packages/happenv-com/filament-access-control)
[![License](https://img.shields.io/github/license/happenv-com/filament-access-control.svg?style=flat-square)](LICENSE.md)

The Filament screens for [happenv-com/laravel-access-control](https://github.com/happenv-com/laravel-access-control): every role against every permission in one grid, and the permissions of a single role or user wherever you want them — a section of the edit form, a tab, a page of their own. Changes are written the moment they are clicked, or collected until the operator presses **Save permissions**.

```php
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;

$panel->plugin(
    FilamentAccessControlPlugin::make()
        ->roleModel(Role::class)
        ->superAdminRole('administrator'),
);

// In the role's (or the user's) form:
PermissionEditor::make()->deferred();
```

## Key features

- **A roles × permissions matrix.** One Filament table: modules as collapsible groups, a row per subject and per verb, a column per role; a click on a subject's row grants or clears all of its verbs. See [The access control page](#the-access-control-page).
- **An editor for one role or one user.** The same table for a single record, as a schema component you put in a form, a tab or an infolist. For a user it also lists the roles that already grant each permission. See [Editing one record](#editing-one-record).
- **Counters on demand.** `->counters()` adds a summary row at the top of each group — one "granted/total" number per role column — visible even while the group is folded. See [Counters](#counters).
- **Live or deferred saving.** Every click written at once, or staged and saved together — with Discard, a count of what is pending, and a warning before leaving with unsaved changes. See [Live or deferred](#live-or-deferred).
- **Safe concurrent edits.** Each save re-reads the record under a row lock and replays the operator's intent, so two administrators changing the same role do not overwrite each other.
- **Your authorization, asked every time.** Laravel abilities, policies or access-control permission enums decide who may see, create, change and delete; a voter's refusal is shown in the operator's language. See [Authorization](#authorization).
- **Surfaces.** Narrow a screen to what a surface offers (an API key's screen, say); grants held outside it stay listed and revocable. See [Surfaces](#surfaces).
- **Why, not just whether.** Every cell shows what laravel-access-control resolves: in effect, implied, missing a requirement, blocked by a conflict, restricted, or withheld by a condition — the tooltip names the permissions involved. See [Rules and conditions](#rules-and-conditions).
- **`#[RequiresMFA]`.** A permission that needs multi-factor authentication on the account.
- **A form field too.** `PermissionSelector` picks permissions as a flat list saved with the rest of a form — for create forms and anything that must save in one go.
- **Tested.** Covered by a Pest suite on every supported version combination.

## Requirements

| Package                              | Versions  |
|--------------------------------------|-----------|
| PHP                                  | 8.3 – 8.5 |
| Laravel                              | 12, 13    |
| Filament                             | 4, 5      |
| happenv-com/laravel-access-control   | 3.1+      |

## Installation

Install the package via Composer:

```bash
composer require happenv-com/filament-access-control
```

> [!IMPORTANT]
> If you have not set up a custom theme and are using Filament Panels, follow the instructions in the [Filament docs](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme) first.

Add the package's views to your theme's CSS file, so Tailwind generates the classes they use:

```css
@source '../../../../vendor/happenv-com/filament-access-control/resources/**/*.blade.php';
```

### Preparing your models

A record whose permissions the screens edit — a role, or a user holding permissions directly — implements `HasEditablePermissions`: the same two methods laravel-access-control's `HasPermissions` trait asks for, made public. `setPermissions()` must persist.

```php
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Traits\HasPermissions;
use Illuminate\Support\Collection;

class Role extends Model implements AuthControllable, HasEditablePermissions
{
    use HasPermissions;

    protected $casts = ['permissions' => 'array'];

    public function getPermissions(): Collection
    {
        return collect($this->permissions ?? [])->filter(fn ($slug) => is_string($slug))->values();
    }

    public function setPermissions(Collection $permissions): void
    {
        $this->permissions = $permissions->values()->all();
        $this->save();
    }
}
```

A user edited the same way can also expose `getRoles(): iterable` (as `HasRoles` does) — the editor then shows which of the user's roles already grant each permission.

### Registering the plugin

```php
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(
            FilamentAccessControlPlugin::make()
                ->roleModel(Role::class)
                ->superAdminRole('administrator') // the `code` of the role that holds everything
                ->roleAbilities(
                    viewAny: RolePermission::View,
                    create: RolePermission::Create,
                    update: RolePermission::Update,
                ),
        );
}
```

## Usage

### The access control page

With a role model, the plugin registers an **Access control** page: the matrix and an **Add role** action. Roles are deleted where your application manages them — its role resource, for instance. Configure it through the plugin:

```php
FilamentAccessControlPlugin::make()
    ->roleModel(Role::class)
    ->roleTitleAttribute('name')                          // or fn (Role $role): string
    ->modifyRolesQueryUsing(fn (Builder $query) => $query->orderBy('name'))
    ->superAdminRole(fn (Role $role): bool => $role->is_admin)
    ->modifyCreateRoleActionUsing(fn (CreateAction $action) => $action->schema([
        TextInput::make('name')->required(),
        TextInput::make('code')->required()->unique(),
    ]))
    ->navigationGroup('Settings')
    ->navigationSort(10)
    ->slug('permissions')
    ->cluster(SettingsCluster::class);
```

The super-admin role is drawn fully granted and read-only. Pass `->accessControlPage(false)` to register no page, or `->accessControlPage(MyPage::class)` with a class extending `Pages\AccessControl` to replace it.

To put the matrix somewhere else — a page of your own, a tab of a resource — use the schema component:

```php
use Happenv\FilamentAccessControl\Schemas\Components\PermissionMatrix;

PermissionMatrix::make()->deferred();
```

or the Livewire component directly: `@livewire(\Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix::class, ['deferred' => true])`.

### Editing one record

`PermissionEditor` edits the permissions of the schema's record. Put it wherever the schema allows:

```php
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;

public static function configure(Schema $schema): Schema
{
    return $schema->components([
        Tabs::make()->tabs([
            Tab::make('Role')->schema([
                TextInput::make('name')->required(),
            ]),
            Tab::make('Permissions')->schema([
                PermissionEditor::make(),
            ]),
        ]),
    ]);
}
```

The editor saves on its own, independently of the form around it — the form's **Save changes** never touches the permissions. It is hidden while the record does not exist yet (a create page), and read-only in a disabled schema (a view page) or when `->disabled()`.

For a user, the **From roles** column lists the roles that already grant each permission, and a super-admin role is called out above the table. Hide the column with `->showInheritedPermissions(false)`.

Edit a record other than the schema's own through the component's data: `PermissionEditor::make()->data(fn (User $record) => ['record' => $record->apiKey])`.

### Live or deferred

By default every click is written at once. Deferred screens stage the clicks instead — changed cells turn amber — and write them together with **Save permissions**, or throw them away with **Discard**:

```php
FilamentAccessControlPlugin::make()->deferred();   // the default for every screen of the plugin

PermissionEditor::make()->deferred();               // or per component
PermissionEditor::make()->deferred(false);
```

Leaving a page with staged changes asks for confirmation first.

### Counters

Each group can show what every role holds of it — a first row of the group, one "granted/total" number per role column (`3/7`), so a folded group still tells whether it is worth opening; a subject's tooltip then counts its verbs too. Off by default:

```php
FilamentAccessControlPlugin::make()->counters();   // every screen of the plugin

PermissionEditor::make()->counters();              // or per component
PermissionMatrix::make()->counters(false);
```

### Authorization

Every change asks the gate, as the panel's user, with the record being changed:

| Screen                      | Asks                                                    | Default     |
|-----------------------------|---------------------------------------------------------|-------------|
| Access control page         | `roleAbilities(viewAny:)` with the role model class     | `viewAny`   |
| Changing a role             | `roleAbilities(update:)` with the role                  | `update`    |
| Add role                    | `roleAbilities(create:)` with the role model class      | `create`    |
| `PermissionEditor` (a user) | `->ability(...)` with the record                        | `update`    |

An ability can be a Laravel ability name (a policy method as often as not), a laravel-access-control permission enum — asked with the record only, as voters expect — or a closure receiving `record`, `model` and `user`. `null` switches the check off. When a voter refuses, its own message reaches the operator; the library's generic `Unauthorized for <slug>` is translated into the permission's name.

### Surfaces

laravel-access-control lets a permission declare the surfaces it is available on with `#[AvailableFor]`. Narrow a screen to one:

```php
PermissionEditor::make()->surface(PermissionSurface::Api);
```

Only what the surface offers can be granted there; what the record already holds outside of it is listed in a group of its own — revocable, never grantable again. A surface enum that implements `OffersEveryPermission` and returns `true` offers the whole catalogue.

### Rules and conditions

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

The user editor counts a super-admin role as holding every permission with its conditions still applied — an unmet `#[RequiresMFA]` still shows. But an application that implements its super-admin through `Gate::before()` skips conditions at the gate along with everything else, so there the column overstates what is actually enforced.

**Dependencies.** A column next to the permission's name lists every rule from that permission's side — *Requires* / *Required by*, *Implied by* / *Implies*, *Blocked by* / *Blocks* — and every condition. The rule's `reason` is its tooltip. Searching also finds the permissions a rule ties to what you typed.

**Conditions — `#[RequiresMFA]`.** Put it on a permission enum or case to withhold the permission from any account without multi-factor authentication enabled on the panel:

```php
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;

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

For the *In effect* column to tell implied permissions from stored ones, roles using `HasPermissions` should implement laravel-access-control's `HoldsGrants`.

### The form field

`PermissionSelector` is a form field holding the slugs as a flat list, saved with the form like any other field — for create forms, or anything that must save in one go:

```php
use Happenv\FilamentAccessControl\Forms\Components\PermissionSelector;

PermissionSelector::make('permissions')->surface(PermissionSurface::Api);
```

It validates what arrives, keeps grants the deployment cannot draw (a module left out of the build), and never lets a slug outside the surface in.

### Naming verbs

A permission row shows its verb — `View`, `Update` — translated from `filament-access-control::permissions.actions.<case_name_in_snake_case>`, or the case name when there is no translation. Name your own verbs with a resolver, or by publishing the translations:

```php
use Happenv\FilamentAccessControl\Support\PermissionTree;

PermissionTree::resolveActionLabelsUsing(
    fn (PermissionDto $permission): ?string => __("app.permission-verbs.{$permission->enum->name}"),
);
```

### Reacting to changes

Every write dispatches `Happenv\FilamentAccessControl\Events\PermissionsUpdated` with the record and what was actually granted and revoked — for an audit log, a cache to clear.

## Translations

The package ships in every locale Filament ships:

`am` `ar` `az` `bg` `bn` `bs` `ca` `ckb` `cs` `da` `de` `el` `en` `es` `et` `eu` `fa` `fi` `fil` `fr` `he` `hi` `hr` `hu` `hy` `id` `it` `ja` `ka` `km` `ko` `ku` `lt` `lus` `lv` `mk` `mn` `ms` `my` `nb` `ne` `nl` `pl` `pt` `pt_BR` `ro` `ru` `sk` `sl` `sq` `sr_Cyrl` `sr_Latn` `sv` `sw` `tg` `th` `tr` `uk` `ur` `uz` `vi` `zh_CN` `zh_HK` `zh_TW`

The test suite keeps it that way: a locale Filament adds and this package lacks fails it, and so does a key missing from any locale.

Publish them to change the wording:

```bash
php artisan vendor:publish --tag="filament-access-control-translations"
```

## Development

```bash
composer test          # unit and feature tests
composer phpstan       # static analysis
composer cs            # fix code style: composer normalize, Rector, Pint
composer ci            # everything CI checks, locally
```

## Upgrading

Breaking changes and how to migrate are described in [UPGRADING](UPGRADING.md) for every major version.

## Changelog

See [CHANGELOG](CHANGELOG.md) and [GitHub releases](https://github.com/happenv-com/filament-access-control/releases) for what has changed recently.

## Contributing

See [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Happenv sp. z o.o.](https://happenv.com)
- [webard](https://github.com/webard)
- [All contributors](../../contributors)

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.

---

<p align="center">
    <a href="https://happenv.com">
        <img src="art/happenv-logo.png" alt="Happenv" width="400">
    </a>
</p>
