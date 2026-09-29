<?php

declare(strict_types=1);

use Happenv\FilamentAccessControl\Tests\TestCase;

pest()->extend(TestCase::class)->in('Unit', 'Feature');

function createUser(string $email = 'jane@example.com'): Happenv\FilamentAccessControl\Tests\Fixtures\User
{
    return Happenv\FilamentAccessControl\Tests\Fixtures\User::query()->create([
        'name' => 'Jane',
        'email' => $email,
        'password' => 'password',
    ]);
}
