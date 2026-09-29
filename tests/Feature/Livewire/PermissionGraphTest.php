<?php

declare(strict_types=1);

use Filament\Actions\Testing\TestAction;
use Filament\Support\Facades\FilamentAsset;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\BrokenPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class, BrokenPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

it('draws the catalogue as a Mermaid graph, its source on the page until mermaid.js draws it', function (): void {
    livewire(RolePermissionMatrix::class)
        ->mountAction(TestAction::make('permissionGraph')->table())
        ->assertMountedActionModalSee('flowchart LR')
        ->assertMountedActionModalSeeHtml('x-ref="source"')
        ->assertMountedActionModalSeeHtml('permissionGraph(');
});

it('draws what one account holds and why', function (): void {
    $user = createUser('member@example.com', [GalleryPermission::View->value]);

    livewire(RecordPermissions::class, ['record' => $user])
        ->mountAction(TestAction::make('permissionGraph')->table())
        ->assertMountedActionModalSee('flowchart LR')
        ->assertMountedActionModalSee('View gallery');
});

it('ships the graph script as a lazily loaded Alpine component', function (): void {
    expect(FilamentAsset::getAlpineComponentSrc('permission-graph', 'happenv-com/filament-access-control'))->toContain('permission-graph.js')
        ->and(dirname(__DIR__, 3) . '/resources/dist/components/permission-graph.js')->toBeFile();
});

it('offers no graph when told not to, and everything else keeps working (invariant 11)', function (): void {
    plugin()->diagrams(false);

    $this->editor->update(['permissions' => [GalleryPermission::Archive->value, ProductPermission::Delete->value]]);

    livewire(RolePermissionMatrix::class)
        ->assertActionHidden(TestAction::make('permissionGraph')->table())
        ->assertTableColumnVisible('dependencies')
        ->assertTableColumnStateSet('holder_' . holderKey($this->editor), 'conflict', 'permission:' . GalleryPermission::Archive->value)
        ->assertSee('Merge everything can never be allowed: it requires Split everything, which it conflicts with.');
});
