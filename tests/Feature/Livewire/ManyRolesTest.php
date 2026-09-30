<?php

declare(strict_types=1);

use Filament\Support\Facades\FilamentAsset;
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

    $this->keys = $this->roles->map(fn (Role $role): string => holderKey($role))->all();
});

function indicator(int $shown, int $total): string
{
    return __('filament-access-control::editor.role_picker.indicator', ['shown' => $shown, 'total' => $total]);
}

it('shows every role until told otherwise', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->assertDontSee(indicator(4, 4));

    foreach ($this->keys as $key) {
        $matrix->assertCanRenderTableColumn('holder_' . $key);
    }
});

it('offers every role in the picker, and no other column', function (): void {
    expect(livewire(RolePermissionMatrix::class)->instance()->roleOptions())
        ->toBe(array_combine($this->keys, ['Administrator', 'Editor', 'Support', 'Viewer']));
});

it('shows the first roles only, when told how many, and says so', function (): void {
    livewire(RolePermissionMatrix::class, ['rolesShownByDefault' => 2])
        ->assertCanRenderTableColumn('holder_' . $this->keys[0])
        ->assertCanRenderTableColumn('holder_' . $this->keys[1])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[2])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[3])
        ->assertSee(indicator(2, 4));
});

// Each in a test of its own: Filament keeps the picker's choice in the session, so a second matrix
// in the same test would start from the first one's choice rather than from its default.
it('takes the number from the plugin', function (): void {
    plugin()->rolesShownByDefault(1);

    livewire(RolePermissionMatrix::class)
        ->assertCanRenderTableColumn('holder_' . $this->keys[0])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[1]);
});

it('prefers the number the screen names over the plugin\'s', function (): void {
    plugin()->rolesShownByDefault(1);

    livewire(RolePermissionMatrix::class, ['rolesShownByDefault' => 3])
        ->assertCanRenderTableColumn('holder_' . $this->keys[2])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[3]);
});

it('applies the picked roles on Apply, not before', function (): void {
    livewire(RolePermissionMatrix::class)
        ->set('tableDeferredFilters.' . RolePermissionMatrix::ROLE_PICKER . '.shown', [$this->keys[1], $this->keys[3]])
        ->assertCanRenderTableColumn('holder_' . $this->keys[0])
        ->call('applyTableFilters')
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[0])
        ->assertCanRenderTableColumn('holder_' . $this->keys[1])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[2])
        ->assertCanRenderTableColumn('holder_' . $this->keys[3])
        ->assertSee(indicator(2, 4));
});

it('applies every tick at once when the picker is not deferred', function (): void {
    plugin()->deferRolePicker(false);

    livewire(RolePermissionMatrix::class)
        ->set('tableFilters.' . RolePermissionMatrix::ROLE_PICKER . '.shown', [$this->keys[2]])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[0])
        ->assertCanRenderTableColumn('holder_' . $this->keys[2]);

    plugin()->deferRolePicker();
});

it('shows a role added after the operator picked', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)
        ->set('tableDeferredFilters.' . RolePermissionMatrix::ROLE_PICKER . '.shown', [$this->keys[0]])
        ->call('applyTableFilters');

    $added = createRole('auditor', name: 'Auditor');

    $matrix->dispatch(RolePermissionMatrix::ROLES_CHANGED)
        ->assertCanRenderTableColumn('holder_' . $this->keys[0])
        ->assertCanNotRenderTableColumn('holder_' . $this->keys[1])
        ->assertCanRenderTableColumn('holder_' . holderKey($added));
});

it('passes the schema component\'s options to the matrix', function (): void {
    expect(PermissionMatrix::make()->rolesShownByDefault(5)->deferRolePicker(false)->getComponentProperties())
        ->toMatchArray(['rolesShownByDefault' => 5, 'rolePickerDeferred' => false])
        ->and(PermissionMatrix::make()->getComponentProperties())
        ->toMatchArray(['rolesShownByDefault' => null, 'rolePickerDeferred' => null]);
});

it('marks the matrix for the stylesheet that keeps its first column and header in view', function (): void {
    livewire(RolePermissionMatrix::class)->assertSeeHtml('fac-matrix');

    expect(collect(FilamentAsset::getStyles(['happenv-com/filament-access-control']))->map->getId()->all())
        ->toContain('filament-access-control');
});
