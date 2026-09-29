#!/usr/bin/env php
<?php

/*
 * Turns this template into a Happenv or Sellero package: replaces the
 * placeholders, removes what the chosen kind of package does not need and
 * configures CI for a public (GitHub-hosted runners, Packagist) or a private
 * (self-hosted runners, Packeton) package.
 *
 * Run it once, right after creating the repository from the template:
 *
 *     php ./configure.php
 */

$organizations = [
    'happenv' => ['github' => 'happenv-com', 'vendor' => 'happenv-com', 'namespace' => 'Happenv', 'visibility' => 'public'],
    'sellero' => ['github' => 'sellero-org', 'vendor' => 'sellero', 'namespace' => 'Sellero', 'visibility' => 'private'],
];

$kinds = [
    'plugin' => 'Filament panel plugin',
    'forms' => 'Filament Forms components only',
    'tables' => 'Filament Tables components only',
    'theme' => 'Filament theme',
    'laravel' => 'Laravel package, no Filament',
];

$organizationKey = choice('Organization', array_keys($organizations), 'happenv');
$organization = $organizations[$organizationKey];
$githubOrg = ask('GitHub organization', $organization['github']);
$vendorSlug = slugify(ask('Composer vendor', $organization['vendor']));
$vendorNamespace = ask('Vendor namespace', $organization['namespace']);

writeln('');
foreach ($kinds as $kind => $label) {
    writeln("  \e[0;36m{$kind}\e[0m — {$label}");
}
$kind = choice('Kind of package', array_keys($kinds), 'plugin');
$isFilament = $kind !== 'laravel';
$isTheme = $kind === 'theme';

$packageName = ask('Package name', basename(getcwd()));
$packageSlug = slugify($packageName);
// Spatie's package tools name the config file, the translation namespace and
// the publish tags after the package name without a `laravel-` prefix.
$packageShortName = str_starts_with($packageSlug, 'laravel-') ? substr($packageSlug, strlen('laravel-')) : $packageSlug;
$packageTitle = ask('Package title', ucwords(str_replace(['-', '_'], ' ', $packageSlug)));

$className = ask('Class name', titleCase($packageName));
$description = ask('Package description', "This is my package $packageSlug");

$isPrivate = choice('Visibility', ['public', 'private'], $organization['visibility']) === 'private';
$hasAssets = $isTheme || ($isFilament && confirm('Does the package ship JavaScript or CSS built with npm?', true));
$hasBrowserTests = $isFilament && confirm('Add browser tests (Pest + Playwright)?');

writeln("\r");
writeln('------');
writeln("Package    : \e[0;36m$vendorSlug/$packageSlug\e[0m" . ($description ? " <{$description}>" : ''));
writeln("Repository : \e[0;36mgithub.com/$githubOrg/$packageSlug\e[0m");
writeln("Title      : \e[0;36m$packageTitle\e[0m");
writeln("Namespace  : \e[0;36m$vendorNamespace\\$className\e[0m");
writeln("Kind       : \e[0;36m{$kinds[$kind]}\e[0m");
writeln('Visibility : ' . ($isPrivate
    ? "\e[0;33mprivate\e[0m (self-hosted runners, Packeton, proprietary license)"
    : "\e[0;32mpublic\e[0m (GitHub-hosted runners, Packagist, MIT license)"));
writeln('npm assets : ' . ($hasAssets ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('Browser    : ' . ($hasBrowserTests ? "\e[0;32mYes" : "\e[0;31mNo") . "\e[0m");
writeln('------');
writeln("\r");
writeln('This script will replace the above values in all relevant files in the project directory.');
writeln("\r");

if (! confirm('Modify files?', true)) {
    exit(1);
}

// Kind of package -------------------------------------------------------------

if ($kind !== 'plugin') {
    safeUnlink(__DIR__ . '/src/SkeletonPlugin.php');
    safeUnlink(__DIR__ . '/tests/Feature/PluginTest.php');
}

if (! $isTheme) {
    safeUnlink(__DIR__ . '/src/SkeletonTheme.php');
}

match ($kind) {
    'forms' => setupForComponentsOnly('filament/forms'),
    'tables' => setupForComponentsOnly('filament/tables'),
    'plugin', 'theme' => removeComposerDeps(['filament/forms', 'filament/tables'], 'require'),
    'laravel' => setupForLaravel(),
};

if ($isTheme) {
    setupForTheme();
}

if (! $hasAssets) {
    removeDirectory(__DIR__ . '/bin');
    removeDirectory(__DIR__ . '/resources/css');
    removeDirectory(__DIR__ . '/resources/dist');
    removeDirectory(__DIR__ . '/resources/js');
    safeUnlink(__DIR__ . '/.prettierrc');
    safeUnlink(__DIR__ . '/.prettierignore');
    safeUnlink(__DIR__ . '/.github/workflows/assets.yml');

    if (! $hasBrowserTests) {
        safeUnlink(__DIR__ . '/package.json');
        safeUnlink(__DIR__ . '/.npmrc');
        safeUnlink(__DIR__ . '/.nvmrc');
    } else {
        updatePackageJson(fn (array $data): array => ['private' => true, 'type' => 'module']);
    }
}

if ($hasBrowserTests) {
    updateComposerJson(function (array $data): array {
        $data['require-dev']['pestphp/pest-plugin-browser'] = '^4.0 || ^5.0';
        ksort($data['require-dev']);

        return $data;
    });
    updatePackageJson(function (array $data): array {
        $data['devDependencies']['playwright'] = '^1.55';
        ksort($data['devDependencies']);

        return $data;
    });
} else {
    removeDirectory(__DIR__ . '/tests/Browser');
    safeUnlink(__DIR__ . '/.github/workflows/browser-tests.yml');
    updateComposerJson(function (array $data): array {
        unset($data['scripts']['test-browser'], $data['scripts-descriptions']['test-browser']);

        return $data;
    });
}

// README: the template becomes the package's README --------------------------

safeUnlink(__DIR__ . '/README.md');
rename(__DIR__ . '/README_TEMPLATE.md', __DIR__ . '/README.md');

// Placeholders ----------------------------------------------------------------

foreach (projectFiles() as $file) {
    $contents = file_get_contents($file);

    if (preg_match('/:vendor|:package|:github_org|:organization|VendorName|skeleton|migration_table_name/i', $contents) !== 1) {
        continue;
    }

    replaceInFile($file, [
        ':github_org' => $githubOrg,
        ':organization' => $organizationKey,
        ':vendor_slug' => $vendorSlug,
        'VendorName' => $vendorNamespace,
        ':package_title' => $packageTitle,
        ':package_short_name' => $packageShortName,
        ':package_slug' => $packageSlug,
        ':package_description' => $description,
        'Skeleton' => $className,
        'skeleton' => $packageSlug,
        'migration_table_name' => titleSnake($packageSlug),
    ]);

    match (true) {
        str_contains($file, determineSeparator('src/Skeleton.php')) => rename($file, determineSeparator('./src/' . $className . '.php')),
        str_contains($file, determineSeparator('src/SkeletonServiceProvider.php')) => rename($file, determineSeparator('./src/' . $className . 'ServiceProvider.php')),
        // The theme class is `Skeleton` itself (src/Skeleton.php is removed for a theme).
        str_contains($file, determineSeparator('src/SkeletonTheme.php')) => rename($file, determineSeparator('./src/' . $className . '.php')),
        str_contains($file, determineSeparator('src/SkeletonPlugin.php')) => rename($file, determineSeparator('./src/' . $className . 'Plugin.php')),
        str_contains($file, determineSeparator('src/Facades/Skeleton.php')) => rename($file, determineSeparator('./src/Facades/' . $className . '.php')),
        str_contains($file, determineSeparator('src/Commands/SkeletonCommand.php')) => rename($file, determineSeparator('./src/Commands/' . $className . 'Command.php')),
        str_contains($file, determineSeparator('src/Testing/TestsSkeleton.php')) => rename($file, determineSeparator('./src/Testing/Tests' . $className . '.php')),
        str_contains($file, determineSeparator('database/migrations/create_skeleton_table.php.stub')) => rename($file, determineSeparator('./database/migrations/create_' . titleSnake($packageSlug) . '_table.php.stub')),
        str_contains($file, determineSeparator('config/skeleton.php')) => rename($file, determineSeparator('./config/' . $packageShortName . '.php')),
        str_contains($file, determineSeparator('resources/lang/en/skeleton.php')) => rename($file, determineSeparator('./resources/lang/en/' . $packageShortName . '.php')),
        default => null,
    };
}

// Marker blocks ---------------------------------------------------------------
//
// Files carry `@<name>-start` / `@<name>-end` marker lines (`# …` in YAML,
// `// …` in PHP, `<!-- … -->` in Markdown and XML). A block whose condition
// holds keeps its content and loses the markers; any other block disappears.

$blocks = [
    'public' => ! $isPrivate,
    'private' => $isPrivate,
    'assets' => $hasAssets,
    'npm' => $hasAssets || $hasBrowserTests,
    'filament' => $isFilament,
    'laravel' => ! $isFilament,
    'plugin' => $kind === 'plugin',
    'browser' => $hasBrowserTests,
    'no-browser' => ! $hasBrowserTests,
];

foreach (projectFiles() as $file) {
    $contents = file_get_contents($file);

    foreach ($blocks as $name => $keep) {
        $contents = processBlocks($contents, $name, $keep);
    }

    // A removed block leaves its surrounding blank lines behind.
    $contents = preg_replace("/\n{3,}/", "\n\n", $contents);

    file_put_contents($file, $contents);
}

foreach (glob(__DIR__ . '/.github/workflows/*.yml') as $workflow) {
    $contents = file_get_contents($workflow);

    // An `env:` whose every entry sat in a removed block.
    $contents = preg_replace('/^([ \t]+)env:\n(?!\1[ \t])/m', '', $contents);

    if (! $isFilament) {
        $contents = str_replace(' - F${{ matrix.filament }}', '', $contents);
    }

    // Private repositories run CI on the organization's self-hosted runners.
    if ($isPrivate) {
        $contents = str_replace('runs-on: ubuntu-latest', 'runs-on: self-hosted', $contents);
    }

    file_put_contents($workflow, $contents);
}

// Public / private ------------------------------------------------------------

if ($isPrivate) {
    safeUnlink(__DIR__ . '/LICENSE.md');
    safeUnlink(__DIR__ . '/.github/CONTRIBUTING.md');
    removeDirectory(__DIR__ . '/.github/ISSUE_TEMPLATE');

    foreach (glob(__DIR__ . '/src/*ServiceProvider.php') as $provider) {
        file_put_contents($provider, preg_replace('/\R\s*->askToStarRepoOnGitHub\([^)]*\)/', '', file_get_contents($provider)));
    }

    updateComposerJson(function (array $data): array {
        $data['license'] = 'proprietary';
        $data['repositories'] = [
            ['type' => 'composer', 'url' => 'https://packeton.happenv.com'],
        ];
        unset($data['support']['security']);

        return $data;
    });
} else {
    // GitHub-hosted runners are free for public repositories and not a shared
    // resource, so there is nothing to clean up after a merged PR.
    safeUnlink(__DIR__ . '/.github/workflows/cancel-pr-runs.yml');
}

writeln("\r");
writeln("\e[0;32mFiles modified.\e[0m");

// `composer cs` right after the install: the new namespace sorts differently
// from the placeholder one, so Pint has imports to reorder before CI is green.
if (confirm('Execute `composer update` and `composer cs`?', true)) {
    passthru('composer update');
    passthru('composer cs');
}

if (($hasAssets || $hasBrowserTests) && confirm('Execute `npm install`' . ($hasAssets ? ' and `npm run build`' : '') . '? (package-lock.json' . ($hasAssets ? ' and resources/dist' : '') . ' must be committed)', true)) {
    passthru('npm install');

    if ($hasAssets) {
        passthru('npm run format');
        passthru('npm run build');
    }
}

if (confirm('Let this script delete itself?', true)) {
    unlink(__FILE__);
}

writeln("\r");
writeln("\e[1;37mNext steps\e[0m");
writeln('- Fill in README.md: description, Key features, Usage.');
writeln('- Push to a `1.x` branch and make it the default branch.');
if ($isPrivate) {
    writeln('- Add the COMPOSER_PACKETON_TOKEN secret for Actions AND for Dependabot, and register the repository in Packeton.');
} else {
    writeln('- Submit the package to Packagist and enable the GitHub hook.');
}
if ($hasBrowserTests) {
    writeln('- Install the browser once: npx playwright install chromium');
}

// Helpers ---------------------------------------------------------------------

function ask(string $question, string $default = ''): string
{
    $def = $default ? "\e[0;33m ($default)" : '';
    $answer = readline("\e[0;32m" . $question . $def . ": \e[0m");

    if (! $answer) {
        return $default;
    }

    return $answer;
}

function confirm(string $question, bool $default = false): bool
{
    $answer = ask($question, ($default ? 'Y/n' : 'y/N'));

    if (strtolower($answer) === 'y/n') {
        return $default;
    }

    return strtolower($answer) === 'y';
}

/**
 * @param  list<string>  $options
 */
function choice(string $question, array $options, string $default): string
{
    while (true) {
        $answer = strtolower(ask($question . ' [' . implode('/', $options) . ']', $default));

        if (in_array($answer, $options, true)) {
            return $answer;
        }

        writeln("\e[0;31mPlease answer one of: " . implode(', ', $options) . "\e[0m");
    }
}

function writeln(string $line): void
{
    echo $line . PHP_EOL;
}

function slugify(string $subject): string
{
    return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
}

function titleCase(string $subject): string
{
    return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', $subject)));
}

function titleSnake(string $subject, string $replace = '_'): string
{
    return str_replace(['-', '_'], $replace, $subject);
}

function replaceInFile(string $file, array $replacements): void
{
    $contents = file_get_contents($file);

    file_put_contents(
        $file,
        str_replace(
            array_keys($replacements),
            array_values($replacements),
            $contents
        )
    );
}

function processBlocks(string $contents, string $name, bool $keep): string
{
    $marker = static fn (string $edge): string => '^[ \t]*(?:#|\/\/|<!--)[ \t]*@' . preg_quote($name, '/') . '-' . $edge . '\b[^\n]*(?:\n|\z)';

    return preg_replace_callback(
        '/' . $marker('start') . '(.*?)' . $marker('end') . '/ms',
        static fn (array $matches): string => $keep ? $matches[1] : '',
        $contents
    );
}

function updateComposerJson(callable $callback): void
{
    $data = json_decode(file_get_contents(__DIR__ . '/composer.json'), true);

    file_put_contents(__DIR__ . '/composer.json', json_encode($callback($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
}

function removeComposerDeps(array $names, string $location): void
{
    updateComposerJson(function (array $data) use ($names, $location): array {
        foreach (array_keys($data[$location] ?? []) as $name) {
            if (in_array($name, $names, true)) {
                unset($data[$location][$name]);
            }
        }

        return $data;
    });
}

function updatePackageJson(callable $callback): void
{
    $data = json_decode(file_get_contents(__DIR__ . '/package.json'), true);

    file_put_contents(__DIR__ . '/package.json', json_encode($callback($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
}

/**
 * The package itself needs only the Forms or Tables package; its tests still
 * boot a whole panel (tests/Fixtures/AdminPanelProvider), so filament/filament
 * stays as a development dependency.
 */
function setupForComponentsOnly(string $package): void
{
    updateComposerJson(function (array $data) use ($package): array {
        $data['require-dev']['filament/filament'] = $data['require']['filament/filament'];
        ksort($data['require-dev']);

        foreach (['filament/filament', 'filament/forms', 'filament/tables'] as $name) {
            if ($name !== $package) {
                unset($data['require'][$name]);
            }
        }

        return $data;
    });
}

function setupForLaravel(): void
{
    removeComposerDeps(['filament/filament', 'filament/forms', 'filament/tables'], 'require');
    removeComposerDeps(['pestphp/pest-plugin-livewire'], 'require-dev');

    updateComposerJson(function (array $data): array {
        $data['require']['illuminate/contracts'] = '^12.0 || ^13.0';
        ksort($data['require']);
        $data['keywords'] = array_values(array_diff($data['keywords'], ['filamentphp', 'filament', 'filament-plugin']));

        return $data;
    });

    removeDirectory(__DIR__ . '/src/Testing');
    removeDirectory(__DIR__ . '/bin');
    removeDirectory(__DIR__ . '/resources/css');
    removeDirectory(__DIR__ . '/resources/dist');
    removeDirectory(__DIR__ . '/resources/js');
    safeUnlink(__DIR__ . '/tests/Fixtures/AdminPanelProvider.php');
}

function setupForTheme(): void
{
    // A theme is one stylesheet compiled by Tailwind, no JavaScript bundle.
    updatePackageJson(function (array $data): array {
        $data['scripts'] = [
            'build' => 'npx @tailwindcss/cli --input resources/css/index.css --output resources/dist/skeleton.css --minify',
            'dev' => 'npx @tailwindcss/cli --input resources/css/index.css --output resources/dist/skeleton.css --watch',
            'format' => 'prettier --write resources/css',
            'lint' => 'prettier --check resources/css',
        ];
        $data['devDependencies'] = [
            '@tailwindcss/cli' => '^4.1',
            'prettier' => '^3.6.0',
            'tailwindcss' => '^4.1',
        ];

        return $data;
    });

    file_put_contents(__DIR__ . '/resources/css/index.css', "@import '../../vendor/filament/filament/resources/css/theme.css';\n");

    replaceInFile(__DIR__ . '/.github/workflows/assets.yml', ["      - 'bin/build.js'\n" => '']);

    // No service provider, facade, command, translations or migrations.
    safeUnlink(__DIR__ . '/src/SkeletonServiceProvider.php');
    safeUnlink(__DIR__ . '/src/Skeleton.php');
    removeDirectory(__DIR__ . '/bin');
    removeDirectory(__DIR__ . '/config');
    removeDirectory(__DIR__ . '/database');
    removeDirectory(__DIR__ . '/stubs');
    removeDirectory(__DIR__ . '/resources/js');
    removeDirectory(__DIR__ . '/resources/lang');
    removeDirectory(__DIR__ . '/resources/views');
    removeDirectory(__DIR__ . '/src/Commands');
    removeDirectory(__DIR__ . '/src/Facades');
    removeDirectory(__DIR__ . '/src/Testing');
    safeUnlink(__DIR__ . '/tests/Feature/ServiceProviderTest.php');
    safeUnlink(__DIR__ . '/tests/Unit/TranslationsTest.php');

    updateComposerJson(function (array $data): array {
        unset($data['autoload']['psr-4']['VendorName\\Skeleton\\Database\\Factories\\'], $data['extra']['laravel']);

        return $data;
    });

    file_put_contents(__DIR__ . '/tests/TestCase.php', preg_replace(
        ['/^\s*SkeletonServiceProvider::class,\R/m', '/^use VendorName\\\\Skeleton\\\\SkeletonServiceProvider;\R/m', '/\R\s*Factory::guessFactoryNamesUsing\(.*?\);\R/s', '/^use Illuminate\\\\Database\\\\Eloquent\\\\Factories\\\\Factory;\R/m'],
        ['', '', "\n", ''],
        file_get_contents(__DIR__ . '/tests/TestCase.php')
    ));

    // config/ and database/ are gone; PHPStan refuses paths that do not exist.
    replaceInFile(__DIR__ . '/phpstan.neon.dist', [
        "        - config\n" => '',
        "        - database\n" => '',
    ]);
}

function safeUnlink(string $filename): void
{
    if (file_exists($filename) && is_file($filename)) {
        unlink($filename);
    }
}

function determineSeparator(string $path): string
{
    return str_replace('/', DIRECTORY_SEPARATOR, $path);
}

/**
 * Every text file of the project, hidden ones (.github) included.
 *
 * @return list<string>
 */
function projectFiles(): array
{
    $skip = ['.git', 'vendor', 'node_modules', 'art'];

    $iterator = new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator(
            new RecursiveDirectoryIterator('.', FilesystemIterator::SKIP_DOTS),
            static fn (SplFileInfo $file): bool => ! ($file->isDir() && in_array($file->getFilename(), $skip, true))
        )
    );

    $files = [];

    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getFilename() !== basename(__FILE__)) {
            $files[] = $file->getPathname();
        }
    }

    return $files;
}

function removeDirectory(string $dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (filetype($dir . '/' . $object) == 'dir') {
                    removeDirectory($dir . '/' . $object);
                } else {
                    unlink($dir . '/' . $object);
                }
            }
        }
        rmdir($dir);
    }
}
