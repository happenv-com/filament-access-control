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
use Illuminate\Support\Str;

use function Pest\Livewire\livewire;

covers(RolePermissionMatrix::class, RecordPermissions::class);

/**
 * The buttons of a rendered screen that hold another button, and the buttons whose text has `$text`.
 *
 * @return array{nested: int, matching: int}
 */
function buttonsIn(string $html, string $text): array
{
    $document = new DOMDocument;
    $previous = libxml_use_internal_errors(true);

    try {
        $document->loadHTML('<?xml encoding="utf-8"?><body>' . $html . '</body>');
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
    }

    $xpath = new DOMXPath($document);

    return [
        'nested' => $xpath->query('//button[.//button]')->length,
        'matching' => $xpath->query('//button[contains(., ' . Str::of($text)->wrap("'")->toString() . ')]')->length,
    ];
}

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
        ->assertSeeHtml('wire:click="mountAction(&#039;pickScope&#039;')
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

it('draws the note and the toggle as sibling buttons, never one inside the other', function (): void {
    $html = livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->html();

    // The note is a native button, so Enter and Space both activate it.
    expect(buttonsIn($html, 'Scope of Editor'))->toBe(['nested' => 0, 'matching' => 1])
        ->and($html)->toContain('wire:click="toggle(&#039;' . holderKey($this->editor) . '&#039;, &#039;' . ProductPermission::View->value . '&#039;)"');
});

it('still toggles the cell that carries a note', function (): void {
    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->call('toggle', holderKey($this->editor), ProductPermission::View->value);

    expect($this->editor->fresh()->getPermissions()->all())->toBe([]);
});

it('draws no button around a note on a screen that edits nothing', function (): void {
    $html = livewire(RecordPermissions::class, ['record' => $this->editor, 'readOnly' => true])
        ->call('setGroupsExpanded', true)
        ->html();

    expect($html)->toContain('Scope of Editor')
        ->and(buttonsIn($html, 'Scope of Editor'))->toBe(['nested' => 0, 'matching' => 0]);
});

it('speaks the grant state of a cell that cannot be changed, beside its icon', function (): void {
    $html = livewire(RecordPermissions::class, ['record' => $this->editor, 'readOnly' => true])
        ->call('setGroupsExpanded', true)
        ->html();

    $document = new DOMDocument;
    $previous = libxml_use_internal_errors(true);

    try {
        $document->loadHTML('<?xml encoding="utf-8"?><body>' . $html . '</body>');
    } finally {
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
    }

    $texts = (new DOMXPath($document))->query('//*[contains(@class, "fac-cell")]//span[contains(@class, "fi-sr-only")]');

    expect($texts->length)->toBeGreaterThan(0)
        ->and(trim($texts->item(0)->textContent))->not->toBe('');
});

it('asks the application for a cell\'s note once per render', function (): void {
    $asked = [];

    plugin()->holderCellNotes(function (Model $holder, PermissionDto $permission) use (&$asked): ?CellNote {
        $asked[$holder->getKey() . ':' . $permission->slug] = ($asked[$holder->getKey() . ':' . $permission->slug] ?? 0) + 1;

        return $permission->slug === ProductPermission::View->value
            ? new CellNote(label: 'Scope', action: 'pickScope')
            : null;
    });

    livewire(RolePermissionMatrix::class)->call('setGroupsExpanded', true);

    // The branch, the icon and the click all read the same answer.
    expect($asked)->not->toBeEmpty()
        ->and(array_values(array_unique($asked)))->toBe([1]);
});

it('survives a callback that answers once and then nothing', function (): void {
    $answered = false;

    plugin()->holderCellNotes(function () use (&$answered): ?CellNote {
        if ($answered) {
            return null;
        }

        $answered = true;

        return new CellNote(label: 'Once', action: 'pickScope');
    });

    livewire(RolePermissionMatrix::class)
        ->call('setGroupsExpanded', true)
        ->assertSee('Once');
});

it('refuses a plugin action whose handler is a method name, naming it', function (): void {
    plugin()->actions(fn (): array => [Action::make('pickScope')->action('pickScope')]);

    expect(fn (): array => plugin()->getActions())
        ->toThrow(InvalidArgumentException::class, 'The plugin action [pickScope] has a method name as its handler')
        // Thrown as the screen renders, so wrapped by the view — the message is what counts.
        ->and(fn () => livewire(RolePermissionMatrix::class))
        ->toThrow('Pass a Closure to ->action() instead');
});

it('keeps an action with no handler, and one with a Closure', function (): void {
    plugin()->actions(fn (): array => [
        Action::make('docs')->url('https://example.com'),
        Action::make('confirm')->requiresConfirmation(),
        Action::make('run')->action(fn (): null => null),
    ]);

    expect(array_map(fn (Action $action): string => $action->getName(), plugin()->getActions()))->toBe(['docs', 'confirm', 'run']);
});
