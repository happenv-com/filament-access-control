<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Support;

use Throwable;

/**
 * The sentence an OPERATOR is shown when authorisation refuses.
 *
 * Two very different things arrive here wearing the same type. A VOTER's refusal is prose somebody
 * wrote for this exact situation — "This role is assigned to users and cannot be deleted." — and
 * throwing it away would take the only explanation the screen has. The GATE's refusal is a default:
 * with `access-control.display_permission_in_exception` on it reads `Unauthorized for
 * core.role.delete`, which is English, names an internal slug, and says nothing an operator can act
 * on. So the gate's default is TRANSLATED rather than suppressed: the slug it carries is resolved
 * through the catalogue into the permission's own name.
 *
 * {@see self::GATE_PREFIX} is the wording `Happenv\LaravelAccessControl\GateConfigurator` produces.
 * Reading a message's SHAPE is coupling, and it is deliberate: `RefusalLeadTest` asserts the prefix
 * against a real refusal from the real gate, so the day the library rewords it, the suite goes red
 * rather than silently falling back to prose nobody wanted.
 */
class RefusalLead
{
    /** What `GateConfigurator` prefixes a missing-permission refusal with, when the option is on. */
    public const string GATE_PREFIX = 'Unauthorized for ';

    /** What `GateConfigurator` refuses with when the option is off, and Laravel when it has no reason. */
    public const string GATE_DEFAULT = 'Unauthorized.';

    public function __construct(private readonly PermissionTree $permissions) {}

    /**
     * The lead to render, or `null` when the caller should use its own default.
     *
     * `null` rather than a default of its own: an error page and a notification word their
     * fallbacks differently, and a shared "no access" string would be wrong on at least one of them.
     */
    public function for(?Throwable $exception): ?string
    {
        return $this->fromMessage($exception?->getMessage());
    }

    /**
     * The same question asked of a bare message, for callers holding a `Response` rather than a
     * thrown exception — `Gate::inspect()` hands back the former.
     */
    public function fromMessage(?string $message): ?string
    {
        // The gate's bare refusal says nothing a caller's own, translated default would not say
        // better — and in the operator's language.
        if (in_array($message, [null, '', self::GATE_DEFAULT], true)) {
            return null;
        }

        if (! str_starts_with($message, self::GATE_PREFIX)) {
            // A voter's own words — not ours to rewrite.
            return $message;
        }

        $slug = substr($message, strlen(self::GATE_PREFIX));
        $name = $this->permissions->names()->get($slug);

        // A slug the catalogue does not know is a module left out of this build, or a typo in an
        // ability. Either way the raw slug is more use to whoever reads the report than a sentence
        // naming nothing.
        return is_string($name)
            ? (string) __('filament-access-control::permissions.missing_permission', ['permission' => $name])
            : $message;
    }
}
