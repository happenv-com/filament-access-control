<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Livewire\Concerns\EditsPermissions;
use Happenv\FilamentAccessControl\Support\Authorization;
use Happenv\FilamentAccessControl\Support\RefusalLead;
use Happenv\LaravelAccessControl\Diagram\PermissionDiagram;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Happenv\LaravelAccessControl\Facades\AccessControl;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
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
    public function mount(?bool $deferred = null, ?bool $counters = null): void
    {
        $this->deferred = $deferred ?? $this->plugin()->isDeferred();
        $this->counters = $counters ?? $this->plugin()->hasCounters();
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
        return $this->configurePermissionTable($table, toolbarActions: [$this->deleteRoleAction()])
            // No roles, no table: a list of permissions nobody can be given reads as a broken screen.
            ->records(fn (?string $search): array => $this->holders->isEmpty() ? [] : $this->permissionRecords($search))
            ->columns([
                $this->permissionColumn(),
                $this->dependenciesColumn(),
                ...$this->holders
                    ->map(fn (Model $role, int | string $roleKey): IconColumn => $this->holderColumn((string) $roleKey, $this->getHolderTitle($role))
                        ->headerTooltip($this->isHolderLocked($role) ? __('filament-access-control::editor.super_admin_hint') : null))
                    ->values()
                    ->all(),
            ])
            ->emptyStateHeading(fn (): string => match (true) {
                $this->holders->isEmpty() => __('filament-access-control::editor.no_roles'),
                filled($this->getTableSearch()) => __('filament-access-control::editor.search_empty', ['search' => $this->getTableSearch()]),
                default => __('filament-access-control::editor.offering_empty'),
            });
    }

    /**
     * Delete a role — picked in the action's form rather than drawn under every column: a column
     * header is a plain label in Filament's table, and it is kept in the component's state.
     *
     * The voters own the rules — protected roles, roles still in use — and the gate is asked WITH
     * the picked role, so its reason reaches the operator as the field's error instead of this
     * screen growing a second copy of the rules that can drift.
     */
    protected function deleteRoleAction(): Action
    {
        $action = Action::make('deleteRole')
            ->label(__('filament-access-control::editor.actions.delete_role.label'))
            ->icon(Heroicon::OutlinedTrash)
            ->color('danger')
            ->link()
            ->visible(fn (): bool => $this->deletableRoles() !== [])
            ->modalHeading(__('filament-access-control::editor.actions.delete_role.heading'))
            ->modalSubmitActionLabel(__('filament-access-control::editor.actions.delete_role.submit'))
            ->modalWidth(Width::Medium)
            ->schema([
                Select::make('role')
                    ->label(__('filament-access-control::editor.fields.role'))
                    ->options(fn (): array => $this->deletableRoles())
                    ->required()
                    ->rule(fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                        $refusal = $this->refuseDeleting((string) $value);

                        if ($refusal !== null) {
                            $fail($refusal);
                        }
                    }),
            ])
            ->action(function (array $data): void {
                $role = $this->holders->get((string) $data['role']);

                // Asked again rather than trusted: the form validated a moment ago, the gate decides now.
                $refusal = $this->refuseDeleting((string) $data['role']);

                if ($refusal !== null || ! $role instanceof Model) {
                    $this->deny($refusal ?? __('filament-access-control::editor.notifications.no_holder'));

                    return;
                }

                $role->delete();

                Notification::make()
                    ->title(__('filament-access-control::editor.notifications.role_deleted'))
                    ->success()
                    ->send();

                $this->dispatch(self::ROLES_CHANGED);
                $this->refreshRoles();
            });

        return $this->plugin()->configureDeleteRoleAction($action);
    }

    /**
     * The roles offered for deletion: every role but a super-admin one, key => title.
     *
     * @return array<string, string>
     */
    protected function deletableRoles(): array
    {
        return $this->holders
            ->reject(fn (Model $role): bool => $this->isHolderLocked($role))
            ->mapWithKeys(fn (Model $role, int | string $key): array => [(string) $key => $this->getHolderTitle($role)])
            ->all();
    }

    /**
     * Why the role may not be deleted, or `null` when it may.
     */
    protected function refuseDeleting(string $roleKey): ?string
    {
        $role = $this->holders->get($roleKey);

        if (! $role instanceof Model) {
            return __('filament-access-control::editor.notifications.no_holder');
        }

        if ($this->isHolderLocked($role)) {
            return __('filament-access-control::editor.super_admin_hint');
        }

        $verdict = Authorization::inspect($this->plugin()->getRoleAbility('delete'), $role);

        return $verdict->denied()
            ? (resolve(RefusalLead::class)->fromMessage($verdict->message()) ?? __('filament-access-control::editor.notifications.unauthorized'))
            : null;
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

    protected function permissionDiagram(): PermissionDiagram
    {
        return AccessControl::diagram()->catalogue();
    }
}
