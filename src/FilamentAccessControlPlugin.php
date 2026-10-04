<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Contracts\Plugin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Support\Concerns\EvaluatesClosures;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Pages\AccessControl;
use Happenv\FilamentAccessControl\Support\CellNote;
use Happenv\FilamentAccessControl\Support\GrantGuard;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use Throwable;
use UnitEnum;

class FilamentAccessControlPlugin implements Plugin
{
    use EvaluatesClosures;

    public const string ID = 'filament-access-control';

    /** @var class-string<Model&HasEditablePermissions>|null */
    protected ?string $roleModel = null;

    protected string | Closure $roleTitleAttribute = 'name';

    protected ?Closure $modifyRolesQueryUsing = null;

    protected ?Closure $isSuperAdminRoleUsing = null;

    protected bool | Closure $isDeferred = false;

    protected bool | Closure $hasCounters = false;

    protected int | Closure | null $rolesShownByDefault = null;

    protected bool | Closure $isRolePickerDeferred = true;

    protected bool | Closure $showsDeclarationProblems = true;

    protected bool | Closure $isEscalationPrevented = true;

    protected bool | Closure $isSelfEditingPrevented = true;

    protected ?Closure $grantableBy = null;

    protected ?Closure $holderCellNotes = null;

    /** @var array<Action>|Closure */
    protected array | Closure $actions = [];

    /** @var class-string<RolePermissionMatrix> */
    protected string $matrixComponent = RolePermissionMatrix::class;

    /**
     * What each operation of the role screens asks the gate — see {@see Support\Authorization} for
     * how a string, a permission enum and a closure are each asked.
     *
     * @var array{viewAny: string|BackedEnum|Closure|null, create: string|BackedEnum|Closure|null, update: string|BackedEnum|Closure|null, delete: string|BackedEnum|Closure|null}
     */
    protected array $roleAbilities = [
        'viewAny' => 'viewAny',
        'create' => 'create',
        'update' => 'update',
        'delete' => 'delete',
    ];

    /** @var class-string<AccessControl>|null */
    protected ?string $accessControlPage = AccessControl::class;

    protected ?Closure $modifyCreateRoleActionUsing = null;

    protected string | UnitEnum | Closure | null $navigationGroup = null;

    protected string | BackedEnum | Htmlable | Closure | null $navigationIcon = 'heroicon-o-shield-check';

    protected int | Closure | null $navigationSort = null;

    protected string | Closure | null $navigationLabel = null;

    protected string | Closure | null $slug = null;

    /** @var class-string|null */
    protected ?string $cluster = null;

    public function getId(): string
    {
        return self::ID;
    }

    public function register(Panel $panel): void
    {
        if ($this->hasAccessControlPage()) {
            $panel->pages([$this->getAccessControlPage()]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * The plugin registered on the current panel — or an unconfigured one, so that a component
     * used on a panel without the plugin still gets the defaults instead of an exception.
     */
    public static function current(): static
    {
        try {
            $panel = Filament::getCurrentOrDefaultPanel();
        } catch (Throwable) {
            $panel = null;
        }

        if ($panel instanceof Panel && $panel->hasPlugin(self::ID)) {
            /** @var static $plugin */
            $plugin = $panel->getPlugin(self::ID);

            return $plugin;
        }

        return static::make();
    }

    // Roles -----------------------------------------------------------------------------------

    /**
     * @param  class-string  $model  an Eloquent model implementing {@see HasEditablePermissions}
     */
    public function roleModel(string $model): static
    {
        if (! is_subclass_of($model, Model::class) || ! is_subclass_of($model, HasEditablePermissions::class)) {
            throw new InvalidArgumentException(sprintf(
                'The role model [%s] must be an Eloquent model implementing [%s].',
                $model,
                HasEditablePermissions::class,
            ));
        }

        $this->roleModel = $model;

        return $this;
    }

    /**
     * @return class-string<Model&HasEditablePermissions>|null
     */
    public function getRoleModel(): ?string
    {
        return $this->roleModel;
    }

    /**
     * The attribute a role is titled by, or a closure receiving `role` and returning the title.
     */
    public function roleTitleAttribute(string | Closure $attribute): static
    {
        $this->roleTitleAttribute = $attribute;

        return $this;
    }

    public function getRoleTitle(Model $role): string
    {
        if ($this->roleTitleAttribute instanceof Closure) {
            return (string) $this->evaluate($this->roleTitleAttribute, ['role' => $role, 'record' => $role]);
        }

        $title = $role->getAttribute($this->roleTitleAttribute);

        return is_scalar($title) && filled($title) ? (string) $title : (string) $role->getKey();
    }

    /**
     * Narrow or order the roles the matrix draws as columns. Receives `query`.
     */
    public function modifyRolesQueryUsing(?Closure $callback): static
    {
        $this->modifyRolesQueryUsing = $callback;

        return $this;
    }

    /**
     * @return Builder<Model>
     */
    public function getRolesQuery(): Builder
    {
        $model = $this->roleModel ?? throw new InvalidArgumentException(
            'The roles matrix needs a role model: call roleModel() on ' . static::class . '.',
        );

        $query = $model::query();

        if ($this->modifyRolesQueryUsing instanceof Closure) {
            $query = $this->evaluate($this->modifyRolesQueryUsing, ['query' => $query]) ?? $query;
        } else {
            $query->orderBy($query->getModel()->getQualifiedKeyName());
        }

        return $query;
    }

    /**
     * The role that short-circuits every permission check. Receives `role`.
     *
     * Its column is drawn fully granted and read-only, and a user holding it is shown every
     * permission as inherited — hiding it would be the other honest option; showing it answers
     * "why does this role ignore the grid?" without the operator having to know.
     */
    public function superAdminRole(string | Closure | null $role, string $attribute = 'code'): static
    {
        $this->isSuperAdminRoleUsing = match (true) {
            $role === null => null,
            $role instanceof Closure => $role,
            default => static fn (Model $record): bool => $record->getAttribute($attribute) === $role,
        };

        return $this;
    }

    public function isSuperAdminRole(Model $role): bool
    {
        if (! $this->isSuperAdminRoleUsing instanceof Closure) {
            return false;
        }

        if ($this->roleModel !== null && ! $role instanceof $this->roleModel) {
            return false;
        }

        return (bool) $this->evaluate($this->isSuperAdminRoleUsing, ['role' => $role, 'record' => $role]);
    }

    /**
     * What the role screens ask the gate. `viewAny` opens the access control page, `update`
     * changes one role's permissions, `create` adds a role. `null` skips the check. Permission enums
     * of laravel-access-control are welcome as they are.
     *
     * `delete` is still accepted, for existing configuration, but nothing asks it any more: the
     * access control page does not delete roles.
     */
    public function roleAbilities(
        string | BackedEnum | Closure | null | false $viewAny = false,
        string | BackedEnum | Closure | null | false $create = false,
        string | BackedEnum | Closure | null | false $update = false,
        string | BackedEnum | Closure | null | false $delete = false,
    ): static {
        foreach (['viewAny' => $viewAny, 'create' => $create, 'update' => $update, 'delete' => $delete] as $operation => $ability) {
            if ($ability !== false) {
                $this->roleAbilities[$operation] = $ability;
            }
        }

        return $this;
    }

    /**
     * @param  'viewAny'|'create'|'update'|'delete'  $operation
     */
    public function getRoleAbility(string $operation): string | BackedEnum | Closure | null
    {
        return $this->roleAbilities[$operation];
    }

    /**
     * Configure the "add role" action — its form above all. Receives `action`.
     */
    public function modifyCreateRoleActionUsing(?Closure $callback): static
    {
        $this->modifyCreateRoleActionUsing = $callback;

        return $this;
    }

    public function configureCreateRoleAction(CreateAction $action): CreateAction
    {
        if ($this->modifyCreateRoleActionUsing instanceof Closure) {
            return $this->evaluate($this->modifyCreateRoleActionUsing, ['action' => $action]) ?? $action;
        }

        return $action;
    }

    /**
     * @deprecated The access control page no longer offers a "delete role" action — delete roles
     *             where the application manages them (its role resource). Kept so existing panel
     *             configuration keeps working; the callback is never called. Removed in 4.0.
     */
    public function modifyDeleteRoleActionUsing(?Closure $callback): static
    {
        return $this;
    }

    // Behaviour -------------------------------------------------------------------------------

    /**
     * Whether the screens collect changes until the operator saves them, instead of writing every
     * click at once. The default for every screen of the plugin; each one can still say otherwise.
     */
    public function deferred(bool | Closure $condition = true): static
    {
        $this->isDeferred = $condition;

        return $this;
    }

    public function isDeferred(): bool
    {
        return (bool) $this->evaluate($this->isDeferred);
    }

    /**
     * Whether each group's header counts what every role (or the one record) holds of it — the
     * default for every screen of the plugin; each one can still say otherwise.
     */
    public function counters(bool | Closure $condition = true): static
    {
        $this->hasCounters = $condition;

        return $this;
    }

    public function hasCounters(): bool
    {
        return (bool) $this->evaluate($this->hasCounters);
    }

    /**
     * How many role columns the matrix shows until the operator picks others in its role picker —
     * the first ones in the roles query's order. Unset, every role shows; either way the picker
     * can hide and show each one.
     */
    public function rolesShownByDefault(int | Closure | null $count): static
    {
        $this->rolesShownByDefault = $count;

        return $this;
    }

    public function getRolesShownByDefault(): ?int
    {
        $count = $this->evaluate($this->rolesShownByDefault);

        return $count === null ? null : max(0, (int) $count);
    }

    /**
     * Whether the matrix's role picker applies the operator's choice on its Apply button (the
     * default) or with every tick.
     */
    public function deferRolePicker(bool | Closure $condition = true): static
    {
        $this->isRolePickerDeferred = $condition;

        return $this;
    }

    public function isRolePickerDeferred(): bool
    {
        return (bool) $this->evaluate($this->isRolePickerDeferred);
    }

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

    // Guards ----------------------------------------------------------------------------------

    /**
     * Whether an operator may grant and revoke only the permissions they hold in effect themselves —
     * see {@see GrantGuard}. On by default: a screen that hands out permissions must not become the
     * way to obtain more of them. An operator holding the super-admin role is never narrowed.
     */
    public function preventEscalation(bool | Closure $condition = true): static
    {
        $this->isEscalationPrevented = $condition;

        return $this;
    }

    public function isEscalationPrevented(): bool
    {
        return (bool) $this->evaluate($this->isEscalationPrevented);
    }

    /**
     * Whether an operator is kept from changing the permissions of a role they hold, and their own
     * direct permissions. On by default: whoever could widen their own role would need no other way in.
     */
    public function preventSelfEditing(bool | Closure $condition = true): static
    {
        $this->isSelfEditingPrevented = $condition;

        return $this;
    }

    public function isSelfEditingPrevented(): bool
    {
        return (bool) $this->evaluate($this->isSelfEditingPrevented);
    }

    /**
     * What an operator may grant and revoke, instead of what they hold in effect. Receives the
     * operator (as `operator`, or by its type) and returns the slugs — or `null` for anything.
     *
     * @param  (Closure(Authenticatable): (iterable<int, string|BackedEnum>|null))|null  $callback
     */
    public function grantableBy(?Closure $callback): static
    {
        $this->grantableBy = $callback;

        return $this;
    }

    public function hasGrantableBy(): bool
    {
        return $this->grantableBy instanceof Closure;
    }

    /**
     * The slugs {@see self::grantableBy()} gives the operator — `null` for anything, and also when no
     * callback is set; ask {@see self::hasGrantableBy()} first.
     *
     * @return Collection<int, string>|null
     */
    public function evaluateGrantableBy(Authenticatable $operator): ?Collection
    {
        if (! $this->grantableBy instanceof Closure) {
            return null;
        }

        $slugs = $this->evaluate(
            $this->grantableBy,
            namedInjections: ['operator' => $operator, 'user' => $operator],
            typedInjections: [Authenticatable::class => $operator, $operator::class => $operator],
        );

        if ($slugs === null) {
            return null;
        }

        return (new Collection(is_iterable($slugs) ? $slugs : []))
            ->map(fn (mixed $slug): string => $slug instanceof BackedEnum ? (string) $slug->value : (string) $slug)
            ->unique()
            ->values();
    }

    // Cells -----------------------------------------------------------------------------------

    /**
     * A note under a holder's cell of a permission row — never a subject's or a group's. Receives
     * the holder (as `holder`, or by its type) and the permission (`permission`) and returns a
     * {@see CellNote}, or `null` for none.
     *
     * @param  (Closure(Model, PermissionDto): ?CellNote)|null  $callback
     */
    public function holderCellNotes(?Closure $callback): static
    {
        $this->holderCellNotes = $callback;

        return $this;
    }

    public function hasHolderCellNotes(): bool
    {
        return $this->holderCellNotes instanceof Closure;
    }

    public function getHolderCellNote(Model $holder, PermissionDto $permission): ?CellNote
    {
        if (! $this->holderCellNotes instanceof Closure) {
            return null;
        }

        $note = $this->evaluate(
            $this->holderCellNotes,
            namedInjections: ['holder' => $holder, 'record' => $holder, 'permission' => $permission],
            typedInjections: [Model::class => $holder, $holder::class => $holder, PermissionDto::class => $permission],
        );

        return $note instanceof CellNote ? $note : null;
    }

    /**
     * Further actions the permission screens can mount by name — the ones {@see CellNote}s open.
     * Mounted with the note's arguments plus `holder` (the holder's key) and `permission` (the
     * slug); after one runs, the screen reads every holder again.
     *
     * Each action authorises itself (`->authorize(...)`): the screens only refuse to mount them
     * while they edit nothing. A closure is evaluated on every request, never at boot, so labels
     * are translated in the request's locale.
     *
     * @param  array<Action>|Closure(): array<Action>  $actions
     */
    public function actions(array | Closure $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * @return array<Action>
     */
    public function getActions(): array
    {
        $actions = $this->evaluate($this->actions);
        $actions = array_values(array_filter(is_array($actions) ? $actions : [], fn (mixed $action): bool => $action instanceof Action));

        foreach ($actions as $action) {
            // Filament uses a string handler only as a direct Livewire click handler. A plugin action
            // is reached through `mountAction()`, which runs `getActionFunction()` — null for a
            // string — so even a method on a subclass of our components would never run here.
            // Silently running nothing is worse than saying so.
            if ($action->hasAction() && ! $action->getActionFunction() instanceof Closure) {
                throw new InvalidArgumentException(sprintf(
                    'The plugin action [%s] has a method name as its handler, which cannot work here: Filament uses a string handler only as a direct Livewire click handler, while a plugin action runs through mountAction(), which ignores it — even a method defined on your own subclass of the component would not be called. Pass a Closure to ->action() instead.',
                    $action->getName(),
                ));
            }
        }

        return $actions;
    }

    // Access control page ---------------------------------------------------------------------

    /**
     * The page drawing the roles × permissions matrix: another class extending
     * {@see AccessControl}, or `false` to register none and place the matrix yourself.
     *
     * @param  class-string<AccessControl>|false  $page
     */
    public function accessControlPage(string | false $page): static
    {
        $this->accessControlPage = $page === false ? null : $page;

        return $this;
    }

    public function hasAccessControlPage(): bool
    {
        return $this->accessControlPage !== null && $this->roleModel !== null;
    }

    /**
     * @return class-string<AccessControl>
     */
    public function getAccessControlPage(): string
    {
        return $this->accessControlPage ?? AccessControl::class;
    }

    /**
     * The Livewire component the access control page draws as its matrix — a class extending
     * {@see RolePermissionMatrix}, to change more of it than the plugin exposes.
     *
     * @param  class-string  $component  a Livewire component extending {@see RolePermissionMatrix}
     */
    public function matrixComponent(string $component): static
    {
        if (! is_a($component, RolePermissionMatrix::class, true)) {
            throw new InvalidArgumentException(sprintf(
                'The matrix component [%s] must extend [%s].',
                $component,
                RolePermissionMatrix::class,
            ));
        }

        $this->matrixComponent = $component;

        return $this;
    }

    /**
     * @return class-string<RolePermissionMatrix>
     */
    public function getMatrixComponent(): string
    {
        return $this->matrixComponent;
    }

    public function navigationGroup(string | UnitEnum | Closure | null $group): static
    {
        $this->navigationGroup = $group;

        return $this;
    }

    public function getNavigationGroup(): string | UnitEnum | null
    {
        return $this->evaluate($this->navigationGroup);
    }

    public function navigationIcon(string | BackedEnum | Htmlable | Closure | null $icon): static
    {
        $this->navigationIcon = $icon;

        return $this;
    }

    public function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return $this->evaluate($this->navigationIcon);
    }

    public function navigationSort(int | Closure | null $sort): static
    {
        $this->navigationSort = $sort;

        return $this;
    }

    public function getNavigationSort(): ?int
    {
        return $this->evaluate($this->navigationSort);
    }

    public function navigationLabel(string | Closure | null $label): static
    {
        $this->navigationLabel = $label;

        return $this;
    }

    public function getNavigationLabel(): ?string
    {
        return $this->evaluate($this->navigationLabel);
    }

    public function slug(string | Closure | null $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->evaluate($this->slug);
    }

    /**
     * @param  class-string|null  $cluster
     */
    public function cluster(?string $cluster): static
    {
        $this->cluster = $cluster;

        return $this;
    }

    /**
     * @return class-string|null
     */
    public function getCluster(): ?string
    {
        return $this->cluster;
    }
}
