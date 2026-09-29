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
- **Counters on demand.** `->counters()` puts what each role holds of a group into the group's header, visible even while it is folded. See [Counters](#counters).
- **Live or deferred saving.** Every click written at once, or staged and saved together — with Discard, a count of what is pending, and a warning before leaving with unsaved changes. See [Live or deferred](#live-or-deferred).
- **Safe concurrent edits.** Each save re-reads the record under a row lock and replays the operator's intent, so two administrators changing the same role do not overwrite each other.
- **Your authorization, asked every time.** Laravel abilities, policies or access-control permission enums decide who may see, create, change and delete; a voter's refusal is shown in the operator's language. See [Authorization](#authorization).
- **Surfaces.** Narrow a screen to what a surface offers (an API key's screen, say); grants held outside it stay listed and revocable. See [Surfaces](#surfaces).
- **A form field too.** `PermissionSelector` picks permissions as a flat list saved with the rest of a form — for create forms and anything that must save in one go.
- **Tested.** Covered by a Pest suite on every supported version combination.

## Requirements

| Package                              | Versions  |
|--------------------------------------|-----------|
| PHP                                  | 8.3 – 8.5 |
| Laravel                              | 12, 13    |
| Filament                             | 4, 5      |
| happenv-com/laravel-access-control   | 2.3+      |

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
                    delete: RolePermission::Delete,
                ),
        );
}
```

## Usage

### The access control page

With a role model, the plugin registers an **Access control** page: the matrix, an **Add role** action and a **Delete role** action. Configure it through the plugin:

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

The super-admin role is drawn fully granted and read-only, and is never offered for deletion. Pass `->accessControlPage(false)` to register no page, or `->accessControlPage(MyPage::class)` with a class extending `Pages\AccessControl` to replace it.

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

Each group's header can count what every role holds of it — `Editor: 3 of 7` — as Filament badges, so a folded group still tells whether it is worth opening; a subject's tooltip then counts its verbs too. Off by default:

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
| Delete role                 | `roleAbilities(delete:)` with the role                  | `delete`    |
| `PermissionEditor` (a user) | `->ability(...)` with the record                        | `update`    |

An ability can be a Laravel ability name (a policy method as often as not), a laravel-access-control permission enum — asked with the record only, as voters expect — or a closure receiving `record`, `model` and `user`. `null` switches the check off. When a voter refuses, its own message reaches the operator; the library's generic `Unauthorized for <slug>` is translated into the permission's name.

### Surfaces

laravel-access-control lets a permission declare the surfaces it is available on with `#[AvailableFor]`. Narrow a screen to one:

```php
PermissionEditor::make()->surface(PermissionSurface::Api);
```

Only what the surface offers can be granted there; what the record already holds outside of it is listed in a group of its own — revocable, never grantable again. A surface enum that implements `OffersEveryPermission` and returns `true` offers the whole catalogue.

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
