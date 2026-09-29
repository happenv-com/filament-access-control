<?php

declare(strict_types=1);

/*
 * Keeps every locale's translation files in step with the English ones.
 *
 *   php bin/sync-locales.php              add every English line a locale lacks, in English
 *   php bin/sync-locales.php <json-dir>   ...and apply the translations in <json-dir>/<locale>.json
 *
 * A JSON file mirrors the PHP files: {"editor": {"columns": {"in_effect": "..."}}}. Every file is
 * written back in the English key order, so a diff shows only what changed.
 */

$root = dirname(__DIR__);
$translations = $argv[1] ?? null;

$export = static function (array $english, array $lines, int $depth) use (&$export): string {
    $indent = str_repeat('    ', $depth);
    $php = '';

    foreach ($english as $key => $line) {
        $php .= $indent . var_export($key, true) . ' => ';
        $php .= is_array($line)
            ? "[\n" . $export($line, is_array($lines[$key] ?? null) ? $lines[$key] : [], $depth + 1) . $indent . "],\n"
            : var_export(is_string($lines[$key] ?? null) ? $lines[$key] : $line, true) . ",\n";
    }

    return $php;
};

foreach (glob("{$root}/resources/lang/*", GLOB_ONLYDIR) ?: [] as $directory) {
    $locale = basename($directory);

    if ($locale === 'en') {
        continue;
    }

    $given = $translations !== null && is_file("{$translations}/{$locale}.json")
        ? json_decode((string) file_get_contents("{$translations}/{$locale}.json"), true, flags: JSON_THROW_ON_ERROR)
        : [];

    foreach (glob("{$root}/resources/lang/en/*.php") ?: [] as $englishFile) {
        $file = basename($englishFile, '.php');
        $current = is_file("{$directory}/{$file}.php") ? require "{$directory}/{$file}.php" : [];

        file_put_contents(
            "{$directory}/{$file}.php",
            "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n"
                . $export(require $englishFile, array_replace_recursive($current, $given[$file] ?? []), 1)
                . "];\n",
        );
    }
}
