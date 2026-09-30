<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentAsset;
use Filament\Tables\Columns\TextColumn;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, PermissionMatrix::class);

beforeEach(function (): void {
    signInOperator();

    $this->roles = collect([
        createRole(Role::ADMINISTRATOR, name: 'Administrator'),
        createRole('editor', name: 'Editor'),
        createRole('support', name: 'Support'),
        createRole('viewer', name: 'Viewer'),
    ]);
});

it('lets the operator hide and show every role column', function (): void {
    $matrix = livewire(RolePermissionMatrix::class);

    foreach ($this->roles as $role) {
        $matrix
            ->assertCanRenderTableColumn('holder_' . holderKey($role))
            ->assertTableColumnExists('holder_' . holderKey($role), fn (TextColumn $column): bool => $column->isToggleable());
    }

    $matrix->assertTableColumnExists('label', fn (TextColumn $column): bool => ! $column->isToggleable());
});

it('shows the first roles only, when told how many', function (): void {
    livewire(RolePermissionMatrix::class, ['rolesShownByDefault' => 2])
        ->assertCanRenderTableColumn('holder_' . holderKey($this->roles[0]))
        ->assertCanRenderTableColumn('holder_' . holderKey($this->roles[1]))
        ->assertCanNotRenderTableColumn('holder_' . holderKey($this->roles[2]))
        ->assertCanNotRenderTableColumn('holder_' . holderKey($this->roles[3]));
});

// Each in a test of its own: Filament keeps the columns a table showed in the session, so a second
// matrix in the same test would start from the first one's choice rather than from its default.
it('takes the number from the plugin', function (): void {
    plugin()->rolesShownByDefault(1);

    livewire(RolePermissionMatrix::class)
        ->assertCanRenderTableColumn('holder_' . holderKey($this->roles[0]))
        ->assertCanNotRenderTableColumn('holder_' . holderKey($this->roles[1]));
});

it('prefers the number the screen names over the plugin\'s', function (): void {
    plugin()->rolesShownByDefault(1);

    livewire(RolePermissionMatrix::class, ['rolesShownByDefault' => 3])
        ->assertCanRenderTableColumn('holder_' . holderKey($this->roles[2]))
        ->assertCanNotRenderTableColumn('holder_' . holderKey($this->roles[3]));
});

it('passes the schema component\'s number to the matrix', function (): void {
    expect(PermissionMatrix::make()->rolesShownByDefault(5)->getComponentProperties())
        ->toMatchArray(['rolesShownByDefault' => 5])
        ->and(PermissionMatrix::make()->getComponentProperties())
        ->toMatchArray(['rolesShownByDefault' => null]);
});

it('marks the matrix for the stylesheet that keeps its first column and header in view', function (): void {
    livewire(RolePermissionMatrix::class)->assertSeeHtml('fac-matrix');

    expect(collect(FilamentAsset::getStyles(['happenv-com/filament-access-control']))->map->getId()->all())
        ->toContain('filament-access-control');
});
