<?php

declare(strict_types=1);

$arguments = $_SERVER['argv'] ?? [];
if (count($arguments) !== 5) {
    fwrite(
        STDERR,
        "Usage: php tools/generate-normalization-data.php VERSION UnicodeData.txt CompositionExclusions.txt output.php\n",
    );
    exit(1);
}

[$script, $unicodeVersion, $unicodeDataPath, $compositionExclusionsPath, $outputPath] = $arguments;
unset($script);

$versions = [
    '3.2.0' => [
        'class' => 'NormalizationData2003',
        'unicodeDataChecksum' => '5e444028b6e76d96f9dc509609c5e3222bf609056f35e5fcde7e6fb8a58cd446',
        'compositionExclusionsChecksum' => '1d3a450d0f39902710df4972ac4a60ec31fbcb54ffd4d53cd812fc1200c732cb',
        'unicodeDataUrl' => 'https://www.unicode.org/Public/3.2-Update/UnicodeData-3.2.0.txt',
        'compositionExclusionsUrl' =>
            'https://www.unicode.org/Public/3.2-Update/CompositionExclusions-3.2.0.txt',
    ],
    '18.0.0' => [
        'class' => 'NormalizationData2008',
        'unicodeDataChecksum' => '0736451de439ae7baf1425136617da495e09ee5afbe6e394374db7009ea08950',
        'compositionExclusionsChecksum' => 'c759b100e9ae8960ae6b50fc5765950a7581fd9f30d4ef95f68f25a6d97e818e',
        'unicodeDataUrl' => 'https://www.unicode.org/Public/18.0.0/ucd/UnicodeData.txt',
        'compositionExclusionsUrl' =>
            'https://www.unicode.org/Public/18.0.0/ucd/CompositionExclusions.txt',
    ],
];
$version = $versions[$unicodeVersion] ?? null;
if ($version === null) {
    fwrite(STDERR, sprintf("Unsupported Unicode version %s.\n", $unicodeVersion));
    exit(1);
}

$expectedChecksums = [
    $unicodeDataPath => $version['unicodeDataChecksum'],
    $compositionExclusionsPath => $version['compositionExclusionsChecksum'],
];
foreach ($expectedChecksums as $path => $expectedChecksum) {
    if (hash_file('sha256', $path) !== $expectedChecksum) {
        fwrite(STDERR, sprintf("Unexpected checksum for %s.\n", $path));
        exit(1);
    }
}

$unicodeData = file($unicodeDataPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$compositionExclusions = file($compositionExclusionsPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($unicodeData === false || $compositionExclusions === false) {
    fwrite(STDERR, "Unable to read the Unicode data files.\n");
    exit(1);
}

/** @var array<int, true> $excluded */
$excluded = [];
foreach ($compositionExclusions as $line) {
    $value = trim(explode('#', $line, 2)[0]);
    if ($value !== '') {
        $excluded[intval($value, 16)] = true;
    }
}

/** @var array<int, int> $combiningClasses */
$combiningClasses = [];
/** @var array<int, list<int>> $canonicalDecompositions */
$canonicalDecompositions = [];
/** @var array<int, list<int>> $compatibilityDecompositions */
$compatibilityDecompositions = [];
/** @var list<array{int, int}> $markRanges */
$markRanges = [];
/** @var array<string, list<array{int, int}>> $bidiRanges */
$bidiRanges = array_fill_keys(['L', 'R', 'AL'], []);
$rangeStart = null;
foreach ($unicodeData as $line) {
    $fields = explode(';', $line);
    $codePoint = intval($fields[0], 16);
    // UnicodeData represents large ranges with paired First/Last records.
    if (str_ends_with($fields[1], ', First>')) {
        $rangeStart = $codePoint;

        continue;
    }
    $start = $codePoint;
    if (str_ends_with($fields[1], ', Last>')) {
        if ($rangeStart === null || $rangeStart > $codePoint) {
            throw new RuntimeException('Invalid UnicodeData range');
        }
        $start = $rangeStart;
        $rangeStart = null;
    }
    if (str_starts_with($fields[2], 'M')) {
        $markRanges[] = [$start, $codePoint];
    }
    if (isset($bidiRanges[$fields[4]])) {
        $bidiRanges[$fields[4]][] = [$start, $codePoint];
    }
    $combiningClass = (int) $fields[3];
    if ($combiningClass !== 0) {
        for ($member = $start; $member <= $codePoint; ++$member) {
            $combiningClasses[$member] = $combiningClass;
        }
    }

    $decomposition = trim($fields[5]);
    if ($decomposition === '') {
        continue;
    }

    $decompositionParts = explode(' ', $decomposition);
    $isCompatibilityMapping = str_starts_with($decompositionParts[0], '<');
    if ($isCompatibilityMapping) {
        array_shift($decompositionParts);
    }
    $decomposedCodePoints = array_map(
        static fn (string $value): int => intval($value, 16),
        $decompositionParts,
    );
    if ($isCompatibilityMapping && $unicodeVersion === '3.2.0') {
        $compatibilityDecompositions[$codePoint] = $decomposedCodePoints;
    } elseif (!$isCompatibilityMapping) {
        $canonicalDecompositions[$codePoint] = $decomposedCodePoints;
    }
}

/** @var array<int, array<int, int>> $compositions */
$compositions = [];
foreach ($canonicalDecompositions as $composite => $decomposition) {
    if (
        count($decomposition) !== 2
        || isset($excluded[$composite])
        || ($combiningClasses[$decomposition[0]] ?? 0) !== 0
    ) {
        continue;
    }

    $compositions[$decomposition[0]][$decomposition[1]] = $composite;
}

ksort($combiningClasses);
ksort($canonicalDecompositions);
ksort($compatibilityDecompositions);
ksort($compositions);
foreach ($compositions as &$compositionsByStarter) {
    ksort($compositionsByStarter);
}
unset($compositionsByStarter);

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
$markRanges = $mergeRanges($markRanges);
foreach ($bidiRanges as &$ranges) {
    $ranges = $mergeRanges($ranges);
}
unset($ranges);

$formatInteger = static fn (int $value): string => sprintf('0x%X', $value);
$output = sprintf(
    <<<'PHP'
<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

/**
 * Generated from the Unicode Character Database %s.
 *
 * Unicode data is distributed under the Unicode License v3; see UNICODE-LICENSE.
 *
 * @see %s
 * @see %s
 *
 * @internal
 * @codeCoverageIgnore generated Unicode data
 */
final class %s
{
    public const UNICODE_VERSION = '%s';

    /** @var array<int, int> */
    public const COMBINING_CLASSES = [
PHP,
    $unicodeVersion,
    $version['unicodeDataUrl'],
    $version['compositionExclusionsUrl'],
    $version['class'],
    $unicodeVersion,
);
$output .= "\n";
foreach ($combiningClasses as $codePoint => $combiningClass) {
    $output .= sprintf("        %s => %d,\n", $formatInteger($codePoint), $combiningClass);
}
$output .= <<<'PHP'
    ];

    /** @var list<array{int, int}> */
    public const MARK_RANGES = [
PHP;
$output .= "\n";
foreach ($markRanges as [$start, $end]) {
    $output .= sprintf("        [%s, %s],\n", $formatInteger($start), $formatInteger($end));
}
$output .= "    ];\n";
if ($unicodeVersion === '3.2.0') {
    $output .= "\n    /** @var array<string, list<array{int, int}>> */\n    public const BIDI_RANGES = [\n";
    foreach ($bidiRanges as $class => $ranges) {
        $output .= sprintf("        '%s' => [\n", $class);
        foreach ($ranges as [$start, $end]) {
            $output .= sprintf("            [%s, %s],\n", $formatInteger($start), $formatInteger($end));
        }
        $output .= "        ],\n";
    }
    $output .= "    ];\n";
}
$output .= <<<'PHP'

    /** @var array<int, list<int>> */
    public const CANONICAL_DECOMPOSITIONS = [
PHP;
$output .= "\n";
foreach ($canonicalDecompositions as $codePoint => $decomposition) {
    $output .= sprintf(
        "        %s => [%s],\n",
        $formatInteger($codePoint),
        implode(', ', array_map($formatInteger, $decomposition)),
    );
}
$output .= <<<'PHP'
    ];

    /** @var array<int, list<int>> */
    public const COMPATIBILITY_DECOMPOSITIONS = [
PHP;
$output .= "\n";
foreach ($compatibilityDecompositions as $codePoint => $decomposition) {
    $output .= sprintf(
        "        %s => [%s],\n",
        $formatInteger($codePoint),
        implode(', ', array_map($formatInteger, $decomposition)),
    );
}
$output .= <<<'PHP'
    ];

    /** @var array<int, array<int, int>> */
    public const COMPOSITIONS = [
PHP;
$output .= "\n";
foreach ($compositions as $starter => $compositionsByStarter) {
    $output .= sprintf("        %s => [\n", $formatInteger($starter));
    foreach ($compositionsByStarter as $combining => $composite) {
        $output .= sprintf(
            "            %s => %s,\n",
            $formatInteger($combining),
            $formatInteger($composite),
        );
    }
    $output .= "        ],\n";
}
$output .= <<<'PHP'
    ];
}
PHP;
$output .= "\n";

if (file_put_contents($outputPath, $output) === false) {
    fwrite(STDERR, "Unable to write the generated normalization data.\n");
    exit(1);
}
