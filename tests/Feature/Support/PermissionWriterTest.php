<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Events\PermissionsUpdated;
use Happenv\FilamentAccessControl\Exceptions\PermissionWriteRefused;
use Happenv\FilamentAccessControl\Support\PermissionWriter;
use Happenv\FilamentAccessControl\Tests\Fixtures\Models\Role;
use Happenv\FilamentAccessControl\Tests\Fixtures\Support\RefusingPermissionWriter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

covers(PermissionWriter::class, PermissionsUpdated::class);

beforeEach(function (): void {
    $this->writer = resolve(PermissionWriter::class);
});

it('grants and revokes in one write', function (): void {
    $role = createRole('editor', ['a', 'b']);

    $this->writer->write($role, grant: ['c'], revoke: ['a']);

    expect($role->fresh()->getPermissions()->all())->toBe(['b', 'c']);
});

it('replays the intent onto the list as it is now, not as the caller last read it', function (): void {
    $role = createRole('editor', ['a']);
    $stale = Role::query()->findOrFail($role->id);

    // Another operator, a moment earlier.
    $this->writer->write($role, grant: ['b']);

    $this->writer->write($stale, grant: ['c']);

    expect($role->fresh()->getPermissions()->all())->toBe(['a', 'b', 'c']);
});

it('treats a grant of something held and a revocation of something gone as nothing', function (): void {
    Event::fake([PermissionsUpdated::class]);

    $role = createRole('editor', ['a']);
    $updatedAt = $role->updated_at;

    $this->travel(1)->minute();

    $written = $this->writer->write($role, grant: ['a'], revoke: ['z']);

    expect($written->getPermissions()->all())->toBe(['a'])
        ->and($role->fresh()->updated_at->equalTo($updatedAt))->toBeTrue();

    Event::assertNotDispatched(PermissionsUpdated::class);
});

it('lets a grant win over a revocation of the same slug', function (): void {
    $role = createRole('editor');

    $this->writer->write($role, grant: ['a'], revoke: ['a']);

    expect($role->fresh()->getPermissions()->all())->toBe(['a']);
});

it('reports exactly what changed', function (): void {
    Event::fake([PermissionsUpdated::class]);

    $role = createRole('editor', ['a', 'b']);

    $this->writer->write($role, grant: ['b', 'c'], revoke: ['a', 'z']);

    Event::assertDispatched(PermissionsUpdated::class, fn (PermissionsUpdated $event): bool => $event->record->is($role)
        && $event->granted === ['c']
        && $event->revoked === ['a']);
});

it('writes nothing, and reports nothing, when a subclass refuses the list', function (): void {
    Event::fake([PermissionsUpdated::class]);

    $role = createRole('editor', ['a']);

    expect(fn (): Model => (new RefusingPermissionWriter('b'))->write($role, grant: ['b', 'c']))
        ->toThrow(PermissionWriteRefused::class, 'Choose a channel first.');

    expect($role->fresh()->getPermissions()->all())->toBe(['a']);

    Event::assertNotDispatched(PermissionsUpdated::class);
});
