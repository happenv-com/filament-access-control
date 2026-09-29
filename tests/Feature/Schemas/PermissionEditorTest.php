<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\Pages\CreateRole;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Roles\Pages\EditRole;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\Pages\EditUser;
use Happenv\FilamentAccessControl\Tests\Fixtures\Resources\Users\Pages\ViewUser;
use Livewire\Features\SupportTesting\Testable;

use function Pest\Livewire\livewire;

covers(PermissionEditor::class);

beforeEach(function (): void {
    signInOperator();
});

function permissionEditorOf(Testable $page): PermissionEditor
{
    $schema = $page->instance()->getSchema('form') ?? $page->instance()->getSchema('infolist');

    /** @var PermissionEditor */
    return collect($schema->getFlatComponents(withHidden: true))
        ->first(fn ($component): bool => $component instanceof PermissionEditor);
}

it('sits in a tab of the role\'s edit form', function (): void {
    $role = createRole('editor');

    livewire(EditRole::class, ['record' => $role->getKey()])
        ->assertOk()
        ->assertSeeLivewire(RecordPermissions::class);
});

it('is left out of a create form, which has nothing to hand permissions to yet', function (): void {
    livewire(CreateRole::class)
        ->assertOk()
        ->assertDontSeeLivewire(RecordPermissions::class);
});

it('saves on its own, not with the form around it', function (): void {
    $role = createRole('editor');

    $page = livewire(EditRole::class, ['record' => $role->getKey()]);

    expect(permissionEditorOf($page)->getComponentProperties())
        ->toMatchArray([
            'record' => $role->fresh(),
            'deferred' => null,
            'surface' => null,
            'ability' => false,
            'readOnly' => false,
            'showInherited' => true,
        ]);

    $page->fillForm(['name' => 'Renamed'])->call('save')->assertHasNoFormErrors();

    expect($role->fresh())
        ->name->toBe('Renamed')
        ->getPermissions()->toBeEmpty();
});

it('hands its configuration to the component', function (): void {
    $user = createUser('member@example.com');

    $properties = permissionEditorOf(livewire(EditUser::class, ['record' => $user->getKey()]))->getComponentProperties();

    expect($properties)->toMatchArray([
        'deferred' => true,
        'ability' => UserPermission::Update,
        'readOnly' => false,
    ])->and($properties['record']->is($user))->toBeTrue();
});

it('goes read-only in a disabled schema', function (): void {
    $user = createUser('member@example.com');

    expect(permissionEditorOf(livewire(ViewUser::class, ['record' => $user->getKey()]))->getComponentProperties()['readOnly'])->toBeTrue();
});

it('evaluates what it is configured with', function (): void {
    $editor = PermissionEditor::make()
        ->deferred(fn (): bool => true)
        ->surface(fn (): Surface => Surface::Api)
        ->ability(fn (): ProductPermission => ProductPermission::Update)
        ->showInheritedPermissions(false);

    expect($editor->isDeferred())->toBeTrue()
        ->and($editor->getSurface())->toBe(Surface::Api)
        ->and($editor->getAbility())->toBe(ProductPermission::Update)
        ->and($editor->showsInheritedPermissions())->toBeFalse()
        ->and($editor->getComponent())->toBe(RecordPermissions::class)
        ->and(PermissionEditor::make()->isDeferred())->toBeNull();
});
