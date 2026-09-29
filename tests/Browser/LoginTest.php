<?php

declare(strict_types=1);

/*
 * Browser tests drive a real Chromium through Playwright against the test
 * panel (tests/Fixtures/AdminPanelProvider). Run them with
 * `composer test-browser`; install the browser once with
 * `npm ci && npx playwright install chromium`.
 */

beforeEach(function (): void {
    $this->artisan('filament:assets');
});

it('signs in to the panel', function (): void {
    createUser();

    visit('/admin/login')
        ->fill('[id="form.email"]', 'jane@example.com')
        ->fill('[id="form.password"]', 'password')
        ->submit()
        ->assertSee('Dashboard')
        ->assertPathIs('/admin')
        ->assertNoJavaScriptErrors();
});
