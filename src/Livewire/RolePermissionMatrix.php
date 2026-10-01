<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\CheckboxList;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Livewire\Concerns\EditsPermissions;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Which role may do what, as one grid.
 *
 * A Filament table: the permissions down the side, grouped by module and by subject (a permission
 * enum — users, warehouses, orders), one column per role, and a clickable icon in every cell. A
 * subject's row grants or clears its whole verb set at once.
 *
 * @property-read Collection<array-key, Model&HasEditablePermissions> $holders
 * @property-read Collection<string, PermissionGroupDto> $groups
 * @property-read array<array-key, array<string, bool>> $grants
 * @property-read array<string, bool> $offeredSlugs
 */
class RolePermissionMatrix extends Component implements HasActions, HasSchemas, HasTable
{
    use EditsPermissions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public const string ROLES_CHANGED = 'filament-access-control::roles-changed';

    /**
     * `surface` is taken by the public property of that name, which Livewire fills before this
     * runs: an interface-typed parameter here would be resolved from the container on Laravel 12
     * whenever the caller leaves it out.
     */
    /** The table filter holding the roles the operator chose to see. */
    public const string ROLE_PICKER = 'roles';

    /**
     * How many role columns show until the operator picks others; `null` for all.
     */
    #[Locked]
    public ?int $rolesShownByDefault = null;

    /**
     * Whether the role picker applies the operator's choice on its Apply button (`true`) or with
     * every tick.
     */
    #[Locked]
    public bool $rolePickerDeferred = true;

    /**
     * The roles the picker has offered so far — to tell a role added since from one the operator
     * left unticked.
     *
     * @var list<string>
     */
    #[Locked]
    public array $offeredRoles = [];

    public function mount(?bool $deferred = null, ?bool $counters = null, ?int $rolesShownByDefault = null, ?bool $rolePickerDeferred = null): void
    {
        $this->deferred = $deferred ?? $this->plugin()->isDeferred();
        $this->counters = $counters ?? $this->plugin()->hasCounters();
        $this->rolesShownByDefault = $rolesShownByDefault ?? $this->plugin()->getRolesShownByDefault();
        $this->rolePickerDeferred = $rolePickerDeferred ?? $this->plugin()->isRolePickerDeferred();
        $this->offeredRoles = array_map(strval(...), array_keys($this->roleOptions()));
    }

    #[On(self::ROLES_CHANGED)]
    public function refreshRoles(): void
    {
        $picked = [
            'tableFilters' => $this->tableFilters[self::ROLE_PICKER]['shown'] ?? null,
            'tableDeferredFilters' => $this->tableDeferredFilters[self::ROLE_PICKER]['shown'] ?? null,
        ];

        $this->refreshHolders();

        // The columns are the roles: a role added or removed changes the table itself.
        $this->resetTable();

        // Resetting the table resets its filters too — the operator's pick survives it, and a role
        // added since is shown: they just made it, and a column that never appears would read as
        // a failed save.
        $offered = array_map(strval(...), array_keys($this->roleOptions()));
        $added = array_values(array_diff($offered, $this->offeredRoles));
        $this->offeredRoles = $offered;

        foreach ($picked as $state => $shown) {
            if (is_array($shown)) {
                $this->{$state}[self::ROLE_PICKER]['shown'] = [...$shown, ...$added];
            }
        }

        // A staged change to a role that no longer exists would be refused at Save with a message
        // about a role the operator cannot see any more; it goes with the role instead.
        $this->changes = array_intersect_key($this->changes, $this->holders->all());
    }

    public function isHolderLocked(Model $holder): bool
    {
        return $this->plugin()->isSuperAdminRole($holder);
    }

    public function getHolderTitle(Model $holder): string
    {
        return $this->plugin()->getRoleTitle($holder);
    }

    public function table(Table $table): Table
    {
        return $this->configurePermissionTable($table)
            // No roles, no table: a list of permissions nobody can be given reads as a broken screen.
            ->records(fn (?string $search): array => $this->holders->isEmpty() ? [] : $this->permissionRecords($search))
            ->columns([
                $this->permissionColumn(),
                $this->dependenciesColumn(),
                ...$this->holders
                    ->map(fn (Model $role, int | string $roleKey): TextColumn => $this->holderColumn((string) $roleKey, $this->getHolderTitle($role))
                        ->headerTooltip(match (true) {
                            $this->isHolderLocked($role) => __('filament-access-control::editor.super_admin_hint'),
                            $this->isOwnHolderGuarded($role) => __('filament-access-control::editor.own_role_hint'),
                            default => null,
                        })
                        ->hidden(fn (): bool => ! $this->isRoleShown((string) $roleKey)))
                    ->values()
                    ->all(),
            ])
            // The role picker: a filter only by Filament's name for it — it narrows the columns, never
            // the rows. Filament's filters bring what the picker needs: the trigger next to the search,
            // Apply or live, the choice kept in the session, and an indicator while roles are hidden.
            ->filters([
                Filter::make(self::ROLE_PICKER)
                    ->schema([
                        CheckboxList::make('shown')
                            ->hiddenLabel()
                            ->options(fn (): array => $this->roleOptions())
                            ->default(fn (): array => $this->defaultShownRoles())
                            ->bulkToggleable()
                            ->searchable()
                            ->columns(fn (): int => min(3, max(1, intdiv(count($this->roleOptions()) + 9, 10)))),
                    ])
                    ->indicateUsing(fn (array $data): array => $this->rolePickerIndicators($data)),
            ], FiltersLayout::Modal)
            ->deferFilters($this->rolePickerDeferred)
            ->persistFiltersInSession()
            ->filtersTriggerAction(fn (Action $action): Action => $action
                ->icon(Heroicon::OutlinedListBullet)
                ->label(__('filament-access-control::editor.role_picker.label'))
                ->modalHeading(__('filament-access-control::editor.role_picker.heading'))
                ->visible(fn (): bool => $this->holders->isNotEmpty()))
            ->filtersFormWidth(fn (): Width => count($this->roleOptions()) > 10 ? Width::FourExtraLarge : Width::Medium)
            // Hooks the stylesheet that keeps the permission column and the role header in view
            // while a wide or long matrix scrolls.
            ->extraAttributes(['class' => 'fac-matrix'])
            ->emptyStateHeading(fn (): string => match (true) {
                $this->holders->isEmpty() => __('filament-access-control::editor.no_roles'),
                filled($this->getTableSearch()) => __('filament-access-control::editor.search_empty', ['search' => $this->getTableSearch()]),
                default => __('filament-access-control::editor.offering_empty'),
            });
    }

    /**
     * Whether the operator chose to see the role's column. Until they choose, the plugin's (or the
     * screen's) default decides.
     */
    public function isRoleShown(string $roleKey): bool
    {
        $shown = $this->getTableFilterState(self::ROLE_PICKER)['shown'] ?? null;

        return in_array($roleKey, is_array($shown) ? array_map(strval(...), $shown) : $this->defaultShownRoles(), true);
    }

    public function hidesAnyRole(): bool
    {
        foreach (array_keys($this->roleOptions()) as $key) {
            if (! $this->isRoleShown((string) $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, string>
     */
    public function roleOptions(): array
    {
        return $this->holders
            ->map(fn (Model $role): string => $this->getHolderTitle($role))
            ->mapWithKeys(fn (string $title, int | string $key): array => [(string) $key => $title])
            ->all();
    }

    /**
     * The roles shown before the operator picks: the first ones in the roles query's order.
     *
     * @return list<string>
     */
    protected function defaultShownRoles(): array
    {
        $keys = array_map(strval(...), array_keys($this->roleOptions()));

        return $this->rolesShownByDefault === null ? $keys : array_slice($keys, 0, $this->rolesShownByDefault);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return list<Indicator>
     */
    protected function rolePickerIndicators(array $data): array
    {
        if (! $this->hidesAnyRole()) {
            return [];
        }

        $total = count($this->roleOptions());
        $shown = count(array_filter(array_keys($this->roleOptions()), fn (int | string $key): bool => $this->isRoleShown((string) $key)));

        return [
            Indicator::make(__('filament-access-control::editor.role_picker.indicator', ['shown' => $shown, 'total' => $total]))
                ->removable(false),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function resolveTableRecord(?string $key): ?array
    {
        return $this->holders->isEmpty() ? null : $this->resolvePermissionRecord($key);
    }

    public function render(): View
    {
        return view('filament-access-control::livewire.role-permission-matrix');
    }

    /**
     * @return Collection<array-key, Model&HasEditablePermissions>
     */
    protected function getHolders(): Collection
    {
        /** @var Collection<array-key, Model&HasEditablePermissions> $roles */
        $roles = $this->plugin()->getRolesQuery()->get()
            ->keyBy(fn (Model $role): string => $this->holderKey($role));

        return $roles;
    }

    protected function getUpdateAbility(): string | BackedEnum | Closure | null
    {
        return $this->plugin()->getRoleAbility('update');
    }

    protected function plugin(): FilamentAccessControlPlugin
    {
        return FilamentAccessControlPlugin::current();
    }
}
