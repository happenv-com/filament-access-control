<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Schemas\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Components\Livewire;
use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
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

    protected PermissionSurfaceDefinition | Closure | null $surface = null;

    protected string | BackedEnum | Closure | false | null $ability = false;

    protected bool | Closure $showsInheritedPermissions = true;

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

        $this->hidden(static fn (PermissionEditor $component): bool => ! $component->hasEditableRecord());
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
     * @return array<string, mixed>
     */
    public function getComponentProperties(): array
    {
        return [
            'deferred' => $this->isDeferred(),
            'surface' => $this->getSurface(),
            'ability' => $this->getAbility(),
            'readOnly' => $this->isDisabled(),
            'showInherited' => $this->showsInheritedPermissions(),
            ...parent::getComponentProperties(),
        ];
    }
}
