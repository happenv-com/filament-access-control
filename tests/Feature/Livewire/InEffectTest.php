<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Livewire\RecordPermissions;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;

use function Pest\Livewire\livewire;

covers(RecordPermissions::class);

beforeEach(function (): void {
    registerPermissions(GalleryPermission::class);
    signInOperator();

    $this->editor = createRole('editor', name: 'Editor');
    $this->user = createUser('member@example.com');
});

function unmetLine(int $count): string
{
    return trans_choice('filament-access-control::editor.conditions.unmet', $count, ['condition' => 'Requires MFA']);
}

it('shows what the account has in effect next to what it holds', function (): void {
    $this->editor->update(['permissions' => [ProductPermission::Update->value]]);
    $this->user->roles()->attach($this->editor);
    $this->user->update(['permissions' => [GalleryPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnVisible('in_effect')
        ->assertTableColumnStateSet('holder_' . holderKey($this->user), 'granted', 'permission:' . GalleryPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'missing-requirement', 'permission:' . GalleryPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::Update->value)
        ->assertTableColumnStateSet('in_effect', 'implied', 'permission:' . GalleryPermission::Manage->value)
        ->assertTableColumnStateSet('in_effect', 'not-granted', 'permission:' . ProductPermission::Delete->value);
});

it('shows a permission the account holds but fails a condition of, and says so above the table', function (): void {
    $this->user->update(['permissions' => [GalleryPermission::Publish->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user])
        ->assertTableColumnStateSet('in_effect', 'unmet-condition', 'permission:' . GalleryPermission::Publish->value)
        ->assertSee(unmetLine(1));

    $this->user->saveAppAuthenticationSecret('JBSWY3DPEHPK3PXP');

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . GalleryPermission::Publish->value)
        ->assertDontSee(unmetLine(1));
});

it('keeps a permission in effect when a staged revocation leaves a role granting it', function (): void {
    $this->editor->update(['permissions' => [ProductPermission::View->value]]);
    $this->user->roles()->attach($this->editor);
    $this->user->update(['permissions' => [ProductPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user->fresh(), 'deferred' => true, 'ability' => UserPermission::Update])
        ->call('toggle', holderKey($this->user), ProductPermission::View->value)
        ->assertTableColumnStateSet('holder_' . holderKey($this->user), 'revoked', 'permission:' . ProductPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::View->value);
});

it('shows the consequence of a staged grant before it is saved', function (): void {
    $this->user->update(['permissions' => [GalleryPermission::View->value]]);

    livewire(RecordPermissions::class, ['record' => $this->user, 'deferred' => true, 'ability' => UserPermission::Update])
        ->assertTableColumnStateSet('in_effect', 'missing-requirement', 'permission:' . GalleryPermission::View->value)
        ->call('toggle', holderKey($this->user), ProductPermission::View->value)
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . GalleryPermission::View->value);

    expect($this->user->fresh()->getPermissions()->all())->toBe([GalleryPermission::View->value]);
});

it('counts a super-admin role as holding everything, conditions still applying', function (): void {
    $this->user->roles()->attach(createRole(Role::ADMINISTRATOR, name: 'Administrator'));

    livewire(RecordPermissions::class, ['record' => $this->user->fresh()])
        ->assertTableColumnStateSet('in_effect', 'effective', 'permission:' . ProductPermission::Delete->value)
        ->assertTableColumnStateSet('in_effect', 'unmet-condition', 'permission:' . GalleryPermission::Publish->value);
});

it('draws no In effect column for a role', function (): void {
    livewire(RecordPermissions::class, ['record' => $this->editor])
        ->assertTableColumnHidden('in_effect')
        ->assertDontSee(unmetLine(1));
});
