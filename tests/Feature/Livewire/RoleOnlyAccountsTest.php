<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Contracts\HasEditablePermissions;
use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Schemas\Components\PermissionEditor;
use Happenv\FilamentAccessControl\Tests\Fixtures\Livewire\AccountPermissionsView;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\RoleOnlyAccount;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Illuminate\View\ViewException;

use function Pest\Livewire\livewire;

covers(RecordPermissions::class, PermissionEditor::class);

beforeEach(function (): void {
    signInOperator();

    $this->editor = createRole('editor', [ProductPermission::View->value], name: 'Editor');
    $this->user = createUser('member@example.com');
    $this->user->roles()->attach($this->editor);
});

describe('without the column of direct grants', function (): void {
    it('shows what the roles grant and what is in effect, and nothing to click', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->user, 'showDirectGrants' => false])
            ->assertTableColumnHidden('holder_' . holderKey($this->user))
            ->assertTableColumnVisible('inherited')
            ->assertTableColumnVisible('in_effect')
            ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::View->value)
            ->assertSee(__('filament-access-control::editor.read_only_hint'))
            ->call('toggle', holderKey($this->user), ProductPermission::Create->value)
            ->assertNotified(__('filament-access-control::editor.notifications.read_only'));

        expect($this->user->fresh()->getPermissions())->toBeEmpty();
    });

    it('shows In effect even where it would repeat the direct column', function (): void {
        $loner = createUser('loner@example.com', [ProductPermission::View->value]);

        livewire(RecordPermissions::class, ['record' => $loner, 'ability' => null])
            ->assertTableColumnHidden('in_effect');

        livewire(RecordPermissions::class, ['record' => $loner, 'showDirectGrants' => false])
            ->assertTableColumnVisible('in_effect');
    });

    it('keeps a role\'s column — a role\'s grants are the role', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->editor, 'showDirectGrants' => false])
            ->assertTableColumnVisible('holder_' . holderKey($this->editor))
            ->call('toggle', holderKey($this->editor), ProductPermission::Create->value);

        expect($this->editor->fresh()->getPermissions()->all())->toEqualCanonicalizing([ProductPermission::View->value, ProductPermission::Create->value]);
    });

    it('is handed over by the schema component', function (): void {
        expect(PermissionEditor::make()->showsDirectGrants())->toBeTrue()
            ->and(PermissionEditor::make()->showDirectGrants(fn (): bool => false)->showsDirectGrants())->toBeFalse();
    });
});

describe('an account that is not HasEditablePermissions', function (): void {
    beforeEach(function (): void {
        $this->account = RoleOnlyAccount::query()->findOrFail($this->user->getKey());
    });

    it('is shown read-only: what its roles grant and what is in effect', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->account, 'readOnly' => true, 'showDirectGrants' => false])
            ->assertOk()
            ->assertTableColumnStateSet('inherited', ['Editor'], 'permission:' . ProductPermission::View->value)
            ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::View->value)
            ->assertTableColumnStateSet('in_effect', 'not-granted', 'permission:' . ProductPermission::Create->value)
            ->call('toggle', holderKey($this->account), ProductPermission::Create->value)
            ->assertNotified(__('filament-access-control::editor.notifications.read_only'));
    });

    it('is refused where it could be written to', function (): void {
        livewire(RecordPermissions::class, ['record' => $this->account]);
    })->throws(ViewException::class, 'must implement [' . HasEditablePermissions::class . ']');

    it('is drawn by a disabled schema component, and left out of a writable one', function (): void {
        livewire(AccountPermissionsView::class, ['account' => $this->account])
            ->assertSeeLivewire(RecordPermissions::class)
            ->assertSee(__('filament-access-control::editor.read_only_hint'));

        livewire(AccountPermissionsView::class, ['account' => $this->account, 'disabled' => false])
            ->assertDontSeeLivewire(RecordPermissions::class);
    });
});
