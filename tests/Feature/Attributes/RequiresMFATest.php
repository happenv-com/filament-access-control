<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelRegistry;
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\LaravelAccessControl\GateConfigurator;
use Illuminate\Auth\GenericUser;
use Illuminate\Support\Facades\Gate;

covers(RequiresMFA::class);

const MFA_SECRET = 'JBSWY3DPEHPK3PXP';

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');
});

it('is met by an account with multi-factor authentication enabled on the panel', function (): void {
    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('is not met by an account without it', function (): void {
    expect((new RequiresMFA)->check(GalleryPermission::Publish, createUser()))->toBeFalse();
});

it('is not met by an account the providers cannot ask — an API key, say', function (): void {
    // Filament's app authentication throws for a model without its contract.
    expect((new RequiresMFA)->check(GalleryPermission::Publish, new GenericUser(['id' => 1])))->toBeFalse();
});

it('is not met on a panel without multi-factor authentication', function (): void {
    // Filament::registerPanel() defers to the panel registry's NEXT resolution, which has already
    // happened by the time a test runs — the registry itself takes an ad hoc panel immediately.
    resolve(PanelRegistry::class)->register(Panel::make()->id('plain')->path('plain'));

    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA(panel: 'plain'))->check(GalleryPermission::Publish, $user))->toBeFalse();

    Filament::setCurrentPanel('plain');

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeFalse()
        ->and((new RequiresMFA(panel: 'admin'))->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('is not met on a panel that does not exist', function (): void {
    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA(panel: 'missing'))->check(GalleryPermission::Publish, $user))->toBeFalse();
});

it('asks the default panel outside of any', function (): void {
    Filament::setCurrentPanel(null);

    $user = createUser();
    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect((new RequiresMFA)->check(GalleryPermission::Publish, $user))->toBeTrue();
});

it('names itself for the screens', function (): void {
    expect((new RequiresMFA)->describe())->toBe('Requires MFA');
});

it('withholds its permission from an account without MFA at the gate and in hasPermissionTo()', function (): void {
    registerPermissions(GalleryPermission::class);
    resolve(GateConfigurator::class)->configure();

    $user = createUser(permissions: [GalleryPermission::Publish->value]);

    expect($user->hasPermissionTo(GalleryPermission::Publish))->toBeFalse()
        ->and(Gate::forUser($user)->allows(GalleryPermission::Publish))->toBeFalse();

    $user->saveAppAuthenticationSecret(MFA_SECRET);

    expect($user->hasPermissionTo(GalleryPermission::Publish))->toBeTrue()
        ->and(Gate::forUser($user)->allows(GalleryPermission::Publish))->toBeTrue();
});
