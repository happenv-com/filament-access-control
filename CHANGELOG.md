# Changelog

All notable changes to `filament-access-control` are documented in this file. Each section is written automatically from the GitHub release notes when a release is published — do not edit it by hand.

## v3.0.0 - Unreleased

### Features

- Cells show what laravel-access-control 3 resolves: in effect, implied, missing requirement, blocked by a conflict, restricted, condition unmet, not granted — with the permissions involved in the tooltip. Staged changes are resolved before they are saved.
- A *Dependencies* column: every rule in both directions, with its reason, and every condition.
- `#[RequiresMFA]`: a condition met only by an account with multi-factor authentication enabled on the panel; fails closed.
- The user editor's *In effect* column and a callout for unmet conditions.
- Declaration problems listed above the screens and marked on their rows — `->declarationProblems()`.
- A *Permission graph* modal (Mermaid, lazily loaded) — `->diagrams()`.
- Search also matches the permissions a rule ties to.

### Requirements

- happenv-com/laravel-access-control 3.1+.

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
