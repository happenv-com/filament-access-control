<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Illuminate\Contracts\Support\Htmlable;

/**
 * A short note under one holder's cell of a permission row — what the application has to say about
 * that grant beyond granted or not (how far a data scope reaches, say). See
 * {@see FilamentAccessControlPlugin::holderCellNotes()}.
 *
 * Clicking it mounts the plugin action named by `action` — one registered through
 * {@see FilamentAccessControlPlugin::actions()} — with `arguments` and the cell's `holder` and
 * `permission`. Without an action, or on a screen that edits nothing, it is plain text.
 */
final readonly class CellNote
{
    /**
     * @param  string  $color  a Filament colour name
     * @param  array<string, mixed>  $arguments
     */
    public function __construct(
        public string | Htmlable $label,
        public string $color = 'gray',
        public ?string $tooltip = null,
        public ?string $action = null,
        public array $arguments = [],
    ) {}
}
