<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\DependencyBadge;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\BrokenPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;

use function Pest\Livewire\livewire;

const MERGE_PROBLEM = 'Merge everything can never be allowed: it requires Split everything, which it conflicts with.';
const ADOPT_PROBLEM = 'Adopt orphans declares “Requires” about nowhere.orphan, whose enum is not registered.';

beforeEach(function (): void {
    registerPermissions(BrokenPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
});

/**
 * @return list<string>
 */
function badgeLabels(object $component, string $slug): array
{
    return array_map(
        fn (DependencyBadge $badge): string => $badge->label,
        $component->dependencyBadges(['type' => 'permission', 'slug' => $slug]),
    );
}

it('lists every declaration problem above the matrix, in words', function (): void {
    livewire(RolePermissionMatrix::class)
        ->assertSee(__('filament-access-control::editor.problems.heading'))
        ->assertSee(MERGE_PROBLEM)
        ->assertSee(ADOPT_PROBLEM);
});

it('marks the rows whose declaration is wrong', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->instance();

    expect(badgeLabels($matrix, BrokenPermission::Merge->value))->toContain('Invalid declaration')
        ->and(badgeLabels($matrix, BrokenPermission::Adopt->value))->toContain('Invalid declaration')
        ->and(badgeLabels($matrix, BrokenPermission::Split->value))->not->toContain('Invalid declaration');
});

it('lists them above the editor of one record too', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])->assertSee(MERGE_PROBLEM);
});

it('keeps quiet when the plugin is told to', function (): void {
    plugin()->declarationProblems(false);

    $matrix = livewire(RolePermissionMatrix::class)
        ->assertDontSee(__('filament-access-control::editor.problems.heading'))
        ->assertDontSee(MERGE_PROBLEM);

    expect(badgeLabels($matrix->instance(), BrokenPermission::Merge->value))->not->toContain('Invalid declaration');
});

it('needs no dependencies column on a surface none of whose permissions declares any', function (): void {
    livewire(RolePermissionMatrix::class)->assertTableColumnVisible('dependencies');

    livewire(RolePermissionMatrix::class, ['surface' => Surface::Api])->assertTableColumnHidden('dependencies');
});
