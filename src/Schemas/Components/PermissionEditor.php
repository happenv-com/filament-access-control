<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Schemas\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Components\Livewire;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\LaravelAccessControl\Contracts\AuthControllable;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;
use Illuminate\Database\Eloquent\Model;

/**
 * The permissions of the schema's record — a role, or a user — wherever a schema goes: a section of
 * the edit form, a tab, an infolist, a page of its own.
 *
 * It saves on its own, independently of the form around it: every click at once, or — when
 * {@see self::deferred()} — on its own Save button. The form's Save never touches the permissions,
 * so the two cannot overwrite each other.
 *
 * Hidden while the schema has no saved record (a create page), since there is nothing yet to hand
 * permissions to. `disabled()` — or a disabled schema, like a view page's — makes it read-only.
 */
class PermissionEditor extends Livewire
{
    protected bool | Closure | null $isDeferred = null;

    protected bool | Closure | null $hasCounters = null;

    protected PermissionSurfaceDefinition | Closure | null $surface = null;

    protected string | BackedEnum | Closure | false | null $ability = false;

    protected bool | Closure $showsInheritedPermissions = true;

    protected bool | Closure $showsDirectGrants = true;

    /**
     * @param  array<string, mixed>|Closure  $data
     */
    public static function make(string | Closure $component = RecordPermissions::class, array | Closure $data = []): static
    {
        return parent::make($component, $data);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpanFull();

        $this->hidden(static fn (PermissionEditor $component): bool => ! $component->hasPermissionsRecord());
    }

    /**
     * Collect changes until the operator presses Save, instead of writing every click at once.
     * Unset, the plugin's default applies.
     */
    public function deferred(bool | Closure | null $condition = true): static
    {
        $this->isDeferred = $condition;

        return $this;
    }

    public function isDeferred(): ?bool
    {
        $deferred = $this->evaluate($this->isDeferred);

        return $deferred === null ? null : (bool) $deferred;
    }

    /**
     * Count, in each group's header, what is granted of it. Unset, the plugin's default applies.
     */
    public function counters(bool | Closure | null $condition = true): static
    {
        $this->hasCounters = $condition;

        return $this;
    }

    public function hasCounters(): ?bool
    {
        $counters = $this->evaluate($this->hasCounters);

        return $counters === null ? null : (bool) $counters;
    }

    /**
     * Offer only what this surface offers — an API key's screen, say. What the record already holds
     * outside of it is listed separately, revocable but never grantable again.
     */
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
     * The ability asked, with the record, before its permissions change: a Laravel ability name
     * or a laravel-access-control permission enum; `null` for no check. A closure may choose one —
     * it is evaluated when the component renders and must return one of those, not a verdict.
     *
     * Unset, a role is asked the plugin's `update` role ability and anything else Laravel's
     * `update`, which a model policy answers.
     */
    public function ability(string | BackedEnum | Closure | null $ability): static
    {
        $this->ability = $ability;

        return $this;
    }

    public function getAbility(): string | BackedEnum | false | null
    {
        return $this->ability instanceof Closure ? $this->evaluate($this->ability) : $this->ability;
    }

    /**
     * For a user: show which permissions the user's roles already grant.
     */
    public function showInheritedPermissions(bool | Closure $condition = true): static
    {
        $this->showsInheritedPermissions = $condition;

        return $this;
    }

    public function showsInheritedPermissions(): bool
    {
        return (bool) $this->evaluate($this->showsInheritedPermissions);
    }

    /**
     * For a user: show the column of the permissions it holds directly — on by default. Off, for an
     * application that grants through roles only, the editor shows what the user's roles grant and
     * what is in effect, and changes nothing.
     */
    public function showDirectGrants(bool | Closure $condition = true): static
    {
        $this->showsDirectGrants = $condition;

        return $this;
    }

    public function showsDirectGrants(): bool
    {
        return (bool) $this->evaluate($this->showsDirectGrants);
    }

    /**
     * The record whose permissions are edited: the schema's own, unless `data` names another.
     */
    public function getPermissionsRecord(): ?Model
    {
        $record = $this->getData()['record'] ?? $this->getRecord();

        return $record instanceof Model ? $record : null;
    }

    public function hasEditableRecord(): bool
    {
        $record = $this->getPermissionsRecord();

        return $record instanceof HasEditablePermissions && $record->exists;
    }

    /**
     * Whether there is a record to show: one the editor can write to — or, read-only, any saved
     * account laravel-access-control can answer for.
     */
    public function hasPermissionsRecord(): bool
    {
        if ($this->hasEditableRecord()) {
            return true;
        }

        $record = $this->getPermissionsRecord();

        return $record instanceof AuthControllable && $record->exists && $this->isDisabled();
    }

    /**
     * @return array<string, mixed>
     */
    public function getComponentProperties(): array
    {
        return [
            'deferred' => $this->isDeferred(),
            'counters' => $this->hasCounters(),
            'surface' => $this->getSurface(),
            'ability' => $this->getAbility(),
            'readOnly' => $this->isDisabled(),
            'showInherited' => $this->showsInheritedPermissions(),
            'showDirectGrants' => $this->showsDirectGrants(),
            ...parent::getComponentProperties(),
        ];
    }
}
