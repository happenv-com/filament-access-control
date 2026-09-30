<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Pages;

use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\Authorization;
use Illuminate\Contracts\Support\Htmlable;
use UnitEnum;

/**
 * Which role may do what — every role, every permission, one grid.
 *
 * Registered by the plugin once it knows the role model. Gated on the plugin's `viewAny` role
 * ability rather than on anything it would inherit, because what this screen does is rewrite roles.
 * Extend it and hand the class to `accessControlPage()` to change more than the plugin exposes.
 */
class AccessControl extends Page
{
    protected string $view = 'filament-access-control::pages.access-control';

    public static function canAccess(): bool
    {
        $plugin = static::plugin();
        $model = $plugin->getRoleModel();

        return $model !== null && Authorization::allows($plugin->getRoleAbility('viewAny'), model: $model);
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-access-control::access-control.title');
    }

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel
            ?? static::plugin()->getNavigationLabel()
            ?? __('filament-access-control::access-control.navigation_label');
    }

    public static function getNavigationGroup(): string | UnitEnum | null
    {
        return static::$navigationGroup ?? static::plugin()->getNavigationGroup();
    }

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return static::$navigationIcon ?? static::plugin()->getNavigationIcon();
    }

    public static function getNavigationSort(): ?int
    {
        return static::$navigationSort ?? static::plugin()->getNavigationSort();
    }

    public static function getDefaultSlug(): string
    {
        return filled(static::$slug) ? static::$slug : (static::plugin()->getSlug() ?? 'access-control');
    }

    public static function getCluster(): ?string
    {
        return static::$cluster ?? static::plugin()->getCluster();
    }

    public function isDeferred(): bool
    {
        return static::plugin()->isDeferred();
    }

    /**
     * @return array<string, mixed>
     */
    public function getMatrixProperties(): array
    {
        return [
            'deferred' => $this->isDeferred(),
            'counters' => static::plugin()->hasCounters(),
            'rolesShownByDefault' => static::plugin()->getRolesShownByDefault(),
        ];
    }

    protected function getHeaderActions(): array
    {
        $plugin = static::plugin();
        $model = $plugin->getRoleModel();

        $action = CreateAction::make('createRole')
            ->label(__('filament-access-control::access-control.actions.create_role.label'))
            ->modalHeading(__('filament-access-control::access-control.actions.create_role.heading'))
            ->model($model)
            ->schema([
                TextInput::make('name')
                    ->label(__('filament-access-control::access-control.fields.name'))
                    ->required()
                    ->maxLength(255),
            ])
            // The page lets in whoever may SEE roles; creating one is an ability of its own.
            ->authorize(static fn (): bool => $model !== null
                && Authorization::allows($plugin->getRoleAbility('create'), model: $model))
            ->createAnother(false)
            ->modalWidth(Width::Medium)
            ->after(fn () => $this->dispatch(RolePermissionMatrix::ROLES_CHANGED));

        return [$plugin->configureCreateRoleAction($action)];
    }

    protected static function plugin(): FilamentAccessControlPlugin
    {
        return FilamentAccessControlPlugin::current();
    }
}
