<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use BackedEnum;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;
use Happenv\FilamentAccessControl\View\AccessControlIconAlias;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;
use Illuminate\Contracts\Support\Htmlable;

/**
 * What a permission's cell shows — read off the library's resolution, never decided here: the
 * package presents what laravel-access-control resolved.
 */
enum PermissionCellState: string
{
    case Effective = 'effective';
    case Implied = 'implied';
    case MissingRequirement = 'missing-requirement';
    case Conflict = 'conflict';
    case Restricted = 'restricted';
    case UnmetCondition = 'unmet-condition';
    case NotGranted = 'not-granted';

    /**
     * The first reason that applies, in the order an operator can act on them — grant what is
     * missing, resolve a conflict — before what the application decides at runtime.
     */
    public static function of(PermissionResolutionDto $resolution): self
    {
        return match (true) {
            ! $resolution->granted => self::NotGranted,
            $resolution->effective => $resolution->stored ? self::Effective : self::Implied,
            $resolution->missing !== [] => self::MissingRequirement,
            $resolution->conflicting !== [] => self::Conflict,
            $resolution->restricted => self::Restricted,
            $resolution->unmetConditions !== [] => self::UnmetCondition,
            // Granted, not in effect and no reason given — nothing the library resolves ends here.
            default => self::NotGranted,
        };
    }

    /**
     * The shape says WHY; the tooltip names what is involved.
     */
    public function icon(): string | BackedEnum | Htmlable
    {
        return match ($this) {
            self::Effective, self::Implied => FilamentIcon::resolve(AccessControlIconAlias::CELL_GRANTED) ?? Heroicon::CheckCircle,
            self::MissingRequirement => FilamentIcon::resolve(AccessControlIconAlias::CELL_MISSING_REQUIREMENT) ?? Heroicon::ExclamationTriangle,
            self::Conflict => FilamentIcon::resolve(AccessControlIconAlias::CELL_CONFLICT) ?? Heroicon::NoSymbol,
            self::Restricted => FilamentIcon::resolve(AccessControlIconAlias::CELL_RESTRICTED) ?? Heroicon::LockClosed,
            self::UnmetCondition => FilamentIcon::resolve(AccessControlIconAlias::CELL_UNMET_CONDITION) ?? Heroicon::ShieldExclamation,
            self::NotGranted => FilamentIcon::resolve(AccessControlIconAlias::CELL_NOT_GRANTED) ?? Heroicon::XCircle,
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Effective => 'success',
            self::Implied => 'info',
            self::MissingRequirement, self::UnmetCondition => 'warning',
            self::Conflict, self::NotGranted => 'danger',
            self::Restricted => 'gray',
        };
    }
}
