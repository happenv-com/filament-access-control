<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Attributes;

use Attribute;
use Filament\Auth\MultiFactor\Contracts\MultiFactorAuthenticationProvider;
use Filament\Facades\Filament;
use Filament\Panel;
use Happenv\LaravelAccessControl\Contracts\DescribesPermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionCondition;
use Happenv\LaravelAccessControl\Contracts\PermissionDefinition;
use Illuminate\Contracts\Auth\Authenticatable;
use Throwable;

/**
 * Withholds a permission from an account that has no multi-factor authentication enabled — placed on
 * a permission enum (every case) or on one case.
 *
 * Met when at least one multi-factor provider of the panel reports it enabled for the account: the
 * panel named here, otherwise the current one, otherwise the default one. It fails CLOSED — no panel,
 * a panel without multi-factor authentication, or an account the providers cannot even ask (an API
 * key, a machine user) does not meet it.
 *
 * Free of side effects, as laravel-access-control requires of a condition: a provider's
 * `isEnabled()` reads the account's own attributes.
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_CLASS_CONSTANT)]
final readonly class RequiresMFA implements DescribesPermissionCondition, PermissionCondition
{
    public function __construct(
        /** The id of the panel whose providers count — the current panel, or the default one, when null. */
        public ?string $panel = null,
    ) {}

    public function check(PermissionDefinition $permission, Authenticatable $principal): bool
    {
        foreach ($this->providers() as $provider) {
            try {
                if ($provider->isEnabled($principal)) {
                    return true;
                }
            } catch (Throwable) {
                // A provider that cannot ask this account — Filament's app authentication throws for
                // a model without its contract — has enabled nothing for it.
            }
        }

        return false;
    }

    public function describe(): string
    {
        return __('filament-access-control::editor.conditions.requires_mfa');
    }

    /**
     * @return array<MultiFactorAuthenticationProvider>
     */
    private function providers(): array
    {
        try {
            $panel = $this->panel === null ? Filament::getCurrentOrDefaultPanel() : Filament::getPanel($this->panel);
        } catch (Throwable) {
            // No panel to ask: nothing can vouch for the account.
            return [];
        }

        return $panel instanceof Panel ? $panel->getMultiFactorAuthenticationProviders() : [];
    }
}
