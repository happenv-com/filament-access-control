<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\View;

/**
 * Icon aliases this package resolves, so a host can re-icon the permission editor globally instead
 * of decorating every column and callout. Register them exactly as Filament's own:
 *
 * ```php
 * use Filament\Support\Facades\FilamentIcon;
 * use Happenv\FilamentAccessControl\View\AccessControlIconAlias;
 *
 * FilamentIcon::register([
 *     AccessControlIconAlias::CELL_RESTRICTED => Phosphor::Lock,
 * ]);
 * ```
 *
 * The string values are part of the public contract — a host registers against them — so they may
 * not be renamed without a breaking release. They share the package's translation namespace, which
 * is what keeps them from colliding with another package's aliases.
 *
 * Without a registered alias every icon stays a Heroicon. The navigation icon is the exception that
 * proves the rule: `FilamentAccessControlPlugin::navigationIcon()` still wins over its alias.
 */
class AccessControlIconAlias
{
    /** The page's entry in the panel navigation. */
    const NAVIGATION = 'filament-access-control::navigation';

    /** A granted permission: in effect, implied by another, or (in the matrix) a fully granted row. */
    const CELL_GRANTED = 'filament-access-control::cell.granted';

    /** A subject's permissions are granted in part (a half-filled row). */
    const CELL_PARTIAL = 'filament-access-control::cell.partial';

    /** Not granted: nothing grants it, or it is granted but not in effect for an unnamed reason. */
    const CELL_NOT_GRANTED = 'filament-access-control::cell.not-granted';

    /** Granted, but a permission it requires is missing. */
    const CELL_MISSING_REQUIREMENT = 'filament-access-control::cell.missing-requirement';

    /** Granted, but a conflicting permission is held. */
    const CELL_CONFLICT = 'filament-access-control::cell.conflict';

    /** Held back by a restriction (e.g. a read-only mode); also marks a restricted permission row. */
    const CELL_RESTRICTED = 'filament-access-control::cell.restricted';

    /** Granted, but a condition attached to it is not met. */
    const CELL_UNMET_CONDITION = 'filament-access-control::cell.unmet-condition';

    /** Chevron on a folded group row. */
    const GROUP_COLLAPSED = 'filament-access-control::group.collapsed';

    /** Chevron on an opened group row. */
    const GROUP_EXPANDED = 'filament-access-control::group.expanded';

    /** The "Roles" filter trigger of the matrix. */
    const ACTIONS_ROLE_PICKER = 'filament-access-control::actions.role-picker';

    /** The "Save changes" action of a deferred editor. */
    const ACTIONS_SAVE = 'filament-access-control::actions.save';

    /** The table's empty state. */
    const EMPTY_STATE = 'filament-access-control::empty-state';

    /** Marks a permission inherited from a role. */
    const INHERITED = 'filament-access-control::inherited';

    /** The search field of the permission selector. */
    const SELECTOR_SEARCH = 'filament-access-control::selector.search';

    /** The disclosure chevron of a group in the permission selector. */
    const SELECTOR_GROUP_TOGGLE = 'filament-access-control::selector.group-toggle';

    /** Callout: the holder is a super admin and cannot be edited. */
    const CALLOUT_LOCKED = 'filament-access-control::callout.locked';

    /** Callout: the holder inherits super-admin rights from a role. */
    const CALLOUT_SUPER_ADMIN = 'filament-access-control::callout.super-admin';

    /** Callout: the signed-in user's own record is guarded. */
    const CALLOUT_OWN_RECORD = 'filament-access-control::callout.own-record';

    /** Callout: the editor is read-only for this viewer. */
    const CALLOUT_READ_ONLY = 'filament-access-control::callout.read-only';

    /** Callout: some granted permissions have unmet conditions. */
    const CALLOUT_UNMET_CONDITIONS = 'filament-access-control::callout.unmet-conditions';

    /** Callout: the permission declarations have problems. */
    const CALLOUT_PROBLEMS = 'filament-access-control::callout.problems';
}
