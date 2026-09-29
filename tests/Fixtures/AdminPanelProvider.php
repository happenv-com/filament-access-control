<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures;

use Filament\Actions\CreateAction;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Forms\Components\TextInput;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\RolePermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\RoleResource;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\UserResource;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * A real panel with login, so resources, pages and widgets of the package can
 * be mounted under Livewire and visited over HTTP (and in the browser) in tests.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->multiFactorAuthentication([
                AppAuthentication::make(),
            ])
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                RoleResource::class,
                UserResource::class,
            ])
            ->plugin(
                FilamentAccessControlPlugin::make()
                    ->roleModel(Role::class)
                    ->superAdminRole(Role::ADMINISTRATOR)
                    ->modifyRolesQueryUsing(fn (Builder $query): Builder => $query
                        ->orderByRaw('code = ? desc', [Role::ADMINISTRATOR])
                        ->orderBy('code'))
                    ->roleAbilities(
                        viewAny: RolePermission::View,
                        create: RolePermission::Create,
                        update: RolePermission::Update,
                        delete: RolePermission::Delete,
                    )
                    ->modifyCreateRoleActionUsing(fn (CreateAction $action): CreateAction => $action->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('code')->required(),
                    ])),
            )
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
