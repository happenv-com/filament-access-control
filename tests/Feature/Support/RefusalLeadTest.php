<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Support\RefusalLead;
use Happenv\FilamentAccessControl\Tests\Fixtures\Permissions\ProductPermission;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

covers(RefusalLead::class);

beforeEach(function (): void {
    $this->lead = resolve(RefusalLead::class);
});

it('names the missing permission instead of its slug', function (): void {
    expect($this->lead->fromMessage('Unauthorized for ' . ProductPermission::Update->value))
        ->toBe('You do not hold the permission "Update products".');
});

it('reads the prefix the library actually refuses with', function (): void {
    // The coupling this class admits to: the day the library rewords its refusal, this goes red.
    config()->set('access-control.display_permission_in_exception', true);

    $message = Gate::forUser(createUser())->inspect(ProductPermission::Update->value)->message();

    expect($message)->toStartWith(RefusalLead::GATE_PREFIX)
        ->and($this->lead->fromMessage($message))->toBe('You do not hold the permission "Update products".');
});

it('leaves the caller its own default for the bare refusal', function (): void {
    expect($this->lead->fromMessage(Gate::forUser(createUser())->inspect(ProductPermission::Update->value)->message()))->toBeNull()
        ->and($this->lead->fromMessage(null))->toBeNull()
        ->and($this->lead->fromMessage(''))->toBeNull();
});

it('passes a voter\'s own words through', function (): void {
    expect($this->lead->fromMessage('This role is protected.'))->toBe('This role is protected.');
});

it('keeps a slug the catalogue does not know', function (): void {
    expect($this->lead->fromMessage('Unauthorized for some.other.slug'))->toBe('Unauthorized for some.other.slug');
});

it('reads a thrown exception', function (): void {
    expect($this->lead->for(new AuthorizationException('Unauthorized for ' . ProductPermission::View->value)))
        ->toBe('You do not hold the permission "View products".')
        ->and($this->lead->for(null))->toBeNull();
});
