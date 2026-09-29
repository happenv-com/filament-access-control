<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Filament\Support\Icons\Heroicon;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;

/**
 * One cell of a permission screen: its {@see PermissionCellState}, whether it shows a change not
 * saved yet, and every reason behind it — the icon shows the first, the tooltip lists them all.
 */
final readonly class PermissionCell
{
    /**
     * @param  list<string>  $reasons
     */
    public function __construct(
        public PermissionCellState $state,
        public bool $staged = false,
        public array $reasons = [],
    ) {}

    public static function of(PermissionResolutionDto $resolution, PermissionTree $tree, bool $staged = false): self
    {
        $reasons = [];

        if (! $resolution->stored && $resolution->grantedBy !== []) {
            $reasons[] = __('filament-access-control::editor.cells.implied_by', ['permissions' => self::names($resolution->grantedBy, $tree)]);
        }

        // What withholds a permission matters only for one that is granted.
        if ($resolution->granted) {
            if ($resolution->missing !== []) {
                $reasons[] = __('filament-access-control::editor.cells.missing', ['permissions' => self::names($resolution->missing, $tree)]);
            }

            if ($resolution->conflicting !== []) {
                $reasons[] = __('filament-access-control::editor.cells.blocked_by', ['permissions' => self::names($resolution->conflicting, $tree)]);
            }

            if ($resolution->restricted) {
                $reasons[] = __('filament-access-control::editor.cells.restricted');
            }

            foreach ($resolution->unmetConditions as $condition) {
                $reasons[] = __('filament-access-control::editor.cells.unmet_condition', ['condition' => $tree->describeCondition($condition)]);
            }
        }

        return new self(PermissionCellState::of($resolution), $staged, $reasons);
    }

    public function withReason(string $reason): self
    {
        return new self($this->state, $this->staged, [...$this->reasons, $reason]);
    }

    public function icon(): Heroicon
    {
        return $this->state->icon();
    }

    /**
     * A staged cell takes the primary colour until it is saved — `warning` already means a missing
     * requirement.
     */
    public function color(): string
    {
        return $this->staged ? 'primary' : $this->state->color();
    }

    public function tooltip(): ?string
    {
        $lines = $this->staged
            ? [__('filament-access-control::editor.staged_marker'), ...$this->reasons]
            : $this->reasons;

        return $lines === [] ? null : implode(' · ', $lines);
    }

    /**
     * @param  list<PermissionDefinition>  $permissions
     */
    private static function names(array $permissions, PermissionTree $tree): string
    {
        return implode(', ', array_map($tree->nameOf(...), $permissions));
    }
}
