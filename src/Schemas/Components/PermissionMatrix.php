<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Schemas\Components;

use Closure;
use Filament\Schemas\Components\Livewire;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\LaravelAccessControl\Contracts\PermissionSurfaceDefinition;

/**
 * The roles × permissions matrix, for a schema of your own — the plugin's access control page
 * draws the same component.
 */
class PermissionMatrix extends Livewire
{
    protected bool | Closure | null $isDeferred = null;

    protected bool | Closure | null $hasCounters = null;

    protected int | Closure | null $rolesShownByDefault = null;

    protected bool | Closure | null $isRolePickerDeferred = null;

    protected PermissionSurfaceDefinition | Closure | null $surface = null;

    /**
     * @param  array<string, mixed>|Closure  $data
     */
    public static function make(string | Closure $component = RolePermissionMatrix::class, array | Closure $data = []): static
    {
        return parent::make($component, $data);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->columnSpanFull();
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
     * How many role columns show until the operator picks others in the role picker. Unset, the
     * plugin's default applies.
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
     * Whether the role picker waits for its Apply button. Unset, the plugin's default applies.
     */
    public function deferRolePicker(bool | Closure | null $condition = true): static
    {
        $this->isRolePickerDeferred = $condition;

        return $this;
    }

    public function isRolePickerDeferred(): ?bool
    {
        $deferred = $this->evaluate($this->isRolePickerDeferred);

        return $deferred === null ? null : (bool) $deferred;
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
     * @return array<string, mixed>
     */
    public function getComponentProperties(): array
    {
        // Not the parent's: it hands the component the schema's record, and the matrix edits every
        // role rather than one record.
        return [
            'deferred' => $this->isDeferred(),
            'counters' => $this->hasCounters(),
            'rolesShownByDefault' => $this->getRolesShownByDefault(),
            'rolePickerDeferred' => $this->isRolePickerDeferred(),
            'surface' => $this->getSurface(),
            ...($this->isLazy() ? ['lazy' => true] : []),
            ...$this->getData(),
        ];
    }
}
