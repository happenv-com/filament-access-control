<?php

declare(strict_types=1);

use Filament\Facades\Filament;
use Happenv\FilamentAccessControl\Support\GrantGuard;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\PlainAccount;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\GalleryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\User;
use Happenv\LaravelAccessControl\PermissionRestrictions;
use Illuminate\Contracts\Auth\Authenticatable;

covers(GrantGuard::class);

beforeEach(function (): void {
    Filament::setCurrentPanel('admin');

    $this->guard = resolve(GrantGuard::class);
});

describe('what an operator may grant and revoke', function (): void {
    it('is what the operator holds in effect, implied permissions included', function (): void {
        registerPermissions(GalleryPermission::class);

        $operator = createUser(permissions: [ProductPermission::Update->value]);

        expect($this->guard->grantableSlugs($operator)?->all())
            ->toEqualCanonicalizing([ProductPermission::Update->value, GalleryPermission::Manage->value])
            ->and($this->guard->mayChange($operator, GalleryPermission::Manage->value))->toBeTrue()
            ->and($this->guard->mayChange($operator, ProductPermission::View->value))->toBeFalse();
    });

    it('leaves out what the operator holds but not in effect — a restriction, an unmet condition', function (): void {
        registerPermissions(GalleryPermission::class);
        resolve(PermissionRestrictions::class)->restrictUsing(fn ($permission): bool => $permission === ProductPermission::View);

        $operator = createUser(permissions: [ProductPermission::View->value, GalleryPermission::Publish->value]);

        expect($this->guard->grantableSlugs($operator)?->all())->toBe([]);
    });

    it('counts what the operator\'s roles grant', function (): void {
        $operator = createUser();
        $operator->roles()->attach(createRole('editor', [ProductPermission::Delete->value]));

        expect($this->guard->grantableSlugs($operator)?->all())->toBe([ProductPermission::Delete->value]);
    });

    it('is anything for an operator holding the super-admin role', function (): void {
        $operator = createUser();
        $operator->roles()->attach(createRole(Role::ADMINISTRATOR));

        expect($this->guard->grantableSlugs($operator))->toBeNull()
            ->and($this->guard->mayChange($operator, ProductPermission::Delete->value))->toBeTrue();
    });

    it('is nothing for nobody, nor for an operator the library cannot ask', function (): void {
        $account = PlainAccount::query()->create(['name' => 'Plain', 'email' => 'plain@example.com', 'password' => 'password', 'permissions' => [ProductPermission::View->value]]);

        expect($this->guard->grantableSlugs(null)?->all())->toBe([])
            ->and($this->guard->mayChange(null, ProductPermission::View->value))->toBeFalse()
            ->and($this->guard->grantableSlugs($account)?->all())->toBe([]);
    });

    it('is anything while the guard is off', function (): void {
        plugin()->preventEscalation(false);

        expect($this->guard->grantableSlugs(createUser()))->toBeNull()
            ->and($this->guard->grantableSlugs(null))->toBeNull();
    });

    it('is what grantableBy() says instead, given the operator', function (): void {
        $operator = createUser(permissions: [ProductPermission::View->value]);

        plugin()->grantableBy(fn (Authenticatable $operator): array => $operator instanceof User
            ? [ProductPermission::Delete, ProductPermission::Create->value]
            : []);

        expect($this->guard->grantableSlugs($operator)?->all())->toBe([ProductPermission::Delete->value, ProductPermission::Create->value]);

        plugin()->grantableBy(fn (): null => null);

        expect($this->guard->grantableSlugs($operator))->toBeNull();
    });

    it('hands grantableBy() the operator by the application\'s own class too', function (): void {
        $operator = createUser();

        plugin()->grantableBy(fn (User $operator): array => [ProductPermission::Delete->value]);

        expect($this->guard->grantableSlugs($operator)?->all())->toBe([ProductPermission::Delete->value]);
    });

    it('lets a super-admin past grantableBy() too', function (): void {
        $operator = createUser();
        $operator->roles()->attach(createRole(Role::ADMINISTRATOR));

        plugin()->grantableBy(fn (): array => []);

        expect($this->guard->grantableSlugs($operator))->toBeNull();
    });
});

describe('an operator\'s own holder', function (): void {
    it('is the operator\'s record and every role the operator holds', function (): void {
        $operator = createUser();
        $editor = createRole('editor');
        $other = createRole('other');
        $operator->roles()->attach($editor);

        expect($this->guard->isOwnHolder($operator, $operator))->toBeTrue()
            ->and($this->guard->isOwnHolder($operator, $editor))->toBeTrue()
            ->and($this->guard->isOwnHolder($operator, $other))->toBeFalse()
            ->and($this->guard->isOwnHolder($operator, createUser('someone@example.com')))->toBeFalse()
            ->and($this->guard->isOwnHolder(null, $editor))->toBeFalse();
    });
});
