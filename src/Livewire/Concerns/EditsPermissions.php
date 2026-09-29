<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire\Concerns;

use BackedEnum;
use Closure;
use Filament\Notifications\Notification;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Support\Authorization;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Support\PermissionWriter;
use Happenv\FilamentAccessControl\Support\RefusalLead;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Happenv\LaravelAccessControl\Dto\PermissionSubjectDto;
use Happenv\LaravelAccessControl\PermissionRestrictions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;

/**
 * Everything a screen that hands permissions to one or more HOLDERS (roles, users) shares.
 *
 * Two ways of saving, one state machine:
 *
 *   - LIVE (the default): every click is written at once, under a row lock;
 *   - DEFERRED: clicks are staged in {@see self::$changes} and nothing reaches the database until
 *     the operator presses Save — or throws it all away with Discard.
 *
 * Every click round-trips to the server rather than syncing through `$wire.$entangle`. Permission
 * slugs contain dots, and both `data_set()` and `$entangle` read a dot as nesting — a state map
 * keyed by slug is a tree pretending to be a flat array. That is also why the staged changes are
 * LISTS of slugs per holder, never maps keyed by them.
 *
 * What a change means is decided from what THIS operator saw: a click on a granted cell revokes,
 * on an empty one grants. The write replays that intent onto the list as it is at the moment of
 * writing ({@see PermissionWriter}), so another operator's change to a different cell survives.
 */
trait EditsPermissions
{
    public string $search = '';

    /**
     * Slugs of the groups the operator has opened. Everything starts collapsed: the catalogue spans
     * every module in the deployment, and an operator who came to change one thing should not have
     * to scroll past the rest.
     *
     * @var list<string>
     */
    public array $expandedGroups = [];

    /**
     * Nullable only for the moment Livewire assigns the mount parameters of the same name, before
     * `mount()` resolves "not said" to the plugin's default.
     */
    #[Locked]
    public ?bool $deferred = null;

    #[Locked]
    public ?PermissionSurfaceDefinition $surface = null;

    /**
     * Staged changes of a deferred screen, per holder key.
     *
     * Locked: only this component's own methods — which authorise and validate every click —
     * write here. What the client sends is a click, never the list to save.
     *
     * @var array<array-key, array{grant: list<string>, revoke: list<string>}>
     */
    #[Locked]
    public array $changes = [];

    /**
     * Authorisation answers, per holder key, for the life of one request.
     *
     * @var array<array-key, bool>
     */
    protected array $editableHolders = [];

    /**
     * The records this screen edits, keyed by {@see self::holderKey()}.
     *
     * @return Collection<array-key, Model&HasEditablePermissions>
     */
    abstract protected function getHolders(): Collection;

    /**
     * A holder whose list is not the answer — a super-admin role, for one. Drawn fully granted and
     * never written to.
     */
    abstract public function isHolderLocked(Model $holder): bool;

    /**
     * The ability asked, with the holder, before any of its permissions change.
     */
    abstract protected function getUpdateAbility(): string | BackedEnum | Closure | null;

    abstract public function getHolderTitle(Model $holder): string;

    public function holderKey(Model $holder): string
    {
        return (string) $holder->getKey();
    }

    /**
     * @return Collection<array-key, Model&HasEditablePermissions>
     */
    #[Computed]
    public function holders(): Collection
    {
        return $this->getHolders();
    }

    /**
     * @return Collection<string, PermissionGroupDto>
     */
    #[Computed]
    public function groups(): Collection
    {
        return $this->tree()->groups($this->search, $this->surface);
    }

    /**
     * What each holder holds in the database, as a lookup the view can hit once per cell.
     *
     * @return array<array-key, array<string, bool>>
     */
    #[Computed]
    public function grants(): array
    {
        return $this->holders
            ->map(fn (HasEditablePermissions $holder): array => $holder->getPermissions()
                ->mapWithKeys(fn (string $slug): array => [$slug => true])
                ->all())
            ->all();
    }

    /**
     * What this screen may grant: the catalogue, narrowed to the surface when there is one.
     *
     * @return array<string, bool>
     */
    #[Computed]
    public function offeredSlugs(): array
    {
        $slugs = $this->surface instanceof PermissionSurfaceDefinition
            ? $this->tree()->slugsAvailableOn($this->surface)
            : $this->tree()->slugs();

        return $slugs->mapWithKeys(fn (string $slug): array => [$slug => true])->all();
    }

    // Reading ---------------------------------------------------------------------------------

    /**
     * Whether the holder holds the permission as the screen should show it — staged changes
     * included, and everything granted for a locked holder.
     */
    public function isGranted(string $holderKey, string $slug): bool
    {
        if ($this->isLockedKey($holderKey)) {
            return true;
        }

        $staged = $this->changes[$holderKey] ?? null;

        if ($staged !== null) {
            if (in_array($slug, $staged['grant'], true)) {
                return true;
            }

            if (in_array($slug, $staged['revoke'], true)) {
                return false;
            }
        }

        return $this->isStored($holderKey, $slug);
    }

    /**
     * Whether the database says the holder holds the permission, staged changes aside.
     */
    public function isStored(string $holderKey, string $slug): bool
    {
        return isset($this->grants[$holderKey][$slug]);
    }

    public function isStaged(string $holderKey, string $slug): bool
    {
        $staged = $this->changes[$holderKey] ?? null;

        return $staged !== null
            && (in_array($slug, $staged['grant'], true) || in_array($slug, $staged['revoke'], true));
    }

    /**
     * @param  iterable<PermissionDto>  $permissions
     */
    public function countGranted(string $holderKey, iterable $permissions): int
    {
        $count = 0;

        foreach ($permissions as $permission) {
            if ($this->isGranted($holderKey, $permission->slug)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param  iterable<PermissionDto>  $permissions
     */
    public function hasStagedIn(string $holderKey, iterable $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->isStaged($holderKey, $permission->slug)) {
                return true;
            }
        }

        return false;
    }

    public function isOffered(string $slug): bool
    {
        return isset($this->offeredSlugs[$slug]);
    }

    /**
     * Whether the application restricts the permission at runtime — denied to everyone, whatever
     * the grid says. Shown so an operator does not grant it and then wonder why nothing changed.
     */
    public function isRestricted(PermissionDto $permission): bool
    {
        return resolve(PermissionRestrictions::class)->isRestricted($permission->enum);
    }

    public function isLockedKey(string $holderKey): bool
    {
        $holder = $this->holders->get($holderKey);

        return $holder instanceof Model && $this->isHolderLocked($holder);
    }

    public function canEditHolder(string $holderKey): bool
    {
        if (array_key_exists($holderKey, $this->editableHolders)) {
            return $this->editableHolders[$holderKey];
        }

        $holder = $this->holders->get($holderKey);

        return $this->editableHolders[$holderKey] = $holder instanceof Model
            && $this->isEditable()
            && ! $this->isHolderLocked($holder)
            && Authorization::allows($this->getUpdateAbility(), $holder);
    }

    /**
     * Whether this screen edits anything at all — a read-only mode switches every control off.
     */
    public function isEditable(): bool
    {
        return true;
    }

    public function hasStagedChanges(): bool
    {
        return $this->changes !== [];
    }

    public function stagedCount(): int
    {
        return array_sum(array_map(
            fn (array $staged): int => count($staged['grant']) + count($staged['revoke']),
            $this->changes,
        ));
    }

    public function actionLabel(PermissionDto $permission): string
    {
        return $this->tree()->actionLabel($permission);
    }

    // Groups ----------------------------------------------------------------------------------

    public function toggleGroup(string $groupSlug): void
    {
        $this->expandedGroups = in_array($groupSlug, $this->expandedGroups, strict: true)
            ? array_values(array_diff($this->expandedGroups, [$groupSlug]))
            : [...$this->expandedGroups, $groupSlug];
    }

    public function expandAll(): void
    {
        $this->expandedGroups = $this->groups->keys()->map(fn (int | string $slug): string => (string) $slug)->values()->all();
    }

    public function collapseAll(): void
    {
        $this->expandedGroups = [];
    }

    public function isExpanded(string $groupSlug): bool
    {
        // A search has already narrowed the list to what the operator asked for; making them open
        // each surviving group would undo the narrowing.
        return filled($this->search) || in_array($groupSlug, $this->expandedGroups, strict: true);
    }

    // Changing --------------------------------------------------------------------------------

    public function toggle(string $holderKey, string $slug): void
    {
        $holder = $this->mutableHolder($holderKey);

        if (! $holder instanceof Model) {
            return;
        }

        if ($this->tree()->slugs()->doesntContain($slug)) {
            $this->deny(__('filament-access-control::editor.notifications.no_permission'));

            return;
        }

        if ($this->isGranted($holderKey, $slug)) {
            $this->change($holder, revoke: [$slug]);

            return;
        }

        // A permission this screen does not offer can be taken away but never handed out: that is
        // the whole point of narrowing a screen to a surface. Taking back a staged revocation of
        // one the holder still holds hands nothing out, so it stays possible.
        if (! $this->isOffered($slug) && ! $this->isStored($holderKey, $slug)) {
            $this->deny(__('filament-access-control::editor.notifications.not_offered'));

            return;
        }

        $this->change($holder, grant: [$slug]);
    }

    /**
     * Grant a subject's whole verb set, or clear it — whichever the current state is not.
     */
    public function toggleSubject(string $holderKey, string $groupSlug, string $subjectKey): void
    {
        $holder = $this->mutableHolder($holderKey);

        if (! $holder instanceof Model) {
            return;
        }

        $subject = $this->groups->get($groupSlug)?->subjects->get($subjectKey);

        if (! $subject instanceof PermissionSubjectDto) {
            $this->deny(__('filament-access-control::editor.notifications.no_permission'));

            return;
        }

        $slugs = $subject->children
            ->map(fn (PermissionDto $permission): string => $permission->slug)
            ->filter(fn (string $slug): bool => $this->isOffered($slug))
            ->values();

        if ($this->countGranted($holderKey, $subject->children) < $subject->children->count()) {
            $this->change($holder, grant: $slugs->all());

            return;
        }

        $this->change($holder, revoke: $subject->children->map(fn (PermissionDto $permission): string => $permission->slug)->all());
    }

    public function save(): void
    {
        $saved = 0;

        foreach (array_keys($this->changes) as $holderKey) {
            $holderKey = (string) $holderKey;
            $staged = $this->changes[$holderKey];
            $holder = $this->mutableHolder($holderKey);

            if (! $holder instanceof Model) {
                continue;
            }

            // Validated again rather than trusted: the catalogue may have changed under a screen left
            // open, and a staged slug must be as grantable at Save as it was at the click.
            $known = $this->tree()->slugs()->flip();

            $this->persist(
                $holder,
                grant: array_values(array_filter($staged['grant'], fn (string $slug): bool => $this->isOffered($slug))),
                revoke: array_values(array_filter($staged['revoke'], fn (string $slug): bool => $known->has($slug))),
            );

            unset($this->changes[$holderKey]);
            $saved++;
        }

        if ($saved > 0) {
            Notification::make()
                ->title(__('filament-access-control::editor.notifications.saved'))
                ->success()
                ->send();
        }
    }

    public function discard(): void
    {
        $this->changes = [];
    }

    /**
     * @param  Model&HasEditablePermissions  $holder
     * @param  list<string>  $grant
     * @param  list<string>  $revoke
     */
    protected function change(Model $holder, array $grant = [], array $revoke = []): void
    {
        if ($this->deferred === true) {
            $this->stage($this->holderKey($holder), $grant, $revoke);

            return;
        }

        $this->persist($holder, $grant, $revoke);
    }

    /**
     * @param  list<string>  $grant
     * @param  list<string>  $revoke
     */
    protected function stage(string $holderKey, array $grant, array $revoke): void
    {
        $staged = $this->changes[$holderKey] ?? ['grant' => [], 'revoke' => []];

        foreach ($grant as $slug) {
            $staged['revoke'] = array_values(array_diff($staged['revoke'], [$slug]));

            if (! $this->isStored($holderKey, $slug) && ! in_array($slug, $staged['grant'], true)) {
                $staged['grant'][] = $slug;
            }
        }

        foreach ($revoke as $slug) {
            $staged['grant'] = array_values(array_diff($staged['grant'], [$slug]));

            if ($this->isStored($holderKey, $slug) && ! in_array($slug, $staged['revoke'], true)) {
                $staged['revoke'][] = $slug;
            }
        }

        if ($staged['grant'] === [] && $staged['revoke'] === []) {
            unset($this->changes[$holderKey]);

            return;
        }

        $this->changes[$holderKey] = $staged;
    }

    /**
     * @param  Model&HasEditablePermissions  $holder
     * @param  list<string>  $grant
     * @param  list<string>  $revoke
     */
    protected function persist(Model $holder, array $grant, array $revoke): void
    {
        resolve(PermissionWriter::class)->write($holder, $grant, $revoke);

        $this->written($holder);
        $this->refreshHolders();

        $this->dispatch('filament-access-control::permissions-updated', holder: $this->holderKey($holder));
    }

    /**
     * Called after a holder's list was written, before the screen reads it again.
     */
    protected function written(Model $holder): void
    {
        //
    }

    protected function refreshHolders(): void
    {
        unset($this->holders, $this->grants);

        $this->editableHolders = [];
    }

    /**
     * Resolve the holder a click names, refusing the ones this screen must not write to.
     *
     * @return (Model&HasEditablePermissions)|null
     */
    protected function mutableHolder(string $holderKey): ?Model
    {
        $holder = $this->holders->get($holderKey);

        if (! $holder instanceof Model) {
            $this->deny(__('filament-access-control::editor.notifications.no_holder'));

            return null;
        }

        if ($this->isHolderLocked($holder)) {
            $this->deny(__('filament-access-control::editor.super_admin_hint'));

            return null;
        }

        if (! $this->isEditable()) {
            $this->deny(__('filament-access-control::editor.notifications.read_only'));

            return null;
        }

        $verdict = Authorization::inspect($this->getUpdateAbility(), $holder);

        if ($verdict->denied()) {
            $this->deny(resolve(RefusalLead::class)->fromMessage($verdict->message())
                ?? __('filament-access-control::editor.notifications.unauthorized'));

            return null;
        }

        return $holder;
    }

    protected function tree(): PermissionTree
    {
        return resolve(PermissionTree::class);
    }

    protected function deny(string $message): void
    {
        Notification::make()
            ->title($message)
            ->danger()
            ->send();
    }
}
