<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Closure;
use Happenv\FilamentAccessControl\Contracts\OffersEveryPermission;
use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Happenv\LaravelAccessControl\Dto\PermissionSubjectDto;
use Happenv\LaravelAccessControl\PermissionCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * The permission catalogue, shaped for the screens that hand permissions out.
 *
 * Three levels, because a large catalogue is too big for two: a group alone would list dozens of
 * rows in which `View` recurs once per enum with nothing to tell the entries apart, so the enum
 * sits between them as the SUBJECT and the permission is reduced to its verb.
 *
 * Built at most once per instance, and the instance lives for one request — the service provider
 * binds it `scoped`. `PermissionCollection::getGroupedPermissions()` memoises nothing and reflects
 * over every registered enum on each call; a tree built at boot instead would freeze the booting
 * worker's locale into every label and serve it to every request afterwards.
 */
class PermissionTree
{
    /**
     * Resolves the verb shown for one permission, ahead of the package's own translations.
     *
     * @var (Closure(PermissionDto): ?string)|null
     */
    protected static ?Closure $actionLabelResolver = null;

    /** @var Collection<string,PermissionGroupDto>|null */
    private ?Collection $catalogue = null;

    /** @var Collection<string,PermissionDto>|null keyed by slug */
    private ?Collection $flat = null;

    /** @var array<string,Collection<int,string>> keyed by surface class and value */
    private array $offerings = [];

    public function __construct(
        private readonly PermissionCollection $permissions,
    ) {}

    /**
     * Name the verbs of your own permissions — return `null` to fall through to the package's
     * translations, and from there to the enum case's name.
     *
     * @param  (Closure(PermissionDto): ?string)|null  $resolver
     */
    public static function resolveActionLabelsUsing(?Closure $resolver): void
    {
        static::$actionLabelResolver = $resolver;
    }

    /**
     * The catalogue, optionally narrowed to a surface and to a search term.
     *
     * A term is matched against the group, the subject and every permission under it, but
     * selection happens at SUBJECT granularity: matching one verb yields the whole subject, so the
     * surviving row still offers the full set of verbs rather than the single one that matched.
     *
     * The surface narrows what the catalogue CONTAINS, the term narrows what is shown of it — so
     * the surface is applied first and the term searches only what may actually be handed out.
     *
     * @return Collection<string,PermissionGroupDto>
     */
    public function groups(?string $search = null, ?PermissionSurfaceDefinition $surface = null): Collection
    {
        $groups = $this->catalogue ??= $this->permissions->getGroupedPermissions()
            ->sortBy(fn (PermissionGroupDto $group): string => $group->name, SORT_NATURAL | SORT_FLAG_CASE);

        if ($surface instanceof PermissionSurfaceDefinition) {
            $groups = $this->narrowToSurface($groups, $surface);
        }

        if (blank($search)) {
            return $groups->map(fn (PermissionGroupDto $group): PermissionGroupDto => $this->sortSubjects($group));
        }

        $needle = $this->normalise($search);

        return $groups
            ->map(function (PermissionGroupDto $group) use ($needle): PermissionGroupDto {
                $groupMatches = str_contains($this->normalise($group->name), $needle);

                return $this->sortSubjects($group, $group->subjects->filter(
                    fn (PermissionSubjectDto $subject): bool => $groupMatches || $this->subjectMatches($subject, $needle),
                ));
            })
            ->reject(fn (PermissionGroupDto $group): bool => $group->subjects->isEmpty());
    }

    /**
     * Every permission slug the catalogue knows, for validating what a screen sends back.
     *
     * @return Collection<int,string>
     */
    public function slugs(): Collection
    {
        return $this->flatten()->keys();
    }

    /**
     * Every permission of the catalogue, keyed by slug.
     *
     * @return Collection<string,PermissionDto>
     */
    public function permissions(): Collection
    {
        return $this->flatten();
    }

    public function find(string $slug): ?PermissionDto
    {
        return $this->flatten()->get($slug);
    }

    /**
     * A permission's full name — or, for one nobody registered (a rule may point at it), its value,
     * which is what is granted anyway.
     */
    public function nameOf(PermissionDefinition $permission): string
    {
        return $this->find((string) $permission->value)->name ?? (string) $permission->value;
    }

    /**
     * A condition's label: its own description, or its class name made readable.
     */
    public function describeCondition(PermissionCondition $condition): string
    {
        return $condition instanceof DescribesPermissionCondition
            ? $condition->describe()
            : Str::headline(class_basename($condition));
    }

    /**
     * Every permission's full name, keyed by slug.
     *
     * The full NAME, not the verb {@see self::actionLabel()} renders: that one answers "what does
     * this row do" underneath a subject heading which already says what it does it TO. A caller
     * listing bare slugs has no such heading, so it needs the name that stands on its own.
     *
     * @return Collection<string,string>
     */
    public function names(): Collection
    {
        return $this->flatten()->map(fn (PermissionDto $permission): string => $permission->name);
    }

    /**
     * The slugs one surface OFFERS — the catalogue narrowed, or the whole of it.
     *
     * A surface that offers everything answers "was anything withheld from me?" with "no", and
     * reads the whole catalogue; any other surface answers "what was declared for me?", and reads
     * the declarations.
     *
     * @return Collection<int,string>
     */
    public function slugsAvailableOn(PermissionSurfaceDefinition $surface): Collection
    {
        return $this->offerings[$surface::class . '::' . $surface->value] ??= $this->offersEverything($surface)
            ? $this->slugs()
            : $this->permissions->availableOn($surface);
    }

    /**
     * The permission's verb, translated.
     *
     * Keyed on the enum CASE rather than on the slug: the slug's shape is each module's own
     * convention, so trimming it would mislabel whichever module spells its slugs differently.
     *
     * An unknown verb falls back to its case name rather than rendering a raw translation key — a
     * new case must stay readable before someone gets round to naming it.
     */
    public function actionLabel(PermissionDto $permission): string
    {
        if (static::$actionLabelResolver instanceof Closure) {
            $label = (static::$actionLabelResolver)($permission);

            if (filled($label)) {
                return $label;
            }
        }

        $key = 'filament-access-control::permissions.actions.' . Str::snake($permission->enum->name);

        return Lang::has($key) ? (string) __($key) : Str::headline($permission->enum->name);
    }

    /**
     * Fold case and strip diacritics so that "utylizacje" finds "Utylizacje" and "zwroty" finds
     * "Zwroty" typed without Polish characters.
     */
    public function normalise(string $value): string
    {
        return Str::lower(Str::ascii($value));
    }

    protected function offersEverything(PermissionSurfaceDefinition $surface): bool
    {
        return $surface instanceof OffersEveryPermission && $surface->offersEverything();
    }

    /**
     * The catalogue as one surface sees it: nothing but the slugs that surface offers.
     *
     * Copies at every level rather than editing in place: the DTOs come from a shared build and a
     * view filtered for one caller must not be visible to the next. `$children` (flat) and
     * `$subjects` (nested) are cut by the SAME predicate — two spellings of one set, and letting
     * them drift would give the screen a search that finds rows nobody can click. Emptied subjects
     * and groups are dropped: a heading over nothing reads as a permission the operator failed to
     * find rather than one nobody is offered.
     *
     * @param  Collection<string,PermissionGroupDto>  $groups
     * @return Collection<string,PermissionGroupDto>
     */
    private function narrowToSurface(Collection $groups, PermissionSurfaceDefinition $surface): Collection
    {
        if ($this->offersEverything($surface)) {
            return $groups;
        }

        $offered = $this->slugsAvailableOn($surface)->flip();

        $offers = fn (PermissionDto $permission): bool => $offered->has($permission->slug);

        return $groups
            ->map(fn (PermissionGroupDto $group): PermissionGroupDto => new PermissionGroupDto(
                name: $group->name,
                slug: $group->slug,
                children: $group->children->filter($offers)->values(),
                description: $group->description,
                subjects: $group->subjects
                    ->map(fn (PermissionSubjectDto $subject): PermissionSubjectDto => new PermissionSubjectDto(
                        name: $subject->name,
                        slug: $subject->slug,
                        enum: $subject->enum,
                        children: $subject->children->filter($offers)->values(),
                        description: $subject->description,
                    ))
                    ->reject(fn (PermissionSubjectDto $subject): bool => $subject->children->isEmpty()),
            ))
            ->reject(fn (PermissionGroupDto $group): bool => $group->subjects->isEmpty());
    }

    /**
     * Every permission in the catalogue, keyed by slug — off the memoised build rather than off a
     * fresh one, which `PermissionCollection::getPermissions()` would make.
     *
     * @return Collection<string,PermissionDto>
     */
    private function flatten(): Collection
    {
        return $this->flat ??= $this->groups()
            ->flatMap(fn (PermissionGroupDto $group): Collection => $group->children)
            ->keyBy(fn (PermissionDto $permission): string => $permission->slug);
    }

    private function subjectMatches(PermissionSubjectDto $subject, string $needle): bool
    {
        if (str_contains($this->normalise($subject->name), $needle) || str_contains($subject->slug, $needle)) {
            return true;
        }

        return $subject->children->contains(fn (PermissionDto $permission): bool => str_contains($this->normalise($permission->name), $needle)
            || str_contains($this->normalise($this->actionLabel($permission)), $needle)
            || str_contains($this->normalise($permission->slug), $needle));
    }

    /**
     * @param  Collection<array-key,PermissionSubjectDto>|null  $subjects
     */
    private function sortSubjects(PermissionGroupDto $group, ?Collection $subjects = null): PermissionGroupDto
    {
        // A copy, not a mutation: the group DTOs come from a shared build and a filtered view of
        // them must not be visible to the next caller.
        return new PermissionGroupDto(
            name: $group->name,
            slug: $group->slug,
            children: $group->children,
            description: $group->description,
            subjects: ($subjects ?? $group->subjects)
                ->sortBy(fn (PermissionSubjectDto $subject): string => $subject->name, SORT_NATURAL | SORT_FLAG_CASE),
        );
    }
}
