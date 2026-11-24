<?php

declare(strict_types=1);

use Algo26\IdnaConvert\NamePrep\UnicodeNormalizer;

require dirname(__DIR__) . '/vendor/autoload.php';

$arguments = $_SERVER['argv'] ?? [];
if (count($arguments) !== 3) {
    fwrite(STDERR, "Usage: php tools/verify-normalization.php IDNA_VERSION NormalizationTest.txt\n");
    exit(1);
}

$idnVersion = (int) $arguments[1];
if (!in_array($idnVersion, [2003, 2008], true)) {
    fwrite(STDERR, "IDNA version must be either 2003 or 2008.\n");
    exit(1);
}

$lines = file($arguments[2], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($lines === false) {
    fwrite(STDERR, "Unable to read the normalization test data.\n");
    exit(1);
}

$parseSequence = static function (string $sequence): array {
    $sequence = trim($sequence);
    if ($sequence === '') {
        return [];
    }

    return array_map(
        static fn (string $value): int => intval($value, 16),
        preg_split('/\s+/', $sequence) ?: [],
    );
};

$normalizer = new UnicodeNormalizer($idnVersion);
$failures = 0;
$testCases = 0;
foreach ($lines as $lineNumber => $line) {
    $data = trim(explode('#', $line, 2)[0]);
    if ($data === '' || str_starts_with($data, '@')) {
        continue;
    }

    $columns = array_map($parseSequence, array_slice(explode(';', $data), 0, 5));
    if (count($columns) !== 5) {
        fwrite(STDERR, sprintf("Invalid test data at line %d.\n", $lineNumber + 1));
        exit(1);
    }

    [$source, $nfc, $nfd, $nfkc, $nfkd] = $columns;
    $checks = $idnVersion === 2003
        ? [
            [$normalizer->normalize($source), $nfkc, 'NFKC(c1)'],
            [$normalizer->normalize($nfc), $nfkc, 'NFKC(c2)'],
            [$normalizer->normalize($nfd), $nfkc, 'NFKC(c3)'],
            [$normalizer->normalize($nfkc), $nfkc, 'NFKC(c4)'],
            [$normalizer->normalize($nfkd), $nfkc, 'NFKC(c5)'],
        ]
        : [
            [$normalizer->normalize($source), $nfc, 'NFC(c1)'],
            [$normalizer->normalize($nfc), $nfc, 'NFC(c2)'],
            [$normalizer->normalize($nfd), $nfc, 'NFC(c3)'],
            [$normalizer->normalize($nfkc), $nfkc, 'NFC(c4)'],
            [$normalizer->normalize($nfkd), $nfkc, 'NFC(c5)'],
        ];

    foreach ($checks as [$actual, $expected, $description]) {
        ++$testCases;
        if ($actual !== $expected) {
            ++$failures;
            fwrite(
                STDERR,
                sprintf("%s failed at line %d.\n", $description, $lineNumber + 1),
            );
        }
    }
}

printf("Verified %d normalization assertions.\n", $testCases);
exit($failures === 0 ? 0 : 1);
