<?php

declare(strict_types=1);

use Filament\Actions\Action;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Livewire\RolePermissionMatrix;
use Happenv\FilamentAccessControl\Support\CellNote;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Illuminate\Database\Eloquent\Model;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

beforeEach(function (): void {
    signInOperator();

    $this->editor = createRole('editor', [ProductPermission::View->value], name: 'Editor');

    $this->calls = [];

    // A data-scope chip under every role's "View products" cell, opening an action that renames the
    // role — a write the screen has to read back.
    plugin()
        ->holderCellNotes(fn (Model $holder, PermissionDto $permission): ?CellNote => $permission->slug === ProductPermission::View->value
            ? new CellNote(label: 'Scope of ' . $holder->getAttribute('name'), color: 'warning', tooltip: 'Pick channels', action: 'pickScope', arguments: ['scope' => 'channels'])
            : null)
        ->actions(fn (): array => [
            Action::make('pickScope')->action(function (array $arguments): void {
                $this->calls[] = $arguments;

                Role::query()->whereKey($arguments['holder'])->update(['name' => 'Renamed']);
            }),
        ]);
});

it('notes a permission row, and no subject or group row', function (): void {
    $matrix = livewire(RolePermissionMatrix::class)->call('setGroupsExpanded', true)->instance();
    $column = 'holder_' . holderKey($this->editor);

    expect($matrix->holderCellNote(holderKey($this->editor), $matrix->permissionRecords()['permission:' . ProductPermission::View->value]))
        ->toBeInstanceOf(CellNote::class)
        ->and($matrix->holderCellNote(holderKey($this->editor), $matrix->permissionRecords()['permission:' . ProductPermission::Update->value]))->toBeNull()
        ->and($matrix->holderCellNote(holderKey($this->editor), $matrix->permissionRecords()['subject:' . ProductPermission::class]))->toBeNull()
        ->and($matrix->holderCellNote(holderKey($this->editor), $matrix->permissionRecords()['group:catalogue']))->toBeNull();

    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->assertSee('Scope of Editor')
        ->assertSee('Pick channels')
        ->assertTableColumnExists($column);
});

it('notes nothing without a callback', function (): void {
    plugin()->holderCellNotes(null);

    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->assertDontSee('Scope of Editor');
});

it('mounts the plugin action from the note, with the cell\'s holder and permission, without toggling the cell', function (): void {
    $arguments = ['scope' => 'channels', 'holder' => holderKey($this->editor), 'permission' => ProductPermission::View->value];

    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        // The note's own click handler, stopped before it reaches the cell's.
        ->assertSeeHtml('wire:click.prevent.stop="mountAction(&#039;pickScope&#039;')
        ->call('mountAction', 'pickScope', $arguments)
        ->assertHasNoErrors();

    expect($this->calls)->toBe([$arguments])
        ->and($this->editor->fresh()->getPermissions()->all())->toBe([ProductPermission::View->value]);
});

it('reads the holders again once the action has run', function (): void {
    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->assertSee('Scope of Editor')
        ->call('mountAction', 'pickScope', ['holder' => holderKey($this->editor), 'permission' => ProductPermission::View->value])
        ->assertSee('Scope of Renamed')
        ->assertDontSee('Scope of Editor');
});

it('shows the note as plain text and mounts nothing on a screen that edits nothing', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor, 'readOnly' => true])
        ->call('setGroupsExpanded', true)
        ->assertSee('Scope of Editor')
        ->assertDontSeeHtml('mountAction(&#039;pickScope')
        ->call('mountAction', 'pickScope', ['holder' => holderKey($this->editor), 'permission' => ProductPermission::View->value])
        ->assertActionNotMounted('pickScope');

    expect($this->calls)->toBe([])
        ->and($this->editor->fresh()->name)->toBe('Editor');
});

it('shows the note on the record screen and mounts its action there', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->call('setGroupsExpanded', true)
        ->assertSeeHtml('mountAction(&#039;pickScope&#039;')
        ->call('mountAction', 'pickScope', ['holder' => holderKey($this->editor), 'permission' => ProductPermission::View->value]);

    expect($this->editor->fresh()->name)->toBe('Renamed');
});
