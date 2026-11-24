<?php

declare(strict_types=1);

$arguments = $_SERVER['argv'] ?? [];
if (count($arguments) !== 6) {
    fwrite(STDERR, "Usage: php tools/generate-idna-data.php IdnaMappingTable.txt DerivedJoiningType.txt Scripts.txt DerivedBidiClass.txt output.php\n");
    exit(1);
}

[$script, $mappingPath, $joiningTypePath, $scriptsPath, $bidiPath, $outputPath] = $arguments;
unset($script);

$expectedChecksums = [
    $mappingPath => 'a03b1eb38032268c696406a83f0972d6a815acd2c8d4151d42ec0fda70ffced1',
    $joiningTypePath => 'e2408ff2c92b175b0f7bf62c989bbb54c7b077528fe31f8d69b96fa09e7d61ed',
    $scriptsPath => '0071fd81b6aeae25f6e8bce8efec3066a6476a91b49bdb2f52dc76e817862a6a',
    $bidiPath => 'd9e23222522551348ea1ccfbb4f62efbf98982afb95840f8959c08ed992c5607',
];
foreach ($expectedChecksums as $path => $checksum) {
    if (hash_file('sha256', $path) !== $checksum) {
        fwrite(STDERR, sprintf("Unexpected checksum for %s.\n", $path));
        exit(1);
    }
}

$readLines = static function (string $path): array {
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        fwrite(STDERR, sprintf("Unable to read %s.\n", $path));
        exit(1);
    }

    return $lines;
};
$parseRange = static function (string $value): array {
    $parts = explode('..', trim($value));
    $start = intval($parts[0], 16);

    return [$start, isset($parts[1]) ? intval($parts[1], 16) : $start];
};
$parseData = static function (string $line): ?array {
    $data = trim(explode('#', $line, 2)[0]);
    if ($data === '') {
        return null;
    }

    return array_map('trim', explode(';', $data));
};

/** @var array<int, int|list<int>> $mappings */
$mappings = [];
$ignored = [];
$idnaDisallowed = [];
foreach ($readLines($mappingPath) as $line) {
    $fields = $parseData($line);
    if ($fields === null) {
        continue;
    }
    [$start, $end] = $parseRange($fields[0]);
    $status = $fields[1];
    $idnaStatus = $fields[3] ?? '';
    if ($status === 'mapped') {
        $mapping = $fields[2] === ''
            ? []
            : array_map(static fn (string $value): int => intval($value, 16), preg_split('/\s+/', $fields[2]) ?: []);
        for ($codePoint = $start; $codePoint <= $end; ++$codePoint) {
            $mappings[$codePoint] = count($mapping) === 1 ? $mapping[0] : $mapping;
        }
    } elseif ($status === 'ignored') {
        $ignored[] = [$start, $end];
    }
    if ($status === 'disallowed' || in_array($idnaStatus, ['NV8', 'XV8'], true)) {
        $idnaDisallowed[] = [$start, $end];
    }
}

/** @var array<string, list<array{int, int}>> $joiningTypes */
$joiningTypes = array_fill_keys(['L', 'D', 'R', 'T'], []);
foreach ($readLines($joiningTypePath) as $line) {
    $fields = $parseData($line);
    if ($fields === null || !isset($joiningTypes[$fields[1]])) {
        continue;
    }
    [$start, $end] = $parseRange($fields[0]);
    $joiningTypes[$fields[1]][] = [$start, $end];
}

/** @var array<string, list<array{int, int}>> $scriptRanges */
$scriptRanges = array_fill_keys(['Greek', 'Hebrew', 'Hiragana', 'Katakana', 'Han'], []);
foreach ($readLines($scriptsPath) as $line) {
    $fields = $parseData($line);
    if ($fields === null || !isset($scriptRanges[$fields[1]])) {
        continue;
    }
    [$start, $end] = $parseRange($fields[0]);
    $scriptRanges[$fields[1]][] = [$start, $end];
}

/** @var array<string, list<array{int, int}>> $bidiClasses */
$bidiClasses = array_fill_keys(['L', 'R', 'AL', 'AN', 'EN', 'ES', 'CS', 'ET', 'ON', 'BN', 'NSM'], []);
foreach ($readLines($bidiPath) as $line) {
    $fields = $parseData($line);
    if ($fields === null || !isset($bidiClasses[$fields[1]])) {
        continue;
    }
    [$start, $end] = $parseRange($fields[0]);
    $bidiClasses[$fields[1]][] = [$start, $end];
}

/**
 * @param list<array{int, int}> $ranges
 *
 * @return list<array{int, int}>
 */
$mergeRanges = static function (array $ranges): array {
    $merged = [];
    foreach ($ranges as [$start, $end]) {
        $lastIndex = array_key_last($merged);
        if ($lastIndex !== null && $start <= $merged[$lastIndex][1] + 1) {
            $merged[$lastIndex][1] = max($merged[$lastIndex][1], $end);

            continue;
        }
        $merged[] = [$start, $end];
    }

    return $merged;
};
$ignored = $mergeRanges($ignored);
$idnaDisallowed = $mergeRanges($idnaDisallowed);
/** @param array<string, list<array{int, int}>> $groups */
$mergeGroups = static function (array $groups) use ($mergeRanges): array {
    foreach ($groups as &$ranges) {
        $ranges = $mergeRanges($ranges);
    }
    unset($ranges);

    return $groups;
};
$joiningTypes = $mergeGroups($joiningTypes);
$scriptRanges = $mergeGroups($scriptRanges);
$bidiClasses = $mergeGroups($bidiClasses);

$formatInteger = static fn (int $value): string => sprintf('0x%X', $value);
$formatRanges = static function (array $ranges) use ($formatInteger): string {
    $lines = '';
    foreach ($ranges as [$start, $end]) {
        $lines .= sprintf("        [%s, %s],\n", $formatInteger($start), $formatInteger($end));
    }

    return $lines;
};
$formatNamedRanges = static function (array $groups) use ($formatRanges): string {
    $lines = '';
    foreach ($groups as $name => $ranges) {
        $lines .= sprintf("        '%s' => [\n%s        ],\n", $name, $formatRanges($ranges));
    }

    return $lines;
};

$output = <<<'PHP'
<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

/**
 * Generated from Unicode 18.0.0 IDNA and property data.
 *
 * Unicode data is distributed under the Unicode License v3; see UNICODE-LICENSE.
 *
 * @internal
 * @codeCoverageIgnore generated Unicode data
 */
final class IdnaData2008
{
    public const UNICODE_VERSION = '18.0.0';

    /** @var array<int, int|list<int>> */
    public const MAPPINGS = [
PHP;
$output .= "\n";
foreach ($mappings as $codePoint => $mapping) {
    $formattedMapping = is_int($mapping)
        ? $formatInteger($mapping)
        : sprintf('[%s]', implode(', ', array_map($formatInteger, $mapping)));
    $output .= sprintf("        %s => %s,\n", $formatInteger($codePoint), $formattedMapping);
}
$output .= "    ];\n\n    /** @var list<array{int, int}> */\n    public const IGNORED = [\n";
$output .= $formatRanges($ignored);
$output .= "    ];\n\n    /** @var list<array{int, int}> */\n    public const IDNA_DISALLOWED = [\n";
$output .= $formatRanges($idnaDisallowed);
$output .= "    ];\n\n    /** @var array<string, list<array{int, int}>> */\n    public const JOINING_TYPES = [\n";
$output .= $formatNamedRanges($joiningTypes);
$output .= "    ];\n\n    /** @var array<string, list<array{int, int}>> */\n    public const SCRIPTS = [\n";
$output .= $formatNamedRanges($scriptRanges);
$output .= "    ];\n\n    /** @var array<string, list<array{int, int}>> */\n    public const BIDI_CLASSES = [\n";
$output .= $formatNamedRanges($bidiClasses);
$output .= "    ];\n}\n";

if (file_put_contents($outputPath, $output) === false) {
    fwrite(STDERR, "Unable to write generated IDNA data.\n");
    exit(1);
}
