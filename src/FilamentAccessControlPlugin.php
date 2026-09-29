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
use Happenv\FilamentAccessControl\Pages\AccessControl;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
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

    protected ?Closure $modifyDeleteRoleActionUsing = null;

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
     * changes one role's permissions, `create` and `delete` add and remove roles. `null` skips the
     * check. Permission enums of laravel-access-control are welcome as they are.
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
     * Configure the matrix's "delete role" action. Receives `action`.
     */
    public function modifyDeleteRoleActionUsing(?Closure $callback): static
    {
        $this->modifyDeleteRoleActionUsing = $callback;

        return $this;
    }

    public function configureDeleteRoleAction(Action $action): Action
    {
        if ($this->modifyDeleteRoleActionUsing instanceof Closure) {
            return $this->evaluate($this->modifyDeleteRoleActionUsing, ['action' => $action]) ?? $action;
        }

        return $action;
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
