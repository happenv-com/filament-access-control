<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\DependencyBadge;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;

use function Pest\Livewire\livewire;

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

it('shows every rule on both of its ends, and every condition', function (string $slug, array $badges): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect(array_map(
        fn (DependencyBadge $badge): array => [$badge->label, $badge->color],
        $matrix->dependencyBadges(['type' => 'permission', 'slug' => $slug]),
    ))->toBe($badges);
})->with([
    'requires' => [GalleryPermission::View->value, [['Requires: View products', 'gray']]],
    'required by' => [ProductPermission::View->value, [['Required by: View gallery', 'gray']]],
    'implied by' => [GalleryPermission::Manage->value, [['Implied by: Update products', 'info']]],
    'implies' => [ProductPermission::Update->value, [['Implies: Manage gallery', 'info']]],
    'blocked by' => [GalleryPermission::Archive->value, [['Blocked by: Delete products', 'danger']]],
    'blocks' => [ProductPermission::Delete->value, [['Blocks: Archive gallery', 'danger']]],
    'a condition' => [GalleryPermission::Publish->value, [['Requires MFA', 'warning']]],
    'nothing' => [ProductPermission::Create->value, []],
]);

it('gives the declared reason in the tooltip', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect($matrix->dependencyTooltip(['type' => 'permission', 'slug' => GalleryPermission::View->value]))
        ->toBe('Requires: View products — The gallery shows products.')
        ->and($matrix->dependencyTooltip(['type' => 'permission', 'slug' => GalleryPermission::Manage->value]))->toBeNull();
});

it('draws the column in the matrix and in the editor of one record', function (): void {
    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->assertTableColumnVisible('dependencies')
        ->assertSee('Requires: View products');

    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->call('setGroupsExpanded', true)
        ->assertTableColumnVisible('dependencies')
        ->assertSee('Blocked by: Delete products');
});

it('has nothing to say on a subject row', function (): void {
    expect(livewire(RolePermissionMatrix::class)->instance()->dependencyBadges(['type' => 'subject', 'slug' => null]))->toBe([]);
});

it('finds a subject through the permissions its rules tie it to', function (): void {
    $records = array_keys(livewire(RolePermissionMatrix::class)->instance()->permissionRecords('gallery', withFolded: true));

    expect($records)->toContain('subject:' . ProductPermission::class)
        ->toContain('subject:' . GalleryPermission::class)
        ->not->toContain('subject:' . CategoryPermission::class);
});
