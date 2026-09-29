# Happenv Package Skeleton

The starting point — and the reference — for every Happenv and Sellero package: Filament plugins, Forms/Tables components, themes and plain Laravel packages, public or private. Based on [filamentphp/plugin-skeleton](https://github.com/filamentphp/plugin-skeleton), with our CI, security checks, test setup, asset pipeline, changelog and README conventions on top — the best of what filament-passkeys, filament-translatable, filament-turnstile, filament-enhanced-charts and filament-comments grew on their own.

> This README describes the template itself. `configure.php` replaces it with [`README_TEMPLATE.md`](README_TEMPLATE.md), which becomes the package's README.

## Creating a package

1. **Use this template** → create the repository in `happenv-com` or `sellero-org` (public or private).
2. Clone it and run the configurator:

   ```bash
   php ./configure.php
   ```

   It asks for:

   | Question           | Options                                                                                   |
   |--------------------|-------------------------------------------------------------------------------------------|
   | Organization       | `happenv` (`happenv-com/…`, `Happenv\`, public by default), `sellero` (`sellero/…` on `sellero-org`, `Sellero\`, private by default) |
   | Kind of package    | `plugin` (Filament panel plugin), `forms`, `tables` (components only), `theme`, `laravel` (no Filament) |
   | Name, title, class, description | —                                                                            |
   | Visibility         | `public` / `private` — see below                                                          |
   | npm assets         | JavaScript / CSS built into `resources/dist` (Filament kinds)                             |
   | Browser tests      | Pest browser plugin + Playwright, against a real test panel (Filament kinds)              |

   Then it replaces the placeholders, removes what the package does not need and runs `composer update`, `composer cs`, `npm install` and `npm run build` so the first commit is already green.

3. Rename the branch to `1.x`, push, and make `1.x` the default branch. One branch per major version (`1.x`, `2.x`, …); CI runs on pushes to `*.x`, `main` and `develop`.
4. Fill in `README.md`: description, **Key features**, Usage.
5. Public: submit to [Packagist](https://packagist.org/packages/submit). Private: register in [Packeton](https://packeton.happenv.com) and add the `COMPOSER_PACKETON_TOKEN` secret **twice** — as an Actions secret and as a Dependabot secret (Dependabot cannot read Actions secrets).

## Public vs private

|                           | public                                   | private                                                   |
|---------------------------|------------------------------------------|-----------------------------------------------------------|
| CI runners                | `ubuntu-latest` (GitHub-hosted, free)    | `self-hosted` (organization runners)                      |
| Distribution              | Packagist                                | Packeton (`repositories` in composer.json, `COMPOSER_AUTH` in CI, Dependabot registry) |
| License                   | MIT, `LICENSE.md`                        | `proprietary`, no license file                            |
| README                    | badges, `composer require`, Contributing | Packeton installation instructions, no badges             |
| CONTRIBUTING, issue forms | yes                                      | removed                                                   |
| `cancel-pr-runs.yml`      | removed                                  | yes — frees the shared runners when a PR is merged or turned into a draft |

Files carry `@<name>-start` / `@<name>-end` marker lines (`# …` in YAML, `// …` in PHP, `<!-- … -->` in Markdown and XML). `configure.php` keeps the content of the blocks whose condition holds and drops the rest:

| Marker                    | Kept when                            |
|---------------------------|--------------------------------------|
| `public` / `private`      | the visibility                       |
| `filament` / `laravel`    | a Filament kind / a plain Laravel package |
| `plugin`                  | a Filament panel plugin              |
| `assets`, `npm`           | npm-built assets / any npm tooling   |
| `browser` / `no-browser`  | browser tests on / off               |

## What every package gets

### GitHub Actions

All actions are pinned to a commit SHA, every workflow starts from `permissions: {}` or `contents: read`, checkouts do not persist credentials unless the job pushes, and PR jobs skip drafts.

| Workflow                    | What it guards                                                                                   | Runs on                                          |
|-----------------------------|--------------------------------------------------------------------------------------------------|--------------------------------------------------|
| `tests.yml`                 | Pest (Unit, Feature) across PHP 8.3–8.5 × Laravel 12–13 × Filament 5 × lowest/stable dependencies | push, PR                                         |
| `browser-tests.yml`         | Pest browser tests in Chromium (optional)                                                         | push, PR                                         |
| `phpstan.yml`               | Larastan, level 6, one leg per Filament major                                                    | PHP changes                                      |
| `quality.yml`               | `composer validate`, `composer normalize`, Rector, Pint, PSR-4 — reported, never auto-committed  | PHP / composer.json changes                      |
| `assets.yml`                | Prettier, and committed `resources/dist` matches `npm run build`                                 | JS / CSS changes                                 |
| `composer-audit.yml`        | known vulnerabilities and abandoned packages                                                     | composer.json changes, weekly                    |
| `trufflehog.yml`            | leaked secrets in the pushed / PR commit range                                                   | push, PR                                         |
| `zizmor.yml`                | GitHub Actions security audit (pinned zizmor, plus a non-blocking pedantic pass)                 | workflow changes                                 |
| `pr-title.yml`              | Conventional Commits PR titles (they become the squash commit and the release-notes line)        | PR                                               |
| `update-changelog.yml`      | writes the release notes into `CHANGELOG.md` on the release branch                               | release published                                |
| `dependabot-auto-merge.yml` | merges minor/patch Dependabot PRs once every other check has passed                               | Dependabot PR                                    |
| `cancel-pr-runs.yml`        | cancels leftover runs of merged / drafted PRs (private only)                                     | PR closed / converted to draft                   |

### Tests

- **Suites**: `tests/Unit`, `tests/Feature` and, optionally, `tests/Browser`. `composer test` runs the first two, `composer test-browser` the last.
- **`TestCase`**: SQLite in memory, an app key, the Laravel `users` table, a `Fixtures\User`, a real test panel with login (`Fixtures\AdminPanelProvider`) for Filament kinds, and the Livewire `DataStore` pin that older Filament releases need under Testbench.
- **Deprecations fail tests** when the package's own code raises them (`failOnDeprecation`, indirect ones ignored), so they are fixed before the next PHP / Laravel / Filament release turns them into errors.
- **`ArchTest`**: Pest's `php` and `security` presets, no debugging calls.
- **`TranslationsTest`**: every locale in `resources/lang` has exactly the keys English has.

### Assets

`bin/build.js` (esbuild) bundles every entry listed at its top — JavaScript and CSS — into `resources/dist`; `npm run dev` rebuilds on change. `resources/dist` is committed, and `assets.yml` rebuilds it with the Node version from `.nvmrc` and fails when the result differs. Prettier (`npm run format` / `npm run lint`) keeps JS and CSS formatted. A theme is built by the Tailwind CLI instead.

### Repository files

- `.github/dependabot.yml` — Actions, Composer and npm; weekly, 7-day cooldown, minor/patch grouped, `chore(deps)` titles.
- `.github/release.yml` — release-notes categories (Breaking changes, Features, Fixes, Dependencies, Other) by PR label.
- `.github/zizmor.yml` — reviewed zizmor exceptions.
- `.npmrc` — `min-release-age=7`, the npm counterpart of the Dependabot cooldown.
- `composer.json` — Happenv sp. z o.o. and webard as authors; `composer ci` (everything CI checks), `composer cs` (normalize, Rector, Pint), `composer test`, `test-browser`, `test-coverage`, `phpstan`.
- `phpstan.neon.dist` — Larastan level 6 with deprecation rules; unmatched baseline entries tolerated for the multi-Filament matrix.
- `rector.php` — library-safe sets (no privatization, no forced `final`).
- `UPGRADING.md` — one section per major version.
- `README_TEMPLATE.md` — the README structure every package follows: badges, description, **Key features**, Requirements, Installation, Configuration, Usage, Testing your application, Translations, Development, Upgrading, Changelog, Contributing, Security, Credits, License, and the Happenv logo at the bottom.

## Releasing a package

1. Merge PRs with Conventional Commits titles; label them (`breaking-change`, `enhancement`, `bug`) so the release notes group them.
2. Create a GitHub release on the `*.x` branch with a `vX.Y.Z` tag and **Generate release notes**.
3. `update-changelog.yml` commits the notes to `CHANGELOG.md`. Packagist / Packeton pick the tag up from the webhook.

## Bringing an existing package in line

Copy `.github/`, `.npmrc`, `.gitattributes`, `rector.php`, `pint.json`, `phpstan.neon.dist` and the `composer.json` scripts, dev dependencies and authors from this repository, resolve the visibility markers by hand, then adjust the `tests.yml` / `phpstan.yml` matrices to the versions the package supports. Restructure the README along `README_TEMPLATE.md` and restore `CHANGELOG.md` from the existing GitHub releases.
