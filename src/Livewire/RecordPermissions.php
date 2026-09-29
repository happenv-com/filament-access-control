<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire;

use BackedEnum;
use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Livewire\Concerns\EditsPermissions;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

/**
 * The permissions of ONE record — a role, or a user holding permissions directly — as a list.
 *
 * The same catalogue and the same save modes as {@see RolePermissionMatrix}, drawn for a single
 * holder, so it fits wherever the application puts it: a section of the edit form, a tab, a page of
 * its own. For a user it also shows what the user's roles already grant, since a direct grant of
 * something a role hands out anyway changes nothing and the operator should be able to see that.
 *
 * @property-read Collection<array-key, Model&HasEditablePermissions> $holders
 * @property-read Collection<string, PermissionGroupDto> $groups
 * @property-read array<array-key, array<string, bool>> $grants
 * @property-read array<string, bool> $offeredSlugs
 * @property-read array<string, list<string>> $inherited
 * @property-read list<string> $superAdminRoles
 * @property-read Collection<string, PermissionDto> $heldOutsideOffering
 */
class RecordPermissions extends Component implements HasActions, HasSchemas, HasTable
{
    use EditsPermissions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    /** @var Model&HasEditablePermissions */
    #[Locked]
    public Model $record;

    /**
     * The ability the application named — `null` among them, for no check at all. Until it names
     * one, a role is asked the plugin's `update` role ability and anything else Laravel's `update`.
     *
     * Named apart from the `ability` mount parameter on purpose: Livewire assigns a parameter to
     * the public property of the same name before `mount()` runs, and "not named" is spelled
     * `false`, which this property does not hold.
     */
    #[Locked]
    public string | BackedEnum | null $customAbility = null;

    #[Locked]
    public bool $hasCustomAbility = false;

    #[Locked]
    public bool $readOnly = false;

    #[Locked]
    public bool $showInherited = true;

    /**
     * `surface` is taken by the public property of that name, which Livewire fills before this runs:
     * an interface-typed parameter here would be resolved from the container on Laravel 12 whenever
     * the caller leaves it out.
     *
     * @param  string|BackedEnum|false|null  $ability  `false` for the default, `null` for no check
     */
    public function mount(
        Model $record,
        ?bool $deferred = null,
        ?bool $counters = null,
        string | BackedEnum | false | null $ability = false,
        bool $readOnly = false,
        bool $showInherited = true,
    ): void {
        if (! $record instanceof HasEditablePermissions) {
            throw new InvalidArgumentException(sprintf(
                'The record [%s] must implement [%s] for its permissions to be edited.',
                $record::class,
                HasEditablePermissions::class,
            ));
        }

        $this->record = $record;
        $this->deferred = $deferred ?? $this->plugin()->isDeferred();
        $this->counters = $counters ?? $this->plugin()->hasCounters();
        $this->hasCustomAbility = $ability !== false;
        $this->customAbility = $ability === false ? null : $ability;
        $this->readOnly = $readOnly;
        $this->showInherited = $showInherited;
    }

    public function recordKey(): string
    {
        return $this->holderKey($this->record);
    }

    public function isHolderLocked(Model $holder): bool
    {
        return $this->plugin()->isSuperAdminRole($holder);
    }

    public function getHolderTitle(Model $holder): string
    {
        return $this->isRole() ? $this->plugin()->getRoleTitle($holder) : (string) $holder->getKey();
    }

    public function isEditable(): bool
    {
        return ! $this->readOnly;
    }

    public function isRole(): bool
    {
        $model = $this->plugin()->getRoleModel();

        return $model !== null && $this->record instanceof $model;
    }

    /**
     * What the record's ROLES grant, slug => the titles of the roles granting it.
     *
     * @return array<string, list<string>>
     */
    #[Computed]
    public function inherited(): array
    {
        $inherited = [];

        foreach ($this->roles() as $role) {
            if (! $role instanceof HasEditablePermissions || $this->plugin()->isSuperAdminRole($role)) {
                continue;
            }

            $title = $this->plugin()->getRoleTitle($role);

            foreach ($role->getPermissions() as $slug) {
                $inherited[$slug][] = $title;
            }
        }

        return $inherited;
    }

    /**
     * The super-admin roles the record holds — each one grants everything.
     *
     * @return list<string>
     */
    #[Computed]
    public function superAdminRoles(): array
    {
        return array_values(array_map(
            fn (Model $role): string => $this->plugin()->getRoleTitle($role),
            array_filter($this->roles(), fn (Model $role): bool => $this->plugin()->isSuperAdminRole($role)),
        ));
    }

    /**
     * @return list<string>
     */
    public function inheritedFrom(string $slug): array
    {
        return $this->superAdminRoles !== [] ? $this->superAdminRoles : ($this->inherited[$slug] ?? []);
    }

    /**
     * What the record HOLDS that this screen does not offer — and that this deployment CAN draw.
     *
     * Only when the screen is narrowed to a surface: a grant issued before the narrowing is inert
     * today and alive the day the surface starts consulting it, so it is drawn — revocable, never
     * grantable afresh — instead of being silently kept or silently dropped.
     *
     * @return Collection<string, PermissionDto>
     */
    #[Computed]
    public function heldOutsideOffering(): Collection
    {
        if (! $this->surface instanceof PermissionSurfaceDefinition) {
            return new Collection;
        }

        return $this->tree()->permissions()
            ->filter(fn (PermissionDto $permission): bool => $this->isStored($this->recordKey(), $permission->slug)
                && ! $this->isOffered($permission->slug));
    }

    public function table(Table $table): Table
    {
        return $this->configurePermissionTable($table)
            ->records(fn (?string $search): array => [
                ...$this->permissionRecords($search),
                ...$this->heldOutsideOfferingRecords(),
            ])
            ->columns([
                $this->permissionColumn(),
                $this->holderColumn($this->recordKey(), __('filament-access-control::editor.columns.granted')),
                TextColumn::make('inherited')
                    ->label(__('filament-access-control::editor.columns.inherited'))
                    ->state(fn (array $record): array => $record['type'] === 'permission' ? $this->inheritedFrom((string) $record['slug']) : [])
                    ->badge()
                    ->color('info')
                    ->icon(Heroicon::UserGroup)
                    ->tooltip(__('filament-access-control::editor.inherited_hint'))
                    ->visible(fn (): bool => $this->roles() !== []),
            ]);
    }

    /**
     * The grants {@see self::heldOutsideOffering()} lists, as a group of their own at the end of the
     * table — outside the search's reach on purpose: a row a search can hide is a row an operator
     * can fail to find, and for exactly these rows that would make the grant unrevocable.
     *
     * @return array<string, array<string, mixed>>
     */
    public function heldOutsideOfferingRecords(): array
    {
        return $this->heldOutsideOffering
            ->mapWithKeys(fn (PermissionDto $permission): array => ['held:' . $permission->slug => [
                'type' => 'permission',
                'group' => 'held-outside-offering',
                'group_name' => __('filament-access-control::editor.held_outside_offering.heading'),
                'group_description' => __('filament-access-control::editor.held_outside_offering.description'),
                'subject' => $permission->enum::class,
                'label' => $permission->name,
                'description' => $permission->slug,
                'slug' => $permission->slug,
                'restricted' => $this->isRestricted($permission),
            ]])
            ->all();
    }

    public function render(): View
    {
        return view('filament-access-control::livewire.record-permissions');
    }

    /**
     * @return Collection<array-key, Model&HasEditablePermissions>
     */
    protected function getHolders(): Collection
    {
        return new Collection([$this->holderKey($this->record) => $this->record]);
    }

    protected function getUpdateAbility(): string | BackedEnum | Closure | null
    {
        if ($this->hasCustomAbility) {
            return $this->customAbility;
        }

        return $this->isRole() ? $this->plugin()->getRoleAbility('update') : 'update';
    }

    protected function written(Model $holder): void
    {
        $this->record->refresh();

        unset($this->inherited, $this->superAdminRoles, $this->heldOutsideOffering);
    }

    /**
     * @return list<Model>
     */
    public function roles(): array
    {
        if (! $this->showInherited || $this->isRole() || ! method_exists($this->record, 'getRoles')) {
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

    protected function plugin(): FilamentAccessControlPlugin
    {
        return FilamentAccessControlPlugin::current();
    }
}
