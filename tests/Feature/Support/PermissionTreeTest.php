<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Support\PermissionTree;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\CategoryPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\Surface;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\UserPermission;
use Happenv\LaravelAccessControl\Dto\PermissionDto;
use Happenv\LaravelAccessControl\Dto\PermissionSubjectDto;

covers(PermissionTree::class);

beforeEach(function (): void {
    $this->tree = resolve(PermissionTree::class);
});

afterEach(function (): void {
    PermissionTree::resolveActionLabelsUsing(null);
});

describe('groups', function (): void {
    it('sorts groups and the subjects inside them by name', function (): void {
        expect($this->tree->groups()->keys()->all())->toBe(['administration', 'catalogue'])
            ->and($this->tree->groups()->get('catalogue')->subjects->pluck('name')->values()->all())
            ->toBe(['Categories', 'Products']);
    });

    it('keys subjects by the enum that declares them', function (): void {
        $catalogue = $this->tree->groups()->get('catalogue');

        expect($catalogue->subjects->get(ProductPermission::class))->toBeInstanceOf(PermissionSubjectDto::class)
            ->and($catalogue->subjects->get(ProductPermission::class)->children->pluck('slug')->all())
            ->toBe(array_column(ProductPermission::cases(), 'value'));
    });
});

describe('search', function (): void {
    it('yields the whole subject when one of its permissions matches', function (): void {
        $products = $this->tree->groups('catalogue.product.delete')->get('catalogue')?->subjects->get(ProductPermission::class);

        expect($products)->toBeInstanceOf(PermissionSubjectDto::class)
            ->and($products->children)->toHaveCount(count(ProductPermission::cases()));
    });

    it('drops the subjects and groups in which nothing matches', function (): void {
        $groups = $this->tree->groups('catalogue.product.delete');

        expect($groups->keys()->all())->toBe(['catalogue'])
            ->and($groups->get('catalogue')->subjects->keys()->all())->toBe([ProductPermission::class]);
    });

    it('keeps every subject of a group whose name matches', function (): void {
        expect($this->tree->groups('catalog')->get('catalogue')->subjects)->toHaveCount(2);
    });

    it('matches regardless of case and diacritics', function (): void {
        expect($this->tree->groups('PRÔDUCTS')->get('catalogue')?->subjects->has(ProductPermission::class))->toBeTrue();
    });

    it('matches the translated verb', function (): void {
        app()->setLocale('pl');

        expect($this->tree->groups('usuwanie')->get('catalogue')?->subjects->has(ProductPermission::class))->toBeTrue();
    });
});

describe('surfaces', function (): void {
    it('narrows the catalogue to what a surface declares', function (): void {
        $groups = $this->tree->groups(surface: Surface::Api);

        expect($groups->keys()->all())->toBe(['catalogue'])
            ->and($groups->get('catalogue')->subjects->keys()->all())->toBe([ProductPermission::class])
            ->and($groups->get('catalogue')->subjects->get(ProductPermission::class)->children->pluck('slug')->all())
            ->toBe([ProductPermission::View->value, ProductPermission::Create->value]);
    });

    it('offers the whole catalogue on a surface that offers everything', function (): void {
        expect($this->tree->slugsAvailableOn(Surface::Panel)->all())->toBe($this->tree->slugs()->all())
            ->and($this->tree->groups(surface: Surface::Panel)->keys()->all())->toBe(['administration', 'catalogue']);
    });

    it('searches only what the surface offers', function (): void {
        expect($this->tree->groups('categories', Surface::Api))->toBeEmpty();
    });

    it('never lets a narrowed view leak into the next caller', function (): void {
        $this->tree->groups(surface: Surface::Api);

        expect($this->tree->groups()->get('catalogue')->subjects)->toHaveCount(2);
    });
});

describe('lookups', function (): void {
    it('knows every slug and every name', function (): void {
        expect($this->tree->slugs())->toContain(UserPermission::Update->value)
            ->and($this->tree->names()->get(ProductPermission::Update->value))->toBe('Update products')
            ->and($this->tree->find(ProductPermission::View->value))->toBeInstanceOf(PermissionDto::class)
            ->and($this->tree->find('nope'))->toBeNull()
            ->and($this->tree->permissions())->toHaveCount(13);
    });
});

describe('action labels', function (): void {
    it('translates a known verb', function (): void {
        app()->setLocale('pl');

        expect($this->tree->actionLabel($this->tree->find(ProductPermission::Delete->value)))->toBe('Usuwanie');
    });

    it('falls back to the case name for a verb nobody translated', function (): void {
        expect($this->tree->actionLabel($this->tree->find(CategoryPermission::MergeDuplicates->value)))->toBe('Merge Duplicates');
    });

    it('asks the application first', function (): void {
        PermissionTree::resolveActionLabelsUsing(fn (PermissionDto $permission): ?string => $permission->enum === CategoryPermission::MergeDuplicates ? 'Merge' : null);

        expect($this->tree->actionLabel($this->tree->find(CategoryPermission::MergeDuplicates->value)))->toBe('Merge')
            ->and($this->tree->actionLabel($this->tree->find(CategoryPermission::View->value)))->toBe('View');
    });
});
