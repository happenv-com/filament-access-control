<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Contracts;

/**
 * A permission surface that can say it offers the WHOLE catalogue.
 *
 * laravel-access-control only records what a permission declares with `#[AvailableFor]`, and that
 * is the right answer for a machine surface: an offer there is a credential somebody can mint.
 * The panel is different — it is where people are granted anything at all, and a permission the
 * role screen does not draw could never be granted to anyone. A surface enum implements this to
 * say which of the two it is; one that does not is read from its declarations.
 */
interface OffersEveryPermission
{
    public function offersEverything(): bool;
}
