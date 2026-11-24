<?php

declare(strict_types=1);

use Algo26\IdnaConvert\Exception\AlreadyPunycodeException;
use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\Std3AsciiRulesViolationException;
use Algo26\IdnaConvert\ToIdn;
use Algo26\IdnaConvert\TranscodeUnicode\Ucs4Codec;

require dirname(__DIR__) . '/vendor/autoload.php';

$arguments = $_SERVER['argv'] ?? [];
if (count($arguments) !== 3) {
    fwrite(STDERR, "Usage: php tools/verify-idna.php IdnaTestV2.txt IdnaMappingTable.txt\n");
    exit(1);
}

$testPath = $arguments[1];
$expectedChecksum = '0236b75c5b20dfd857b3b5cf75509887959d6ab00d6c7bda9b7dc3df518c1fde';
if (hash_file('sha256', $testPath) !== $expectedChecksum) {
    fwrite(STDERR, "Unexpected checksum for the Unicode 18.0.0 IDNA test data.\n");
    exit(1);
}

$lines = file($testPath, FILE_IGNORE_NEW_LINES);
if ($lines === false) {
    fwrite(STDERR, "Unable to read the IDNA test data.\n");
    exit(1);
}

$codec = new Ucs4Codec();
$converter = new ToIdn(2008, true, true, true);
$decodeField = static function (string $field) use ($codec): string {
    if ($field === '""') {
        return '';
    }

    $decoded = preg_replace_callback(
        '/\\\\u([0-9A-Fa-f]{4})|\\\\x\{([0-9A-Fa-f]+)}/',
        static function (array $matches) use ($codec): string {
            $value = $matches[1] !== '' ? $matches[1] : $matches[2];

            $codePoint = intval($value, 16);
            if (0xD800 <= $codePoint && $codePoint <= 0xDFFF) {
                // PHP can carry these bytes: exercise rejection of an illegal UTF-8 surrogate
                // rather than letting the test fixture decoder reject the case for us.
                return chr(0xE0 | ($codePoint >> 12))
                    . chr(0x80 | (($codePoint >> 6) & 0x3F))
                    . chr(0x80 | ($codePoint & 0x3F));
            }

            return $codec->encode([$codePoint]);
        },
        $field,
    );
    if ($decoded === null) {
        throw new RuntimeException('Unable to decode an IDNA test field');
    }

    return $decoded;
};
// Read the pinned upstream reference, not the implementation's generated tables.
$mappingPath = $arguments[2];
if (hash_file('sha256', $mappingPath) !== 'a03b1eb38032268c696406a83f0972d6a815acd2c8d4151d42ec0fda70ffced1') {
    throw new RuntimeException('Unexpected checksum for the Unicode 18.0.0 IDNA mapping data');
}
$referenceDisallowed = [];
$mappingLines = file($mappingPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
if ($mappingLines === false) {
    throw new RuntimeException('Unable to read the IDNA mapping data');
}
foreach ($mappingLines as $mappingLine) {
    $record = trim(explode('#', $mappingLine, 2)[0]);
    if ($record === '') {
        continue;
    }
    $columns = array_map('trim', explode(';', $record));
    if ($columns[1] !== 'disallowed' && !in_array($columns[3] ?? '', ['NV8', 'XV8'], true)) {
        continue;
    }
    $range = explode('..', $columns[0]);
    $referenceDisallowed[] = [hexdec($range[0]), hexdec($range[1] ?? $range[0])];
}
$isStrictlyDisallowed = static function (string $value) use ($codec, $referenceDisallowed): bool {
    foreach ($codec->decode($value) as $codePoint) {
        foreach ($referenceDisallowed as [$start, $end]) {
            if ($start <= $codePoint && $codePoint <= $end) {
                return true;
            }
        }
    }

    return false;
};

$counts = ['exact' => 0, 'uts46-rejected' => 0, 'strict-idna2008-rejected' => 0, 'malformed-utf8-rejected' => 0];
foreach ($lines as $lineNumber => $line) {
    $data = trim(explode('#', $line, 2)[0]);
    if ($data === '') {
        continue;
    }
    $fields = array_pad(array_map('trim', explode(';', $data)), 7, '');

    $source = $decodeField($fields[0]);
    $toUnicode = $fields[1] === '' ? $source : $decodeField($fields[1]);
    $toAscii = $fields[3] === '' ? $toUnicode : $decodeField($fields[3]);
    $unicodeStatus = $fields[2];
    $asciiStatus = $fields[4] === '' ? $unicodeStatus : $fields[4];
    $hasCorpusError = $asciiStatus !== '' && $asciiStatus !== '[]';
    if (preg_match('//u', $source) !== 1) {
        $category = 'malformed-utf8-rejected';
    } elseif ($hasCorpusError) {
        $category = 'uts46-rejected';
    } elseif ($isStrictlyDisallowed($toUnicode)) {
        $category = 'strict-idna2008-rejected';
    } else {
        $category = 'exact';
    }
    $expectsFailure = $category !== 'exact';

    $actual = null;
    $conversionFailure = null;
    try {
        $actual = $converter->convert($source);
    } catch (InvalidCharacterException | Std3AsciiRulesViolationException | AlreadyPunycodeException $exception) {
        // A decoded label with an ACE prefix can trigger AlreadyPunycodeException.
        // Only these explicit input-validation failures count as expected rejection.
        $conversionFailure = $exception;
    } catch (Throwable $exception) {
        throw new RuntimeException(
            sprintf('Line %d raised an unexpected %s', $lineNumber + 1, $exception::class),
            0,
            $exception,
        );
    }
    if ($expectsFailure && $conversionFailure === null) {
        throw new RuntimeException(
            sprintf('Line %d (%s) should fail but returned %s', $lineNumber + 1, $category, json_encode($actual)),
        );
    }
    if (!$expectsFailure && $conversionFailure !== null) {
        throw new RuntimeException(
            sprintf('Line %d unexpectedly failed: %s', $lineNumber + 1, $conversionFailure->getMessage()),
            0,
            $conversionFailure,
        );
    }
    if (!$expectsFailure && $actual !== $toAscii) {
        throw new RuntimeException(
            sprintf(
                'Line %d returned %s, expected %s',
                $lineNumber + 1,
                json_encode($actual),
                json_encode($toAscii),
            ),
        );
    }
    ++$counts[$category];
}

printf("Verified %d ToASCII cases; skipped 0 cases.\n", array_sum($counts));
foreach ($counts as $category => $count) {
    printf("  %s: %d\n", $category, $count);
}
