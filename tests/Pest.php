<?php

declare(strict_types=1);

use VendorName\Skeleton\Tests\TestCase;

// @browser-start
pest()->extend(TestCase::class)->in('Unit', 'Feature', 'Browser');
// @browser-end
// @no-browser-start
pest()->extend(TestCase::class)->in('Unit', 'Feature');
// @no-browser-end

function createUser(string $email = 'jane@example.com'): VendorName\Skeleton\Tests\Fixtures\User
{
    return VendorName\Skeleton\Tests\Fixtures\User::query()->create([
        'name' => 'Jane',
        'email' => $email,
        'password' => 'password',
    ]);
}
