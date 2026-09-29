# Filament Access Control

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

## Security vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## Credits

- [Happenv sp. z o.o.](https://happenv.com)
- [webard](https://github.com/webard)

## License

Proprietary. Copyright © Happenv sp. z o.o. All rights reserved.

---

<p align="center">
    <a href="https://happenv.com">
        <img src="art/happenv-logo.png" alt="Happenv" width="400">
    </a>
</p>
