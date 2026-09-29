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
            'surface' => $this->getSurface(),
            ...($this->isLazy() ? ['lazy' => true] : []),
            ...$this->getData(),
        ];
    }
}
