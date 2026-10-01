<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Forms\Components;

use Closure;
use Filament\Facades\Filament;
use Filament\Forms\Components\Field;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Happenv\FilamentAccessControl\Support\GrantGuard;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionGroupDto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Picks the permissions a record holds DIRECTLY, as a flat list of slugs — a form FIELD, saved by
 * the form's own Save like any other.
 *
 * Where {@see PermissionEditor} writes on its
 * own, this one is state: fit for a create form (there is no record to write to yet) and for any
 * form whose permissions must be saved together with the rest of it.
 *
 * The state is the list itself, not a slug-keyed map of booleans. Permission slugs contain dots and
 * `$wire.$entangle` reads a dot as nesting, so a map would arrive in PHP as a tree that only looks
 * flat because both sides split it the same way.
 */
class PermissionSelector extends Field
{
    protected string $view = 'filament-access-control::forms.components.permission-selector';

    /**
     * The surface whose offering this field shows, or null for the whole catalogue.
     *
     * The default is DELIBERATELY the whole catalogue: a new usage that forgets to name a surface
     * should be noisy rather than quietly narrowed.
     */
    protected PermissionSurfaceDefinition | Closure | null $surface = null;

    /**
     * What the operator may grant and revoke, for the life of one request — `null` for anything.
     * Asked of {@see GrantGuard} once: the view asks for every row.
     *
     * @var Collection<int, string>|null
     */
    protected ?Collection $grantableSlugs = null;

    protected bool $grantableSlugsResolved = false;

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpanFull();

        $this->hiddenLabel();

        $this->default([]);

        $this->afterStateHydrated(static function (PermissionSelector $component): void {
            $component->state($component->sanitise($component->getState()));
        });

        $this->dehydrateStateUsing(static fn (PermissionSelector $component, mixed $state): array => $component->merge($state));

        $this->rule(static fn (PermissionSelector $component): Closure => static function (string $attribute, mixed $value, Closure $fail) use ($component): void {
            $sanitised = new Collection($component->sanitise($value));

            $known = $component->getTree()->slugs();
            $offered = $component->offeredSlugs();
            $heldOutside = $component->heldOutsideOffering();
            $unknownHeld = $component->unknownHeldSlugs();

            $unknown = $sanitised->diff($known)->diff($unknownHeld);

            if ($unknown->isNotEmpty()) {
                $fail(__('filament-access-control::permission-selector.validation.unknown', [
                    'permissions' => $unknown->join(', '),
                ]));
            }

            // A slug the catalogue knows perfectly well, which this surface simply does not offer and
            // the record does not already hold. Reported in its own words: calling it "unknown" would
            // be a lie, and dropping it silently would hide an attempt to widen a credential.
            $notOffered = $sanitised
                ->intersect($known)
                ->diff($offered)
                ->diff($heldOutside);

            if ($notOffered->isNotEmpty()) {
                $fail(__('filament-access-control::permission-selector.validation.not_offered', [
                    'permissions' => $notOffered->join(', '),
                ]));
            }

            // What this save would CHANGE — granted or revoked. Left as it is, a permission is nobody's
            // decision, so the guards have nothing to say about it.
            $held = $component->heldSlugs();
            $changed = $sanitised->diff($held)->merge($held->diff($sanitised))->intersect($known)->values();

            if ($changed->isEmpty()) {
                return;
            }

            if ($component->isOwnRecord()) {
                $fail(__('filament-access-control::permission-selector.validation.own_record'));

                return;
            }

            $notGrantable = $changed->reject(fn (string $slug): bool => $component->mayChange($slug));

            if ($notGrantable->isNotEmpty()) {
                $fail(__('filament-access-control::permission-selector.validation.not_grantable', [
                    'permissions' => $notGrantable->join(', '),
                ]));
            }
        });
    }

    public function surface(PermissionSurfaceDefinition | Closure | null $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getSurface(): ?PermissionSurfaceDefinition
    {
        return $this->evaluate($this->surface);
    }

    /**
     * The catalogue this field DRAWS — narrowed by the same surface that decides what it may write.
     *
     * @return Collection<string,PermissionGroupDto>
     */
    public function getGroups(): Collection
    {
        return $this->getTree()->groups(surface: $this->getSurface());
    }

    public function getActionLabel(PermissionDto $permission): string
    {
        return $this->getTree()->actionLabel($permission);
    }

    /**
     * A lower-cased, unaccented blob of everything a row can be searched by, so the client-side
     * filter can match without a round trip.
     */
    public function getHaystack(string ...$parts): string
    {
        return Str::lower(Str::ascii(implode(' ', $parts)));
    }

    /**
     * @return array<int,string>
     */
    public function sanitise(mixed $state): array
    {
        return (new Collection(is_array($state) ? $state : []))
            ->filter(fn (mixed $slug): bool => is_string($slug))
            ->unique()
            ->values()
            ->all();
    }

    public function getTree(): PermissionTree
    {
        return resolve(PermissionTree::class);
    }

    /**
     * What this field may OFFER.
     *
     * @return Collection<int,string>
     */
    public function offeredSlugs(): Collection
    {
        $surface = $this->getSurface();

        return $surface instanceof PermissionSurfaceDefinition
            ? $this->getTree()->slugsAvailableOn($surface)
            : $this->getTree()->slugs();
    }

    /**
     * What the record holds that this deployment cannot show.
     *
     * A module left out of the build takes its permission enums with it, so its slugs vanish from
     * the catalogue while staying in the column. The field must not delete a grant it merely cannot
     * render — the module coming back would find the record silently stripped.
     *
     * @return Collection<int,string>
     */
    public function unknownHeldSlugs(): Collection
    {
        return $this->heldSlugs()->diff($this->getTree()->slugs())->values();
    }

    /**
     * What the record HOLDS that this surface does not offer — and that this deployment CAN draw.
     *
     * Rendered, revocable, and never silently kept: a grant issued before the field was narrowed
     * is inert today and alive the day the surface starts consulting it.
     *
     * @return Collection<int,string>
     */
    public function heldOutsideOffering(): Collection
    {
        return $this->heldSlugs()
            ->intersect($this->getTree()->slugs())
            ->diff($this->offeredSlugs())
            ->values();
    }

    /**
     * The same grants as {@see self::heldOutsideOffering()}, each under the name the catalogue gives
     * it — slug => name. Read from the UNNARROWED catalogue: these are by definition the slugs no
     * offering covers, so the narrowed one is the one place their names are certain to be missing.
     *
     * @return Collection<string,string>
     */
    public function heldOutsideOfferingNames(): Collection
    {
        $names = $this->getTree()->names();

        return $this->heldOutsideOffering()
            ->mapWithKeys(fn (string $slug): array => [$slug => $names->get($slug, $slug)]);
    }

    /**
     * Whether the operator may grant and revoke this permission — see {@see GrantGuard}.
     */
    public function mayChange(string $slug): bool
    {
        if (! $this->grantableSlugsResolved) {
            $this->grantableSlugs = resolve(GrantGuard::class)->grantableSlugs(Filament::auth()->user());
            $this->grantableSlugsResolved = true;
        }

        return ! $this->grantableSlugs instanceof Collection || $this->grantableSlugs->contains($slug);
    }

    /**
     * Whether the record is the operator themselves, and the plugin keeps operators off their own
     * permissions.
     */
    public function isOwnRecord(): bool
    {
        $record = $this->getRecord();

        return $record instanceof Model
            && FilamentAccessControlPlugin::current()->isSelfEditingPrevented()
            && resolve(GrantGuard::class)->isOwnHolder(Filament::auth()->user(), $record);
    }

    /**
     * @return Collection<int,string>
     */
    protected function heldSlugs(): Collection
    {
        $record = $this->getRecord();

        return $record instanceof HasEditablePermissions ? $record->getPermissions() : new Collection;
    }

    /**
     * The list that actually gets written.
     *
     * Three disjoint cases, three different remedies — and collapsing any two of them is exactly how
     * "hide" turns into "revoke":
     *
     *   - offered            → the operator decides, by ticking or not ticking;
     *   - held, not offered  → the operator may REVOKE it but never re-offer it to somebody else;
     *   - held, unrenderable → kept whatever the form says, because the form never showed it.
     *
     * Anything else the state carries is dropped: a slug that is neither offered nor already held
     * can only have come from a hand-made request.
     *
     * @return array<int,string>
     */
    protected function merge(mixed $state): array
    {
        $held = $this->heldSlugs();

        // The guards, again, after the validation that already refused: a list that reached here
        // some other way still changes nothing it may not.
        if ($this->isOwnRecord()) {
            return $held->values()->all();
        }

        return (new Collection($this->sanitise($state)))
            ->intersect($this->offeredSlugs()->merge($this->heldOutsideOffering()))
            ->filter(fn (string $slug): bool => $this->mayChange($slug) || $held->contains($slug))
            ->merge($held->reject(fn (string $slug): bool => $this->mayChange($slug)))
            ->merge($this->unknownHeldSlugs())
            ->unique()
            ->values()
            ->all();
    }
}
