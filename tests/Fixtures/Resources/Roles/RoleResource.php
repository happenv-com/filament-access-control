<?php

declare(strict_types=1);

namespace Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->tabs([
                Tab::make('Role')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('code')->required(),
                ]),
                Tab::make('Permissions')->schema([
                    PermissionEditor::make(),
                ]),
            ])->columnSpanFull(),
        ]);
    }

    public static function canViewAny(): bool
    {
        return true;
    }

    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit($record): bool
    {
        return true;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
