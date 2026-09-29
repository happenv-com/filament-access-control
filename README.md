# Filament Access Control

[![Latest Version](https://img.shields.io/github/v/release/happenv-com/filament-access-control?style=flat-square&label=version)](https://github.com/happenv-com/filament-access-control/releases)
[![Tests](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/tests.yml?label=tests&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/tests.yml)
[![PHPStan](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/phpstan.yml?label=phpstan&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/phpstan.yml)
[![Quality](https://img.shields.io/github/actions/workflow/status/happenv-com/filament-access-control/quality.yml?label=code%20quality&style=flat-square)](https://github.com/happenv-com/filament-access-control/actions/workflows/quality.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/happenv-com/filament-access-control.svg?style=flat-square)](https://packagist.org/packages/happenv-com/filament-access-control)
[![License](https://img.shields.io/github/license/happenv-com/filament-access-control.svg?style=flat-square)](LICENSE.md)

Filament UI for happenv-com/laravel-access-control: a roles × permissions matrix and a permission editor for one role or user, saving live or on demand.

<!--
  One or two paragraphs: what the package does and for whom. Link the library
  it builds on, if any. Then show the smallest useful example.
-->

```php
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;

$panel->plugin(FilamentAccessControlPlugin::make());
```

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
| Filament | 5         |

## Installation

Install the package via Composer:

```bash
composer require happenv-com/filament-access-control
```

> [!IMPORTANT]
> If you have not set up a custom theme and are using Filament Panels, follow the instructions in the [Filament docs](https://filamentphp.com/docs/5.x/styling/overview#creating-a-custom-theme) first.

Add the package's views to your theme's CSS file, so Tailwind generates the classes they use:

```css
@source '../../../../vendor/happenv-com/filament-access-control/resources/**/*.blade.php';
```

Register the plugin in your panel provider:

```php
use Happenv\FilamentAccessControl\FilamentAccessControlPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugin(FilamentAccessControlPlugin::make());
}
```

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-access-control-config"
```

<!-- Show the published config, or a table of the options that matter. -->

Optionally, publish the views and translations:

```bash
php artisan vendor:publish --tag="filament-access-control-views"
php artisan vendor:publish --tag="filament-access-control-translations"
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
composer phpstan       # static analysis
composer cs            # fix code style: composer normalize, Rector, Pint
composer ci            # everything CI checks, locally
```

## Upgrading

Breaking changes and how to migrate are described in [UPGRADING](UPGRADING.md) for every major version.

## Changelog

See [CHANGELOG](CHANGELOG.md) and [GitHub releases](https://github.com/happenv-com/filament-access-control/releases) for what has changed recently.

## Contributing

See [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Happenv sp. z o.o.](https://happenv.com)
- [webard](https://github.com/webard)
- [All contributors](../../contributors)

## License

The MIT License (MIT). See [License File](LICENSE.md) for more information.

---

<p align="center">
    <a href="https://happenv.com">
        <img src="art/happenv-logo.png" alt="Happenv" width="400">
    </a>
</p>
