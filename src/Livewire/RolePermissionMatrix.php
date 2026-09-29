<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Livewire\Concerns\EditsPermissions;
use Happenv\FilamentAccessControl\Support\Authorization;
use Happenv\FilamentAccessControl\Support\RefusalLead;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Which role may do what, as one grid.
 *
 * Rows are the SUBJECT (a permission enum — users, warehouses, orders), columns are the roles, and
 * a cell holds one toggle per verb. Two levels of nesting (group → subject) keep a catalogue of
 * hundreds of permissions to a few dozen rows, and only the groups the operator opens are drawn.
 *
 * @property-read Collection<array-key, Model&HasEditablePermissions> $holders
 * @property-read Collection<string, PermissionGroupDto> $groups
 * @property-read array<array-key, array<string, bool>> $grants
 * @property-read array<string, bool> $offeredSlugs
 */
class RolePermissionMatrix extends Component implements HasActions, HasSchemas
{
    use EditsPermissions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public const string ROLES_CHANGED = 'filament-access-control::roles-changed';

    public function mount(?bool $deferred = null, ?PermissionSurfaceDefinition $surface = null): void
    {
        $this->deferred = $deferred ?? $this->plugin()->isDeferred();
        $this->surface = $surface;
    }

    #[On(self::ROLES_CHANGED)]
    public function refreshRoles(): void
    {
        $this->refreshHolders();

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

    public function deleteRoleAction(): Action
    {
        $action = DeleteAction::make('deleteRole')
            ->record(fn (array $arguments): ?Model => $this->holders->get((string) ($arguments['role'] ?? '')))
            ->modalHeading(fn (?Model $record): ?string => $record instanceof Model
                ? __('filament-access-control::editor.actions.delete_role.heading', ['role' => $this->getHolderTitle($record)])
                : null)
            ->tooltip(__('filament-access-control::editor.actions.delete_role.label'))
            ->iconButton()
            ->size(Size::Small)
            // The voters own the rules — protected roles, roles still in use — and asking the gate
            // WITH the role keeps this screen from growing a second copy of them that can drift.
            // Disabled with the voter's reason rather than hidden: a missing button explains nothing.
            ->authorize(fn (?Model $record): mixed => $record instanceof Model
                ? Authorization::inspect($this->plugin()->getRoleAbility('delete'), $record)
                : false)
            ->authorizationTooltip()
            ->authorizationMessage(fn (?Model $record): string => ($record instanceof Model
                ? resolve(RefusalLead::class)->fromMessage(Authorization::inspect($this->plugin()->getRoleAbility('delete'), $record)->message())
                : null) ?? __('filament-access-control::editor.notifications.unauthorized'))
            ->after(function (): void {
                $this->dispatch(self::ROLES_CHANGED);
                $this->refreshRoles();
            });

        return $this->plugin()->configureDeleteRoleAction($action);
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
