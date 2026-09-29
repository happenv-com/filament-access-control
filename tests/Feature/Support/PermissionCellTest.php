<?php

declare(strict_types=1);

use Filament\Support\Icons\Heroicon;
use Happenv\FilamentAccessControl\Attributes\RequiresMFA;
use Happenv\FilamentAccessControl\Support\PermissionCell;
use Happenv\FilamentAccessControl\Support\PermissionCellState;
use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Tests\Fixtures\Conditions\RequiresOfficeHours;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UnregisteredPermission;
use Happenv\LaravelAccessControl\Dto\PermissionResolutionDto;

covers(PermissionCell::class, PermissionCellState::class);

it('turns a resolution into one of seven states', function (PermissionResolutionDto $resolution, PermissionCellState $state, Heroicon $icon, string $color): void {
    expect(PermissionCellState::of($resolution))->toBe($state)
        ->and($state->icon())->toBe($icon)
        ->and($state->color())->toBe($color);
})->with([
    'stored and in effect' => [resolutionOf(), PermissionCellState::Effective, Heroicon::CheckCircle, 'success'],
    'in effect, implied' => [resolutionOf(stored: false, grantedBy: [ProductPermission::Update]), PermissionCellState::Implied, Heroicon::CheckCircle, 'info'],
    'a requirement missing' => [resolutionOf(allowed: false, missing: [ProductPermission::View]), PermissionCellState::MissingRequirement, Heroicon::ExclamationTriangle, 'warning'],
    'a conflict lost' => [resolutionOf(allowed: false, conflicting: [ProductPermission::Delete]), PermissionCellState::Conflict, Heroicon::NoSymbol, 'danger'],
    'restricted at runtime' => [resolutionOf(restricted: true), PermissionCellState::Restricted, Heroicon::LockClosed, 'gray'],
    'a condition unmet' => [resolutionOf(unmetConditions: [new RequiresMFA]), PermissionCellState::UnmetCondition, Heroicon::ShieldExclamation, 'warning'],
    'not granted' => [resolutionOf(stored: false, granted: false, allowed: false), PermissionCellState::NotGranted, Heroicon::XCircle, 'danger'],
]);

it('shows the first reason and lists every one', function (): void {
    $cell = PermissionCell::of(resolutionOf(
        allowed: false,
        missing: [ProductPermission::View],
        conflicting: [ProductPermission::Delete],
        restricted: true,
        unmetConditions: [new RequiresMFA],
    ), resolve(PermissionTree::class));

    expect($cell->state)->toBe(PermissionCellState::MissingRequirement)
        ->and($cell->reasons)->toBe([
            'Missing requirement: View products',
            'Blocked by: Delete products',
            'Restricted by the application right now',
            'Requires MFA — this account does not meet it',
        ]);
});

it('names what implies a permission', function (): void {
    $cell = PermissionCell::of(
        resolutionOf(stored: false, grantedBy: [ProductPermission::Update, ProductPermission::Delete]),
        resolve(PermissionTree::class),
    );

    expect($cell->tooltip())->toBe('Implied by: Update products, Delete products');
});

it('has nothing to say about a plain grant or a plain absence', function (): void {
    $tree = resolve(PermissionTree::class);

    expect(PermissionCell::of(resolutionOf(), $tree)->tooltip())->toBeNull()
        ->and(PermissionCell::of(resolutionOf(stored: false, granted: false, allowed: false), $tree)->tooltip())->toBeNull();
});

it('does not explain what would withhold a permission that is not granted', function (): void {
    $cell = PermissionCell::of(
        resolutionOf(stored: false, granted: false, allowed: false, restricted: true, unmetConditions: [new RequiresMFA]),
        resolve(PermissionTree::class),
    );

    expect($cell->reasons)->toBe([]);
});

it('marks a staged cell with the primary colour and says it is unsaved', function (): void {
    $cell = PermissionCell::of(resolutionOf(allowed: false, missing: [ProductPermission::View]), resolve(PermissionTree::class), staged: true);

    expect($cell->color())->toBe('primary')
        ->and($cell->icon())->toBe(Heroicon::ExclamationTriangle)
        ->and($cell->tooltip())->toBe('Unsaved · Missing requirement: View products');
});

it('adds a reason without touching the rest', function (): void {
    $cell = (new PermissionCell(PermissionCellState::Implied, reasons: ['Implied by: Update products']))->withReason('A click grants it explicitly');

    expect($cell->reasons)->toBe(['Implied by: Update products', 'A click grants it explicitly'])
        ->and($cell->state)->toBe(PermissionCellState::Implied);
});

it('names a permission nobody registered by its value', function (): void {
    $tree = resolve(PermissionTree::class);

    expect($tree->nameOf(UnregisteredPermission::Orphan))->toBe('nowhere.orphan')
        ->and($tree->nameOf(ProductPermission::View))->toBe('View products');
});

it('names a condition by its description, or after its class', function (): void {
    $tree = resolve(PermissionTree::class);

    expect($tree->describeCondition(new RequiresMFA))->toBe('Requires MFA')
        ->and($tree->describeCondition(new RequiresOfficeHours))->toBe('Requires Office Hours');
});
