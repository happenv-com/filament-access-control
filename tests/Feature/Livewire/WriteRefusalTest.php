<?php

declare(strict_types=1);

use Filament\Notifications\Notification;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\PermissionWriter;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Support\RefusingPermissionWriter;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class);

beforeEach(function (): void {
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
    $this->author = createRole('author', name: 'Author');

    app()->instance(PermissionWriter::class, new RefusingPermissionWriter(ProductPermission::View->value));
});

it('tells the operator, and writes nothing, when the writer refuses a click', function (): void {
    livewire(RolePermissionMatrix::class, ['deferred' => false])
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->assertNotified('Choose a channel first.');

    expect($this->editor->fresh()->getPermissions())->toBeEmpty();
});

it('shows the refusal\'s body when it has one', function (): void {
    app()->instance(PermissionWriter::class, new RefusingPermissionWriter(ProductPermission::View->value, body: 'Under the View cell.'));

    livewire(RolePermissionMatrix::class, ['deferred' => false])
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->assertNotified(Notification::make()->title('Choose a channel first.')->body('Under the View cell.')->danger());
});

it('keeps a refused holder\'s changes staged at Save, and saves the other holders\'', function (): void {
    $matrix = livewire(RolePermissionMatrix::class, ['deferred' => true])
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value)
        ->call('toggle', holderKey($this->author), ProductPermission::Update->value)
        ->call('save')
        ->assertNotified('Choose a channel first.')
        ->assertSet('changes', [holderKey($this->editor) => ['grant' => [ProductPermission::View->value], 'revoke' => []]]);

    expect($this->editor->fresh()->getPermissions())->toBeEmpty()
        ->and($this->author->fresh()->getPermissions()->all())->toBe([ProductPermission::Update->value])
        ->and($matrix->instance()->isGranted(holderKey($this->editor), ProductPermission::View->value))->toBeTrue();
});
