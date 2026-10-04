# Changelog

All notable changes to `filament-access-control` are documented in this file. Each section is written automatically from the GitHub release notes when a release is published — do not edit it by hand.

## v3.5.1 - 2026-10-04

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Other

* fix: loud string plugin actions and sibling cell-note buttons by @webard in https://github.com/happenv-com/filament-access-control/pull/16

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.5.0...v3.5.1

## v3.5.0 - 2026-10-04

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Other

* feat: cell notes, plugin actions and a refusal seam for permission writes by @webard in https://github.com/happenv-com/filament-access-control/pull/15

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.4.0...v3.5.0

## v3.4.0 - 2026-10-01

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Features

* feat: escalation and self-editing guards, on by default (3.4.0) by @webard in https://github.com/happenv-com/filament-access-control/pull/14

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.3.0...v3.4.0

## v3.3.0 - 2026-09-30

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Features

* feat: the permission column stays at least 250 px wide by @webard in https://github.com/happenv-com/filament-access-control/pull/13

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.2.0...v3.3.0

## v3.2.0 - 2026-09-30

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Features

* feat: a group is a row of the table — counters beside its name, folded groups not drawn by @webard in https://github.com/happenv-com/filament-access-control/pull/12
* feat: a role picker for the matrix; keep its permission column and role header in view by @webard in https://github.com/happenv-com/filament-access-control/pull/11

#### Fixes

* fix: a wide permission table scrolls inside its frame instead of stretching the page by @webard in https://github.com/happenv-com/filament-access-control/pull/10

#### Other

* docs: screenshots of the access control page and the user editor by @webard in https://github.com/happenv-com/filament-access-control/pull/9

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.1.1...v3.2.0

## v3.1.1 - 2026-09-30

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Fixes

* fix: show In effect only where it can differ from Granted by @webard in https://github.com/happenv-com/filament-access-control/pull/8

#### Other

* docs: banner, key features and configuration along the package skeleton's README by @webard in https://github.com/happenv-com/filament-access-control/pull/7

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.1.0...v3.1.1

## v3.0.1 - 2026-09-29

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Other

* fix: accessible role cells, surface-aware Dependencies column (3.0.1) by @webard in https://github.com/happenv-com/filament-access-control/pull/5

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v3.0.0...v3.0.1

## v3.0.0 - 2026-09-29

<!-- Release notes generated using configuration in .github/release.yml at 3.x -->
### What's Changed

#### Features

* feat: ship every locale Filament ships by @webard in https://github.com/happenv-com/filament-access-control/pull/2

#### Other

* feat: 3.x — rules, conditions and graphs on the permission screens by @webard in https://github.com/happenv-com/filament-access-control/pull/4

### New Contributors

* @webard made their first contribution in https://github.com/happenv-com/filament-access-control/pull/2

**Full Changelog**: https://github.com/happenv-com/filament-access-control/compare/v2.0.0...v3.0.0

## v2.0.0 - 2026-09-29

The first release: the Filament screens for [happenv-com/laravel-access-control](https://github.com/happenv-com/laravel-access-control) 2.x. The package's major follows the library's.

### Features

- **Roles × permissions matrix** — a Filament table with modules as collapsible groups, a row per subject and per verb, a column per role; a subject's row grants or clears all of its verbs. Registered by the plugin as the **Access control** page with *Add role* and *Delete role*, or placed anywhere with `PermissionMatrix`.
- **`PermissionEditor`** — the permissions of one role or one user, for any schema: a form section, a tab, a page of its own. Saves independently of the form around it; for a user it lists the roles that already grant each permission.
- **Live or deferred saving** — every click written at once, or staged until *Save permissions* (with *Discard* and a warning before leaving). `->deferred()` on the plugin or per component.
- **Counters** — `->counters()` puts what each role holds of a group into the group's header.
- **Safe concurrent edits** — every write re-reads the record under a row lock and replays the operator's intent; `PermissionsUpdated` is dispatched with what actually changed.
- **Authorization** — Laravel abilities, policies or access-control permission enums per operation; a voter's refusal reaches the operator in their language.
- **Surfaces** — narrow a screen to what a surface offers; grants held outside it stay listed and revocable.
- **`PermissionSelector`** — the catalogue as a form field saved with the form.
- Translations: English, Polish, German.

### Requirements

PHP 8.3 – 8.5 · Laravel 12, 13 · Filament 4, 5 · laravel-access-control ^2.3
