# :package_title

<!-- @public-start -->
[![Latest Version](https://img.shields.io/github/v/release/:github_org/:package_slug?style=flat-square&label=version)](https://github.com/:github_org/:package_slug/releases)
[![Tests](https://img.shields.io/github/actions/workflow/status/:github_org/:package_slug/tests.yml?label=tests&style=flat-square)](https://github.com/:github_org/:package_slug/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/:github_org/:package_slug/phpstan.yml?label=phpstan&style=flat-square)](https://github.com/:github_org/:package_slug/actions/workflows/phpstan.yml)
[![Quality](https://img.shields.io/github/actions/workflow/status/:github_org/:package_slug/quality.yml?label=code%20quality&style=flat-square)](https://github.com/:github_org/:package_slug/actions/workflows/quality.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/:vendor_slug/:package_slug.svg?style=flat-square)](https://packagist.org/packages/:vendor_slug/:package_slug)
[![License](https://img.shields.io/github/license/:github_org/:package_slug.svg?style=flat-square)](LICENSE.md)

<!-- @public-end -->
:package_description

<!--
  One or two paragraphs: what the package does and for whom. Link the library
  it builds on, if any. Then show the smallest useful example.
-->

<!-- @plugin-start -->
```php
use VendorName\Skeleton\SkeletonPlugin;

$panel->plugin(SkeletonPlugin::make());
```
<!-- @plugin-end -->

## Key features

<!--
  3–8 bullets. Each one starts with a bold outcome, then one sentence on how
  or when. Say what the user GETS, not how it is implemented. Link the section
  of this README that covers it.
-->

- **First feature.** What it gives the user, in one sentence.
- **Second feature.** What it gives the user, in one sentence.
- **Tested.** Covered by a Pest suite on every supported version combination.

## Requirements

| Package  | Versions  |
|----------|-----------|
| PHP      | 8.3 – 8.5 |
| Laravel  | 12, 13    |
<!-- @filament-start -->
| Filament | 5         |
<!-- @filament-end -->

## Installation

<!-- @public-start -->
Install the package via Composer:

```bash
composer require :vendor_slug/:package_slug
```
<!-- @public-end -->
<!-- @private-start -->
This package is private and served from [Packeton](https://packeton.happenv.com). Add the repository to your application's `composer.json` once:

```json
"repositories": [
    {
        "type": "composer",
        "url": "https://packeton.happenv.com"
    }
]
```

Store your Packeton token in `auth.json` (never commit it):

```bash
composer config --auth http-basic.packeton.happenv.com token <your-packeton-token>
```

Then install the package:

```bash
composer require :vendor_slug/:package_slug
```
<!-- @private-end -->

<!-- @filament-start -->

> [!IMPORTANT]
> If you have not set up a custom theme and are using Filament Panels, follow the instructions in the [Filament docs](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme) first.

Add the package's views to your theme's CSS file, so Tailwind generates the classes they use:

```css
@source '../../../../vendor/:vendor_slug/:package_slug/resources/**/*.blade.php';
```
<!-- @filament-end -->
<!-- @plugin-start -->

Register the plugin in your panel provider:

```php
use VendorName\Skeleton\SkeletonPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(SkeletonPlugin::make());
}
```
<!-- @plugin-end -->

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag=":package_short_name-config"
```

<!-- Show the published config, or a table of the options that matter. -->

Optionally, publish the views and translations:

```bash
php artisan vendor:publish --tag=":package_short_name-views"
php artisan vendor:publish --tag=":package_short_name-translations"
```

## Usage

<!--
  Task-oriented subsections ("### Doing X"), each with a short example.
  Start with the most common task.
-->

## Testing your application

<!--
  If the package ships test helpers (a Livewire `Testable` mixin, fakes,
  assertions), document them here with a Pest example. Otherwise remove this
  section.
-->

## Translations

<!--
  List the shipped locales (a table of "Language (`code`)" cells reads well)
  and how to publish them. tests/Unit/TranslationsTest.php checks that every
  locale has exactly the keys English has.
-->

## Development

```bash
composer test          # unit and feature tests
<!-- @browser-start -->
composer test-browser  # browser tests (once: npm ci && npx playwright install chromium)
<!-- @browser-end -->
composer phpstan       # static analysis
composer cs            # fix code style: composer normalize, Rector, Pint
composer ci            # everything CI checks, locally
```
<!-- @assets-start -->

The package's JavaScript and CSS are built by `bin/build.js` into `resources/dist`, which is committed. After changing `resources/js` or `resources/css`, rebuild and commit the result — CI refuses outdated assets:

```bash
npm ci
npm run build   # or `npm run dev` to rebuild on change
npm run lint    # Prettier check, as in CI
```
<!-- @assets-end -->

## Upgrading

Breaking changes and how to migrate are described in [UPGRADING](UPGRADING.md) for every major version.

## Changelog

See [CHANGELOG](CHANGELOG.md) and [GitHub releases](https://github.com/:github_org/:package_slug/releases) for what has changed recently.

<!-- @public-start -->
## Contributing

See [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

<!-- @public-end -->
## Security vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Happenv sp. z o.o.](https://happenv.com)
- [webard](https://github.com/webard)
<!-- @public-start -->
- [All contributors](../../contributors)
<!-- @public-end -->

## License

<!-- @public-start -->
The MIT License (MIT). See [License File](LICENSE.md) for more information.
<!-- @public-end -->
<!-- @private-start -->
Proprietary. Copyright © Happenv sp. z o.o. All rights reserved.
<!-- @private-end -->

---

<p align="center">
    <a href="https://happenv.com">
        <img src="art/happenv-logo.png" alt="Happenv" width="400">
    </a>
</p>
