<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire;

use BackedEnum;
use Closure;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
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
    /**
     * How many role columns show until the operator picks others in the column menu; `null` for all.
     */
    #[Locked]
    public ?int $rolesShownByDefault = null;

    public function mount(?bool $deferred = null, ?bool $counters = null, ?int $rolesShownByDefault = null): void
    {
        $this->deferred = $deferred ?? $this->plugin()->isDeferred();
        $this->counters = $counters ?? $this->plugin()->hasCounters();
        $this->rolesShownByDefault = $rolesShownByDefault ?? $this->plugin()->getRolesShownByDefault();
    }

    #[On(self::ROLES_CHANGED)]
    public function refreshRoles(): void
    {
        $this->refreshHolders();

        // The columns are the roles: a role added or removed changes the table itself.
        $this->resetTable();

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
                    ->values()
                    ->map(fn (Model $role, int $position): TextColumn => $this->holderColumn($this->holderKey($role), $this->getHolderTitle($role))
                        ->headerTooltip($this->isHolderLocked($role) ? __('filament-access-control::editor.super_admin_hint') : null)
                        // Every role can be hidden from the column menu — with fifty of them, the
                        // operator keeps the ones they are working on in view.
                        ->toggleable(isToggledHiddenByDefault: $this->rolesShownByDefault !== null && $position >= $this->rolesShownByDefault))
                    ->all(),
            ])
            ->columnManagerColumns(fn (): int => match (true) {
                $this->holders->count() > 24 => 3,
                $this->holders->count() > 12 => 2,
                default => 1,
            })
            // Hooks the stylesheet that keeps the permission column and the role header in view
            // while a wide or long matrix scrolls.
            ->extraAttributes(['class' => 'fac-matrix'])
            ->emptyStateHeading(fn (): string => match (true) {
                $this->holders->isEmpty() => __('filament-access-control::editor.no_roles'),
                filled($this->getTableSearch()) => __('filament-access-control::editor.search_empty', ['search' => $this->getTableSearch()]),
                default => __('filament-access-control::editor.offering_empty'),
            });
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
