---
name: filament-access-control-development
description: >-
  Put happenv-com/laravel-access-control permissions on Filament screens with
  happenv-com/filament-access-control. Use when registering FilamentAccessControlPlugin on a panel
  (roleModel, superAdminRole, roleAbilities, deferred, counters, rolesShownByDefault, grantableBy,
  preventEscalation, preventSelfEditing, holderCellNotes, actions, accessControlPage,
  matrixComponent), making a Role or User model editable (HasEditablePermissions), adding
  PermissionEditor / PermissionMatrix / PermissionSelector to a resource, page or form, using
  #[RequiresMFA], narrowing a screen to a surface (OffersEveryPermission), refusing writes
  (PermissionWriter::ensureMayWrite, PermissionWriteRefused), auditing changes (PermissionsUpdated),
  naming verbs (PermissionTree::resolveActionLabelsUsing), or testing these screens.
---

# Filament Access Control

Filament screens for laravel-access-control: an **Access control** page with every role against every permission, and an editor for one role or one user that you place in any schema. The screens only display and edit **stored** grants; every answer about what is in effect (implied, missing requirement, conflict, restricted, unmet condition) comes from laravel-access-control. They never decide authorization themselves, so the rules, voters and models must be set up there first.

Permission enums, groups, voters, rules, conditions, the `HasRoles` / `HasPermissions` / `HoldsGrants` model setup and registering enums in `register()` are covered by the `laravel-access-control-development` skill. Read it first; this skill covers only the Filament layer.

## When to use this skill

- Adding the roles x permissions page or a permission tab to a role or user resource.
- Making a model's permissions editable from Filament.
- Deciding who may open the screens and what an operator may grant.
- Hooking audit logs, extra validation or per-cell scope notes into the screens.
- Testing a panel that uses these components.

## Installation

Supports Filament 4 and 5, laravel-access-control 3.1+. The views use Tailwind classes, so add them to the panel's custom theme CSS:

```css
@source '../../../../vendor/happenv-com/filament-access-control/resources/**/*.blade.php';
```

The matrix stylesheet is published by `php artisan filament:assets` (run by `filament:upgrade`). No config file: everything is set on the plugin or per component.

## Models

Every record the screens **write** implements `HasEditablePermissions`: the protected `getPermissions()` / `setPermissions()` of laravel-access-control's `HasPermissions`, made **public**. `setPermissions()` must save; the writer saves nothing itself.

```php
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Contracts\HoldsGrants;
use Happenv\LaravelAccessControl\Traits\HasPermissions;

class Role extends Model implements AuthControllable, HasEditablePermissions, HoldsGrants
{
    use HasPermissions;

    protected function casts(): array { return ['permissions' => 'array']; }

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

- Give roles `HoldsGrants`, or the cells cannot tell an implied permission from a stored one.
- A user edited directly uses `HasRolesAndPermissions` + `HasEditablePermissions`; its `getRoles()` feeds the editor's **From roles** column.
- A user with roles only (`HasRoles`, no `HasEditablePermissions`) can be shown **read-only**: `PermissionEditor::make()->disabled()->showDirectGrants(false)`. Without `disabled()` the editor stays hidden for such a record.
- Every registered permission enum needs `#[PermissionGroup]`, or the screens throw `PermissionGroupRequiredException` while rendering.

## Registering the plugin

```php
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;

$panel->plugin(
    FilamentAccessControlPlugin::make()
        ->roleModel(Role::class)                       // Model & HasEditablePermissions, else InvalidArgumentException
        ->roleTitleAttribute('name')                   // or fn (Role $role): string
        ->superAdminRole('administrator')              // matches the `code` attribute; ('admin', 'slug') or fn (Role $role): bool
        ->roleAbilities(viewAny: RolePermission::View, create: RolePermission::Create, update: RolePermission::Update)
        ->modifyRolesQueryUsing(fn (Builder $query) => $query->orderBy('name'))
        ->modifyCreateRoleActionUsing(fn (CreateAction $action) => $action->schema([
            TextInput::make('name')->required(),
            TextInput::make('code')->required()->unique(),
        ]))
        ->deferred()                                   // stage clicks until "Save permissions"
        ->counters()                                   // "granted/total" per role on each group row
        ->rolesShownByDefault(8)                       // role picker for many roles
        ->navigationGroup('Settings')->navigationSort(10)->slug('permissions')
);
```

- The **Access control** page is registered only when `roleModel()` is set. `->accessControlPage(false)` registers none; `->accessControlPage(MyPage::class)` replaces it (must extend `Pages\AccessControl`). `->cluster(SettingsCluster::class)` puts it in a cluster.
- The page has no delete action; roles are deleted in your own `RoleResource`.
- **Default abilities are `viewAny`, `create` and `update`.** `viewAny` (open the page) and `create` (Add role) are asked with the role model **class**; `update` (change a role's permissions) with the role **instance**, so `RolePolicy::update(User $user, Role $role)`. Without a `RolePolicy` (or `roleAbilities(...)`), the page is hidden and every write is refused. An ability may be a Gate ability name, a permission enum (asked with the record only, as voters expect), a closure receiving `record`, `model`, `user` and returning `bool` or `Response`, or `null` for no check. A voter's `deny()` message is shown to the operator.
- **`superAdminRole()` is display and guard logic only.** It draws that role fully granted and read-only and lifts the escalation guard for its holders; it does **not** make the role pass the Gate. Implement the super-admin in laravel-access-control (see that skill).

## Placing the editor and the matrix

```php
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionMatrix;

// RoleResource / UserResource form, typically in a tab:
Tab::make('Permissions')->schema([
    PermissionEditor::make()->deferred(),
]),

PermissionEditor::make()->ability('managePermissions');           // user records default to `update`
PermissionEditor::make()->showInheritedPermissions(false);         // hide "From roles"
PermissionEditor::make()->data(fn (User $record) => ['record' => $record->apiKey]);  // edit another record

PermissionMatrix::make()->deferred()->rolesShownByDefault(8);      // the roles grid on any page or tab
```

- `PermissionEditor` saves **on its own**; the surrounding form's Save never touches permissions. It is hidden until the record exists (create pages) and read-only in a disabled schema (view pages).
- Without `->ability()`, a role record is checked with `roleAbilities(update:)` and any other record with `'update'`, always with the record.
- `->ability(fn (...) => ...)` only **picks** the ability (name, enum or `null`). Returning `false` means "default ability", not "deny". Put decisions in a gate or policy and return its name.
- Per-component `deferred()` and `counters()` override the plugin; `null` falls back to it. `surface()` has no plugin default: without it a component offers the whole catalogue.
- Livewire directly: `@livewire(\Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix::class, ['deferred' => true])`, or `RecordPermissions::class` with `['record' => $role]`.
- To change the grid: extend `Livewire\RolePermissionMatrix` and register it with `->matrixComponent(MyMatrix::class)`. To refuse a holder, override `refusalFor(string $holderKey): ?string` (both live clicks and deferred saves ask it), not `mutableHolder()`.

### The form field (create forms)

```php
use Happenv\FilamentAccessControl\Forms\Components\PermissionSelector;

PermissionSelector::make('permissions')->surface(PermissionSurface::Api);
```

A plain field holding the list of slugs, saved with the form into the model attribute of the same name. It validates unknown, not-offered and not-grantable slugs, and keeps held grants it cannot draw. It does **not** go through `PermissionWriter`: no `ensureMayWrite()`, no `PermissionsUpdated` event. Use `PermissionEditor` where those must run.

## Who may change what

Both guards are on by default and enforced server-side on every write.

- **Escalation:** an operator grants and revokes only what they hold **in effect** (`AccessControl::effectivePermissions()`, not the Gate, so a `Gate::before()` admin is narrowed to what they actually hold). Holders of `superAdminRole()` are exempt. The operator must be `AuthControllable`, else they may change nothing. Override with `->grantableBy(fn (User $operator): ?array => ...)` (slugs or enums; `null` = anything) or `->preventEscalation(false)`.
- **Self-editing:** an operator cannot change their own direct grants or a role they hold (found through the operator's `getRoles()`), super-admins included. `->preventSelfEditing(false)` turns it off.
- Guards read `FilamentAccessControlPlugin::current()`. On a panel without the plugin registered they run with defaults (no super-admin, no `grantableBy`).

## Conditions, surfaces, labels

```php
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;

enum OrderPermission: string implements PermissionDefinition
{
    #[RequiresMFA]                       // current (or default) panel's MFA providers
    case Refund = 'order.refund';

    #[RequiresMFA(panel: 'admin')]
    case Export = 'order.export';
}
```

- `#[RequiresMFA]` is a laravel-access-control condition: enforced at the Gate everywhere, not only in Filament. It fails closed (no panel, no MFA provider, or an account the providers cannot ask, such as an API key). A `Gate::before()` skips it.
- Your own conditions implementing `DescribesPermissionCondition` are named in the Dependencies column and the editor's callout.
- `->surface(Surface::Api)` on editor, matrix or selector offers only permissions declared `#[AvailableFor(Surface::Api)]`. The editor and the selector still list the record's grants held outside the surface (revocable, never grantable); the matrix draws only the surface's catalogue, so revoke such grants in an editor or an unfiltered matrix. A surface enum implementing `OffersEveryPermission` returning `true` offers everything (use it for the panel surface).
- Verb labels come from `filament-access-control::permissions.actions.<snake_case_case_name>`, else the case name. Customize with `PermissionTree::resolveActionLabelsUsing(fn (PermissionDto $p): ?string => ...)` in a provider's `boot()`, or publish `--tag=filament-access-control-translations`.
- `->declarationProblems(false)` hides the list of `PermissionGraph` problems shown above the screens; fix the declarations instead.

## Hooks

```php
// Audit / cache busting: dispatched after commit, only with what actually changed.
Event::listen(PermissionsUpdated::class, fn (PermissionsUpdated $e) => audit($e->record, $e->granted, $e->revoked));

// Refuse some writes: runs in the transaction, under a row lock.
class ScopedPermissionWriter extends PermissionWriter
{
    protected function ensureMayWrite(Model $locked, Collection $resulting, Collection $granted, Collection $revoked): void
    {
        if ($granted->contains(OrderPermission::View->value) && ! $locked->channels()->exists()) {
            throw new PermissionWriteRefused(__('app.roles.choose_channels_first'));   // shown as a notification
        }
    }
}
$this->app->bind(PermissionWriter::class, ScopedPermissionWriter::class);
```

Cell notes and their actions (e.g. a data scope per role grant):

```php
FilamentAccessControlPlugin::make()
    ->holderCellNotes(fn (Model $holder, PermissionDto $permission): ?CellNote => $permission->enum === OrderPermission::View
        ? new CellNote(trans_choice('app.scope.channels', $holder->channels->count()), action: 'channelScope')
        : null)
    ->actions(fn (): array => [
        Action::make('channelScope')
            ->fillForm(fn (array $arguments): array => ['channels' => Role::find($arguments['holder'])->channels->modelKeys()])
            ->authorize(fn (array $arguments): bool => Gate::allows('update', Role::find($arguments['holder'])))
            ->schema([CheckboxList::make('channels')->options(Channel::pluck('name', 'id'))])
            ->action(fn (array $arguments, array $data) => Role::find($arguments['holder'])->channels()->sync($data['channels'])),
    ]);
```

- The action gets `holder` (the holder's key) and `permission` (the slug) plus the note's `arguments`. They come from the browser: authorize inside each action.
- The handler must be a **Closure**; `->action('method')` throws `InvalidArgumentException`.
- Pass `actions()` a closure so labels translate per request.

## Testing

Use Livewire tests against the components; the holder key is `(string) $model->getKey()`.

```php
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use function Pest\Livewire\livewire;

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');
    actingAs($admin);   // must hold, in effect, what the test grants, or hold the super-admin role
});

it('grants a permission to a role', function (): void {
    livewire(RecordPermissions::class, ['record' => $role])
        ->call('toggle', (string) $role->getKey(), ProductPermission::View->value);

    expect($role->fresh()->getPermissions())->toContain(ProductPermission::View->value);
});

it('saves staged changes', function (): void {
    livewire(RolePermissionMatrix::class, ['deferred' => true])
        ->call('toggle', (string) $role->getKey(), ProductPermission::View->value)
        ->call('save');
});
```

- A signed-in operator who lacks a permission cannot grant it (escalation guard), and cannot edit their own roles. Give the operator the grants, a super-admin role, or call `FilamentAccessControlPlugin::get()->preventEscalation(false)` in that test.
- Set the current panel, so `FilamentAccessControlPlugin::current()` finds your configuration.
- Assert `Event::fake([PermissionsUpdated::class])` + `Event::assertDispatched(...)` for audit hooks.

## Do not

- Do not edit permissions with a plain `CheckboxList` filled from `hasPermissionTo()`; use these components (they read stored grants).
- Do not rely on `superAdminRole()` to grant access at the Gate.
- Do not forget a role policy (or `roleAbilities()`); the defaults ask `viewAny` / `create` / `update`.
- Do not expect `PermissionSelector` to fire `PermissionsUpdated` or `ensureMayWrite()`.
- Do not give a cell-note action a string handler.
