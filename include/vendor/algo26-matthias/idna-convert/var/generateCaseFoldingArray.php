<?php

$inputFile = __DIR__ . '/CaseFolding.txt';
$outputFile = __DIR__ . '/../src/NamePrep/CaseFoldingData.php';

$map = [];

// Datei zeilenweise lesen
foreach (file($inputFile) as $line) {
    // Kommentare und leere Zeilen überspringen
    if (empty($line) || $line[0] === '#') {
        continue;
    }

    [$code, $status, $mapping] = array_map('trim', explode(';', $line));

    // ignore mapping irrelevant for IDNA2008
    if (!in_array($status, ['C', 'F'], true)) {
        continue;
    }
    // For IDNA2008, ß must not be case-folded to "ss"
    if (strtoupper($code) === '00DF') {
        continue;
    }

    $from = hexdec($code);
    $to = array_map('hexdec', explode(' ', $mapping));

    $map[$from] = $to;
}

$output = '<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

/**
 * @codeCoverageIgnore character maps
 */
class CaseFoldingData implements CaseFoldingDataInterface
{
    public array $foldingMap = [
';
foreach ($map as $from => $to) {
    $fromHex = sprintf('0x%X', $from);
    $toHex = array_map(fn($v) => sprintf('0x%X', $v), $to);

    $output .= '        ' . $fromHex . ' => [' . join(', ', $toHex) .'],' . PHP_EOL;
}
$output .= '    ];
}
';
file_put_contents($outputFile, $output);
