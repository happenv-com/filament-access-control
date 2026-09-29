<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

/**
 * One badge next to a permission's name: a rule from this permission's side, or a condition. Filament
 * reads its label and colour itself; the reason goes into the tooltip.
 */
final readonly class DependencyBadge implements HasColor, HasLabel
{
    public function __construct(
        public string $label,
        public string $color,
        /** Why the rule exists, as declared — null when nobody said. */
        public ?string $reason = null,
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getColor(): string
    {
        return $this->color;
    }
}
