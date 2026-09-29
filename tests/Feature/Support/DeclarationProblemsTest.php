<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Support\DeclarationProblems;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\BrokenPermission;
use Happenv\LaravelAccessControl\PermissionGraph;
use Illuminate\Support\Facades\Lang;

covers(DeclarationProblems::class);

/**
 * Overwrites one translation LINE (not a whole group) with `null`, after fully loading the group's
 * real content — the same nested array Lang::addLines() edits, but a bare addLines() call before
 * anything else touched the group would stop it loading its other lines at all.
 */
function forgetTranslationLine(string $item): void
{
    $translator = app('translator');

    $translator->load('filament-access-control', 'editor', 'en');
    $translator->addLines(['editor.' . $item => null], 'en', 'filament-access-control');
}

beforeEach(function (): void {
    registerPermissions(BrokenPermission::class);

    $this->problems = resolve(DeclarationProblems::class);
});

it('falls back to the library English sentence when a problem type has no translation', function (): void {
    expect(Lang::has('filament-access-control::editor.problems.requires_conflicting'))->toBeTrue();

    forgetTranslationLine('problems.requires_conflicting');

    expect(Lang::has('filament-access-control::editor.problems.requires_conflicting'))->toBeFalse();

    // Only the problem whose type lost its translation falls back — its sibling, of a different
    // type that is still translated, is untouched.
    $expectedFallback = resolve(PermissionGraph::class)->problems()[0];

    expect($this->problems->sentences())
        ->toHaveCount(2)
        ->and($this->problems->sentences()[0])->toBe($expectedFallback)
        ->and($this->problems->sentences()[1])->toBe(__('filament-access-control::editor.problems.unregistered_target', [
            'permission' => 'Adopt orphans',
            'other' => 'nowhere.orphan',
            'rule' => 'Requires',
        ]));
});

it("falls back to the rule type's own name when a rule type has no translation", function (): void {
    forgetTranslationLine('problems.rules.requires');

    expect(Lang::has('filament-access-control::editor.problems.rules.requires'))->toBeFalse();

    $expected = __('filament-access-control::editor.problems.unregistered_target', [
        'permission' => 'Adopt orphans',
        'other' => 'nowhere.orphan',
        'rule' => 'Requires',
    ]);

    expect($this->problems->sentences())->toContain($expected);
});
