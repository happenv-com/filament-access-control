<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Livewire\Concerns;

use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Support\Authorization;
use Happenv\FilamentAccessControl\Support\DeclarationProblems;
use Happenv\FilamentAccessControl\Support\DependencyBadge;
use Happenv\FilamentAccessControl\Support\PermissionCell;
use Happenv\FilamentAccessControl\Support\PermissionCellState;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Support\PermissionWriter;
use Happenv\FilamentAccessControl\Support\RefusalLead;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;
use Happenv\LaravelAccessControl\Dto\PermissionSubjectDto;
use Happenv\LaravelAccessControl\PermissionResolver;
use Happenv\LaravelAccessControl\PermissionRestrictions;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Js;
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
 * The screens are Filament tables fed with arrays ({@see self::permissionRecords()}): one row per
 * SUBJECT (a permission enum) followed by one row per permission of it, grouped by module into
 * Filament's own collapsible groups, and one clickable icon column per holder.
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
    /**
     * Nullable only for the moment Livewire assigns the mount parameters of the same name, before
     * `mount()` resolves "not said" to the plugin's default.
     */
    #[Locked]
    public ?bool $deferred = null;

    #[Locked]
    public ?PermissionSurfaceDefinition $surface = null;

    /**
     * Whether each group's header counts what every holder holds of it. Nullable for the same
     * reason as {@see self::$deferred}.
     */
    #[Locked]
    public ?bool $counters = null;

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
     * One resolution per holder for the life of one request: the library resolves the rules over a
     * stored state once and answers every permission from it. Forgotten whenever what the screen
     * treats as stored changes — a click staged, a write, a discard.
     *
     * @var array<array-key, Closure(PermissionDefinition): PermissionResolutionDto>
     */
    protected array $permissionExplainers = [];

    /**
     * The cells built from those resolutions — a cell's state, colour and tooltip are asked for
     * separately.
     *
     * @var array<array-key, array<string, PermissionCell>>
     */
    protected array $permissionCells = [];

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

    abstract protected function plugin(): FilamentAccessControlPlugin;

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
        return $this->tree()->groups(surface: $this->surface);
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

    /**
     * Whether a holder's cells show what the rules make of its grants, or only whether it holds each
     * one. A role's do; an account's direct grants do not — its "In effect" column does that.
     */
    public function resolvesHolderCells(): bool
    {
        return true;
    }

    /**
     * What the library resolves a holder's grants to — staged changes included, and without an
     * account, so without conditions: a role holds grants, it does not sign in.
     */
    public function holderResolution(string $holderKey, PermissionDefinition $permission): PermissionResolutionDto
    {
        $explain = $this->permissionExplainers[$holderKey] ??= resolve(PermissionResolver::class)->explainer(
            fn (PermissionDefinition $candidate): bool => $this->isGranted($holderKey, (string) $candidate->value),
        );

        return $explain($permission);
    }

    /**
     * A holder's cell for one permission: its resolution as an icon, a colour and the reasons.
     */
    public function holderCell(string $holderKey, string $slug): PermissionCell
    {
        return $this->permissionCells[$holderKey][$slug] ??= $this->makeHolderCell($holderKey, $slug);
    }

    protected function makeHolderCell(string $holderKey, string $slug): PermissionCell
    {
        $permission = $this->tree()->find($slug);

        // A super-admin role holds everything, whatever the rules would say about a list it does not have.
        if ($this->isLockedKey($holderKey) || ! $permission instanceof PermissionDto) {
            return new PermissionCell($this->isGranted($holderKey, $slug) ? PermissionCellState::Effective : PermissionCellState::NotGranted);
        }

        $cell = PermissionCell::of(
            $this->holderResolution($holderKey, $permission->enum),
            $this->tree(),
            staged: $this->isStaged($holderKey, $slug),
        );

        return $cell->state === PermissionCellState::Implied && $this->canEditHolder($holderKey)
            ? $cell->withReason(__('filament-access-control::editor.cells.grant_explicitly'))
            : $cell;
    }

    protected function forgetResolutions(): void
    {
        $this->permissionExplainers = [];
        $this->permissionCells = [];
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

    // Table -----------------------------------------------------------------------------------

    /**
     * The table's rows: per subject, the subject itself and then each of its permissions.
     *
     * Keyed the way Filament keys custom data — `subject:` and the enum, `permission:` and the slug —
     * and already in group order, which is all Filament's grouping of custom data needs. The search
     * narrows at SUBJECT granularity, as the catalogue does: a matching verb brings its whole subject.
     *
     * @return array<string, array{type: string, group: string, group_name: string, group_description: ?string, subject: string, label: string, description: ?string, slug: ?string, restricted: bool}>
     */
    public function permissionRecords(?string $search = null): array
    {
        $records = [];

        foreach ($this->tree()->groups($search, $this->surface) as $group) {
            foreach ($group->subjects as $subjectKey => $subject) {
                $shared = [
                    'group' => $group->slug,
                    'group_name' => $group->name,
                    'group_description' => $group->description,
                    'subject' => (string) $subjectKey,
                ];

                $records['subject:' . $subjectKey] = [
                    ...$shared,
                    'type' => 'subject',
                    'label' => $subject->name,
                    'description' => $subject->description,
                    'slug' => null,
                    'restricted' => false,
                ];

                foreach ($subject->children as $permission) {
                    $records['permission:' . $permission->slug] = [
                        ...$shared,
                        'type' => 'permission',
                        'label' => $this->actionLabel($permission),
                        'description' => $permission->description,
                        'slug' => $permission->slug,
                        'restricted' => $this->isRestricted($permission),
                    ];
                }
            }
        }

        return $records;
    }

    /**
     * What one holder's cell shows for a row: a {@see PermissionCellState} value for a permission —
     * or `granted` / `revoked` where the screen shows only what is held — and `all`, `some` or
     * `none` for a subject.
     *
     * @param  array<string, mixed>  $record
     */
    public function cellState(string $holderKey, array $record): string
    {
        if ($record['type'] !== 'subject') {
            $slug = (string) $record['slug'];

            if (! $this->resolvesHolderCells()) {
                return $this->isGranted($holderKey, $slug) ? 'granted' : 'revoked';
            }

            return $this->holderCell($holderKey, $slug)->state->value;
        }

        $permissions = $this->subject((string) $record['group'], (string) $record['subject'])->children ?? new Collection;
        $granted = $this->countGranted($holderKey, $permissions);

        return match (true) {
            $granted === 0 => 'none',
            $granted === $permissions->count() => 'all',
            default => 'some',
        };
    }

    /**
     * @param  array<string, mixed>  $record
     */
    public function isStagedRecord(string $holderKey, array $record): bool
    {
        if ($record['type'] !== 'subject') {
            return $this->isStaged($holderKey, (string) $record['slug']);
        }

        return $this->hasStagedIn(
            $holderKey,
            $this->subject((string) $record['group'], (string) $record['subject'])->children ?? [],
        );
    }

    /**
     * The group's description — and, with counters on, what each holder holds of the group, so a
     * folded group still says whether it is worth opening.
     *
     * @param  array<string, mixed>  $record
     */
    public function groupDescription(array $record): string | Htmlable | null
    {
        $group = $this->groups->get((string) $record['group']);

        if ($this->counters !== true || ! $group instanceof PermissionGroupDto) {
            return $record['group_description'];
        }

        $permissions = $group->subjects->flatMap(fn (PermissionSubjectDto $subject): Collection => $subject->children);
        $named = $this->holders->count() > 1;

        return new HtmlString(view('filament-access-control::partials.group-counters', [
            'description' => $record['group_description'],
            'counts' => $this->holders
                ->map(fn (Model $holder, int | string $key): array => [
                    'holder' => $named ? $this->getHolderTitle($holder) : null,
                    'granted' => $this->countGranted((string) $key, $permissions),
                    'total' => $permissions->count(),
                ])
                ->values()
                ->all(),
        ])->render());
    }

    /**
     * Open or fold every group at once.
     *
     * Filament folds groups in the browser, so this is said to the table's Alpine component: with
     * groups folded by default, the ones listed in `groupVisibility` are the open ones.
     */
    public function setGroupsExpanded(bool $expanded): void
    {
        $titles = $expanded
            ? $this->tree()->groups($this->getTableSearch(), $this->surface)
                ->map(fn (PermissionGroupDto $group): string => $group->name)
                ->values()
                ->all()
            : [];

        $this->js('Alpine.$data($wire.$el.querySelector(\'.fi-ta\')).groupVisibility = ' . Js::from($titles));
    }

    /**
     * A search has already narrowed the table to what the operator asked for; making them open each
     * surviving group would undo the narrowing — and clearing it folds everything back.
     */
    public function updated(string $property): void
    {
        if ($property === 'tableSearch') {
            $this->setGroupsExpanded(filled($this->getTableSearch()));
        }
    }

    /**
     * @param  array<Action>  $toolbarActions  the screen's own, drawn after expand / collapse
     */
    protected function configurePermissionTable(Table $table, array $toolbarActions = []): Table
    {
        return $table
            ->records(fn (?string $search): array => $this->permissionRecords($search))
            ->groups([
                Group::make('group')
                    ->getTitleFromRecordUsing(fn (array $record): string => $record['group_name'])
                    ->getDescriptionFromRecordUsing(fn (array $record): string | Htmlable | null => $this->groupDescription($record))
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->defaultGroup('group')
            ->groupingSettingsHidden()
            // Folded: the catalogue spans every module of the deployment, and an operator who came to
            // change one thing should not have to scroll past the rest.
            ->collapsedGroupsByDefault()
            ->recordClasses(fn (array $record): ?string => $record['type'] === 'subject' ? 'fi-striped' : null)
            ->paginated(false)
            ->searchPlaceholder(__('filament-access-control::editor.search'))
            ->emptyStateIcon(Heroicon::OutlinedShieldCheck)
            ->emptyStateHeading(fn (): string => filled($this->getTableSearch())
                ? __('filament-access-control::editor.search_empty', ['search' => $this->getTableSearch()])
                : __('filament-access-control::editor.offering_empty'))
            ->toolbarActions([
                Action::make('expandAll')
                    ->label(__('filament-access-control::editor.expand_all'))
                    ->link()
                    ->color('gray')
                    ->action(fn () => $this->setGroupsExpanded(true)),
                Action::make('collapseAll')
                    ->label(__('filament-access-control::editor.collapse_all'))
                    ->link()
                    ->color('gray')
                    ->action(fn () => $this->setGroupsExpanded(false)),
                ...$toolbarActions,
                Action::make('discardChanges')
                    ->label(__('filament-access-control::editor.actions.discard'))
                    ->color('gray')
                    ->visible(fn (): bool => $this->deferred === true)
                    ->disabled(fn (): bool => ! $this->hasStagedChanges())
                    ->action(fn () => $this->discard()),
                Action::make('saveChanges')
                    ->label(__('filament-access-control::editor.actions.save'))
                    ->icon(Heroicon::Check)
                    ->visible(fn (): bool => $this->deferred === true)
                    ->disabled(fn (): bool => ! $this->hasStagedChanges())
                    ->badge(fn (): ?int => $this->hasStagedChanges() ? $this->stagedCount() : null)
                    ->badgeColor('warning')
                    ->action(fn () => $this->save()),
            ]);
    }

    protected function permissionColumn(): TextColumn
    {
        return TextColumn::make('label')
            ->label(__('filament-access-control::editor.columns.permission'))
            ->description(fn (array $record): ?string => $record['description'])
            ->weight(fn (array $record): ?FontWeight => $record['type'] === 'subject' ? FontWeight::SemiBold : null)
            // Indented under its subject — inline, so that it holds without the app's theme having to
            // compile a utility class out of a PHP file.
            ->extraAttributes(fn (array $record): array => $record['type'] === 'subject' ? [] : ['style' => 'padding-inline-start: 2rem'])
            ->icon(fn (array $record): ?Heroicon => $record['restricted'] ? Heroicon::LockClosed : null)
            ->iconColor('gray')
            ->iconPosition(IconPosition::After)
            ->tooltip(fn (array $record): ?string => $record['restricted'] ? __('filament-access-control::editor.restricted_hint') : null)
            ->wrap()
            ->searchable();
    }

    /**
     * The rules and conditions next to a permission's name — both ends of every rule — so an operator
     * sees why a cell shows what it shows. Hidden while no permission declares any.
     */
    protected function dependenciesColumn(): TextColumn
    {
        return TextColumn::make('dependencies')
            ->label(__('filament-access-control::editor.columns.dependencies'))
            ->state(fn (array $record): array => $this->dependencyBadges($record))
            ->badge()
            ->tooltip(fn (array $record): ?string => $this->dependencyTooltip($record))
            ->wrap()
            ->visible(fn (): bool => $this->tree()->hasDependencies());
    }

    /**
     * @param  array<string, mixed>  $record
     * @return list<DependencyBadge>
     */
    public function dependencyBadges(array $record): array
    {
        $permission = $record['type'] === 'permission' ? $this->tree()->find((string) $record['slug']) : null;

        if (! $permission instanceof PermissionDto) {
            return [];
        }

        $badges = $this->tree()->dependencies($permission);

        if ($this->plugin()->showsDeclarationProblems() && resolve(DeclarationProblems::class)->declares($permission->enum)) {
            $badges[] = new DependencyBadge(__('filament-access-control::editor.dependencies.invalid_declaration'), 'danger');
        }

        return $badges;
    }

    /**
     * The declaration problems, worded — nothing when the plugin keeps quiet about them.
     *
     * @return list<string>
     */
    public function declarationProblemSentences(): array
    {
        return $this->plugin()->showsDeclarationProblems()
            ? resolve(DeclarationProblems::class)->sentences()
            : [];
    }

    /**
     * Why each rule exists, where its declaration says.
     *
     * @param  array<string, mixed>  $record
     */
    public function dependencyTooltip(array $record): ?string
    {
        $reasons = [];

        foreach ($this->dependencyBadges($record) as $badge) {
            if (filled($badge->reason)) {
                $reasons[] = $badge->label . ' — ' . $badge->reason;
            }
        }

        return $reasons === [] ? null : implode(' · ', $reasons);
    }

    /**
     * One holder's column: a clickable icon per row — a verb granted or not, a subject held in
     * full, in part or not at all.
     */
    protected function holderColumn(string $holderKey, string | Htmlable $label): IconColumn
    {
        return IconColumn::make('holder_' . $holderKey)
            ->label($label)
            ->alignCenter()
            ->state(fn (array $record): string => $this->cellState($holderKey, $record))
            ->icon(fn (string $state): Heroicon => PermissionCellState::tryFrom($state)?->icon() ?? match ($state) {
                'granted', 'all' => Heroicon::CheckCircle,
                'some' => Heroicon::MinusCircle,
                default => Heroicon::XCircle,
            })
            // A staged cell keeps the shape of what it will become and takes the primary colour until
            // it is saved — Filament's own palette, no styles of our own. `warning` means a missing
            // requirement.
            ->color(fn (string $state, array $record): string => match (true) {
                $this->isStagedRecord($holderKey, $record) => 'primary',
                PermissionCellState::tryFrom($state) instanceof PermissionCellState => PermissionCellState::from($state)->color(),
                in_array($state, ['granted', 'all'], true) => 'success',
                $state === 'some' => 'warning',
                $state === 'revoked' => 'danger',
                default => 'gray',
            })
            ->tooltip(fn (array $record): ?string => match (true) {
                $record['type'] === 'subject' => $this->isStagedRecord($holderKey, $record)
                    ? __('filament-access-control::editor.staged_marker')
                    : $this->subjectTooltip($holderKey, $record),
                $this->resolvesHolderCells() => $this->holderCell($holderKey, (string) $record['slug'])->tooltip(),
                $this->isStagedRecord($holderKey, $record) => __('filament-access-control::editor.staged_marker'),
                default => null,
            })
            ->disabledClick(fn (): bool => ! $this->canEditHolder($holderKey))
            ->action(function (array $record) use ($holderKey): void {
                $record['type'] === 'subject'
                    ? $this->toggleSubject($holderKey, (string) $record['group'], (string) $record['subject'])
                    : $this->toggle($holderKey, (string) $record['slug']);
            });
    }

    /**
     * @param  array<string, mixed>  $record
     */
    protected function subjectTooltip(string $holderKey, array $record): string
    {
        if ($this->counters !== true) {
            return __('filament-access-control::editor.toggle_subject');
        }

        $permissions = $this->subject((string) $record['group'], (string) $record['subject'])->children ?? new Collection;

        return __('filament-access-control::editor.counter', [
            'granted' => $this->countGranted($holderKey, $permissions),
            'total' => $permissions->count(),
        ]) . ' · ' . __('filament-access-control::editor.toggle_subject');
    }

    protected function subject(string $groupSlug, string $subjectKey): ?PermissionSubjectDto
    {
        $subject = $this->groups->get($groupSlug)?->subjects->get($subjectKey);

        return $subject instanceof PermissionSubjectDto ? $subject : null;
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

        $subject = $this->subject($groupSlug, $subjectKey);

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
        $this->forgetResolutions();

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
        $this->forgetResolutions();

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
        $this->forgetResolutions();

        unset($this->holders, $this->grants);

        $this->flushCachedTableRecords();

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
