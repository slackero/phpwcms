<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\TranscodeUnicode;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Validation\Ucs4Validator;
use InvalidArgumentException;

class TranscodeUnicode implements TranscodeUnicodeInterface
{
    use ByteLengthTrait;

    public function __construct(private readonly Ucs4Validator $ucs4Validator = new Ucs4Validator())
    {
    }

    public const FORMAT_UCS4 = 'ucs4';
    public const FORMAT_UCS4_ARRAY = 'ucs4array';
    public const FORMAT_UTF8 = 'utf8';
    public const FORMAT_UTF7 = 'utf7';
    public const FORMAT_UTF7_IMAP  = 'utf7imap';

    private const VALID_ENCODINGS = [
        self::FORMAT_UCS4,
        self::FORMAT_UCS4_ARRAY,
        self::FORMAT_UTF8,
        self::FORMAT_UTF7,
        self::FORMAT_UTF7_IMAP
    ];

    private bool $safeMode;
    private int $safeCodepoint = 0xFFFC;

    /**
     * @param string|list<int> $data
     *
     * @return string|list<int>
     * @throws InvalidCharacterException
     */
    public function convert(
        $data,
        string $fromEncoding,
        string $toEncoding,
        bool $safeMode = false,
        int $safeCodepoint = 0xFFFC
    ): array|string {
        $this->safeMode = $safeMode;
        $this->safeCodepoint = $safeCodepoint;

        if ($safeMode && !$this->isUnicodeScalarValue($safeCodepoint)) {
            throw new InvalidArgumentException('Safe replacement must be a valid Unicode scalar value');
        }

        $fromEncoding = strtolower($fromEncoding);
        $toEncoding   = strtolower($toEncoding);

        if (!in_array($fromEncoding, self::VALID_ENCODINGS, true)) {
            throw new InvalidArgumentException(sprintf('Invalid input format %s', $fromEncoding), 300);
        }
        if (!in_array($toEncoding, self::VALID_ENCODINGS, true)) {
            throw new InvalidArgumentException(sprintf('Invalid output format %s', $toEncoding), 301);
        }

        if ($fromEncoding === self::FORMAT_UCS4_ARRAY && !is_array($data)) {
            throw new InvalidArgumentException('UCS-4 array input must be an array');
        }
        if ($fromEncoding !== self::FORMAT_UCS4_ARRAY && !is_string($data)) {
            throw new InvalidArgumentException('Encoded input must be a string');
        }

        if ($fromEncoding === $toEncoding) {
            return $data;
        }

        if (is_array($data)) {
            $codePoints = $data;
        } else {
            $codePoints = match ($fromEncoding) {
                self::FORMAT_UCS4 => $this->ucs4ToUcs4Array($data),
                self::FORMAT_UTF8 => $this->utf8ToUcs4Array($data),
                self::FORMAT_UTF7 => $this->utf7ToUcs4Array($data),
                self::FORMAT_UTF7_IMAP => $this->utf7ImapToUcs4Array($data),
            };
        }

        if (!$this->safeMode) {
            $this->ucs4Validator->validate($codePoints);
        }

        if ($toEncoding === self::FORMAT_UCS4_ARRAY) {
            return $codePoints;
        }

        return match ($toEncoding) {
            self::FORMAT_UCS4 => $this->ucs4ArrayToUcs4($codePoints),
            self::FORMAT_UTF8 => $this->ucs4ArrayToUtf8($codePoints),
            self::FORMAT_UTF7 => $this->ucs4ArrayToUtf7($codePoints),
            self::FORMAT_UTF7_IMAP => $this->ucs4ArrayToUtf7Imap($codePoints),
        };
    }

    /**
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    private function utf8ToUcs4Array(string $input): array
    {
        $startByte = 0;
        $nextByte = 0;

        $output = [];
        $outputLength = 0;
        $inputLength = $this->getByteLength($input);
        $mode = 'next';
        $test = 'none';
        for ($k = 0; $k < $inputLength; ++$k) {
            $v = ord($input[$k]); // Extract byte from input string

            if ($v < 128) { // We found an ASCII char - copy into string as is
                $output[$outputLength] = $v;
                ++$outputLength;
                if ('add' === $mode) {
                    if ($this->safeMode) {
                        $output[$outputLength - 2] = $this->safeCodepoint;
                        $mode = 'next';
                    } else {
                        throw new InvalidCharacterException(
                            sprintf(
                                'Conversion from UTF-8 to UCS-4 failed: malformed input at byte %d',
                                $k,
                            ),
                            302,
                        );
                    }
                }

                continue;
            }

            if ('next' === $mode) { // Try to find the next start byte; determine the width of the Unicode char
                $startByte = $v;
                $mode = 'add';
                $test = 'range';
                if (0xC2 <= $v && $v <= 0xDF) { // &110xxxxx 10xxxxx
                    $nextByte = 0; // How many times subsequent bit masks must rotate 6bits to the left
                    $v = ($v - 192) << 6;
                } elseif (0xE0 <= $v && $v <= 0xEF) { // &1110xxxx 10xxxxxx 10xxxxxx
                    $nextByte = 1;
                    $v = ($v - 224) << 12;
                } elseif (0xF0 <= $v && $v <= 0xF4) { // &11110xxx 10xxxxxx 10xxxxxx 10xxxxxx
                    $nextByte = 2;
                    $v = ($v - 240) << 18;
                } elseif ($this->safeMode) {
                    $mode = 'next';
                    $output[$outputLength] = $this->safeCodepoint;
                    ++$outputLength;

                    continue;
                } else {
                    throw new InvalidCharacterException(
                        sprintf('This might be UTF-8, but I don\'t understand it at byte %d', $k),
                        303,
                    );
                }
                if (($inputLength - $k - 1) < ($nextByte + 1)) {
                    if (!$this->safeMode) {
                        throw new InvalidCharacterException(
                            sprintf('Conversion from UTF-8 to UCS-4 failed: malformed input at byte %d', $k),
                            302,
                        );
                    }

                    $output[$outputLength] = $this->safeCodepoint;
                    ++$outputLength;
                    $mode = 'next';

                    continue;
                }

                $output[$outputLength] = $v;
                ++$outputLength;

                continue;
            }
            if (!$this->safeMode && $test === 'range') {
                $test = 'none';
                if (
                    ($v < 0xA0 && $startByte === 0xE0)
                    || ($v > 0x9F && $startByte === 0xED)
                    || ($v < 0x90 && $startByte === 0xF0)
                    || ($v > 0x8F && $startByte === 0xF4)
                ) {
                    throw new InvalidCharacterException(
                        sprintf('Bogus UTF-8 character (out of legal range) at byte %d', $k),
                        304,
                    );
                }
            }
            if ($v >> 6 === 2) { // Bit mask must be 10xxxxxx
                $v = ($v - 128) << ($nextByte * 6);
                $output[($outputLength - 1)] += $v;
                --$nextByte;
            } elseif ($this->safeMode) {
                $output[$outputLength - 1] = $this->safeCodepoint;
                $k--;
                $mode = 'next';

                continue;
            } else {
                throw new InvalidCharacterException(
                    sprintf('Conversion from UTF-8 to UCS-4 failed: malformed input at byte %d', $k),
                    302,
                );
            }
            if ($nextByte < 0) {
                $mode = 'next';
            }
        }

        return array_values($output);
    }

    /**
     * @param list<int> $input
     *
     * @throws InvalidCharacterException
     */
    private function ucs4ArrayToUtf8(array $input): string
    {
        $output = '';
        foreach ($input as $k => $v) {
            if (!$this->isUnicodeScalarValue($v)) {
                if ($this->safeMode) {
                    $output .= $this->ucs4ArrayToUtf8([$this->safeCodepoint]);

                    continue;
                }

                throw new InvalidCharacterException(
                    sprintf('Conversion from UCS-4 to UTF-8 failed: malformed input at byte %d', $k),
                    305,
                );
            }

            if ($v < 128) { // 7bit are transferred literally
                $output .= chr($v & 0xFF);
            } elseif ($v < (1 << 11)) { // 2 bytes
                $output .= sprintf(
                    '%s%s',
                    chr(192 + ($v >> 6)),
                    chr(128 + ($v & 63))
                );
            } elseif ($v < (1 << 16)) { // 3 bytes
                $output .= sprintf(
                    '%s%s%s',
                    chr(224 + ($v >> 12)),
                    chr(128 + (($v >> 6) & 63)),
                    chr(128 + ($v & 63))
                );
            } else { // 4 bytes
                $output .= sprintf(
                    '%s%s%s%s',
                    chr((240 + ($v >> 18)) & 0xFF),
                    chr(128 + (($v >> 12) & 63)),
                    chr(128 + (($v >> 6) & 63)),
                    chr(128 + ($v & 63))
                );
            }
        }

        return $output;
    }

    /** @return list<int> */
    private function utf7ImapToUcs4Array(string $input): array
    {
        return $this->utf7ToUcs4Array(str_replace(',', '/', $input), '&');
    }

    /**
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    private function utf7ToUcs4Array(string $input, string $sc = '+'): array
    {
        $output = [];
        $inputLength = $this->getByteLength($input);

        for ($position = 0; $position < $inputLength; ++$position) {
            $character = $input[$position];
            if ($character !== $sc) {
                $output[] = ord($character);

                continue;
            }

            if ($position + 1 < $inputLength && $input[$position + 1] === '-') {
                $output[] = ord($sc);
                ++$position;

                continue;
            }

            $start = ++$position;
            while ($position < $inputLength && preg_match('/[A-Za-z0-9+\/]/', $input[$position]) === 1) {
                ++$position;
            }
            $encoded = substr($input, $start, $position - $start);
            if ($encoded === '') {
                throw new InvalidCharacterException('Conversion from UTF-7 failed: empty shift sequence', 307);
            }

            foreach ($this->decodeUtf7Sequence($encoded) as $codePoint) {
                $output[] = $codePoint;
            }

            if ($position < $inputLength && $input[$position] !== '-') {
                --$position;
            }
        }

        return $output;
    }

    /**
     * @param list<int> $input
     */
    private function ucs4ArrayToUtf7Imap(array $input): string
    {
        return str_replace(
            '/',
            ',',
            $this->ucs4ArrayToUtf7($input, '&')
        );
    }

    /**
     * @param list<int> $input
     */
    private function ucs4ArrayToUtf7(array $input, string $sc = '+'): string
    {
        $output = '';
        $b64 = '';

        foreach ($input as $position => $codePoint) {
            if (!$this->isUnicodeScalarValue($codePoint)) {
                throw new InvalidCharacterException(
                    sprintf('Conversion from UCS-4 to UTF-7 failed: malformed input at byte %d', $position),
                    305,
                );
            }

            $isDirect = 0x20 <= $codePoint && $codePoint <= 0x7E && $codePoint !== ord($sc);
            if ($isDirect) {
                $output .= $this->flushUtf7Sequence($b64, $sc);
                $b64 = '';
                $output .= chr($codePoint);

                continue;
            }

            if ($codePoint === ord($sc)) {
                $output .= $this->flushUtf7Sequence($b64, $sc);
                $b64 = '';
                $output .= $sc . '-';

                continue;
            }

            if ($codePoint <= 0xFFFF) {
                $b64 .= chr(($codePoint >> 8) & 0xFF) . chr($codePoint & 0xFF);

                continue;
            }

            $supplementary = $codePoint - 0x10000;
            $highSurrogate = 0xD800 + ($supplementary >> 10);
            $lowSurrogate = 0xDC00 + ($supplementary & 0x3FF);
            $b64 .= chr(($highSurrogate >> 8) & 0xFF) . chr($highSurrogate & 0xFF);
            $b64 .= chr(($lowSurrogate >> 8) & 0xFF) . chr($lowSurrogate & 0xFF);
        }

        return $output . $this->flushUtf7Sequence($b64, $sc);
    }

    private function flushUtf7Sequence(string $utf16, string $shiftCharacter): string
    {
        if ($utf16 === '') {
            return '';
        }

        return $shiftCharacter . rtrim(base64_encode($utf16), '=') . '-';
    }

    /**
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    private function decodeUtf7Sequence(string $encoded): array
    {
        $padding = (4 - strlen($encoded) % 4) % 4;
        $utf16 = base64_decode($encoded . str_repeat('=', $padding), true);
        if ($utf16 === false || strlen($utf16) % 2 !== 0) {
            throw new InvalidCharacterException('Conversion from UTF-7 failed: malformed base64 sequence', 307);
        }

        $output = [];
        $length = strlen($utf16);
        for ($position = 0; $position < $length; $position += 2) {
            $unit = ord($utf16[$position]) << 8 | ord($utf16[$position + 1]);
            if (0xD800 <= $unit && $unit <= 0xDBFF) {
                if ($position + 3 >= $length) {
                    throw new InvalidCharacterException('Conversion from UTF-7 failed: unpaired high surrogate', 307);
                }
                $lowSurrogate = ord($utf16[$position + 2]) << 8 | ord($utf16[$position + 3]);
                if ($lowSurrogate < 0xDC00 || $lowSurrogate > 0xDFFF) {
                    throw new InvalidCharacterException('Conversion from UTF-7 failed: unpaired high surrogate', 307);
                }
                $output[] = 0x10000 + (($unit - 0xD800) << 10) + ($lowSurrogate - 0xDC00);
                $position += 2;

                continue;
            }
            if (0xDC00 <= $unit && $unit <= 0xDFFF) {
                throw new InvalidCharacterException('Conversion from UTF-7 failed: unpaired low surrogate', 307);
            }
            $output[] = $unit;
        }

        return $output;
    }

    /**
     * Convert UCS-4 array into a big-endian UCS-4 string.
     *
     * @param list<int> $input
     */
    private function ucs4ArrayToUcs4(array $input): string
    {
        $output = '';
        foreach ($input as $v) {
            $output .= sprintf(
                '%s%s%s%s',
                chr(($v >> 24) & 255),
                chr(($v >> 16) & 255),
                chr(($v >> 8) & 255),
                chr($v & 255),
            );
        }

        return $output;
    }

    /**
     * Convert a big-endian UCS-4 string to a UCS-4 array.
     *
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    private function ucs4ToUcs4Array(string $input): array
    {
        $output = [];

        $inputLength = $this->getByteLength($input);

        if ($inputLength % 4) {
            throw new InvalidCharacterException('Input UCS4 string is broken', 306);
        }

        if (!$inputLength) {
            return $output;
        }

        for ($i = 0, $outputLength = -1; $i < $inputLength; ++$i) {
            if (!($i % 4)) { // Increment output position every 4 input bytes
                $outputLength++;
                $output[$outputLength] = 0;
            }
            $output[$outputLength] += ord($input[$i]) << (8 * (3 - ($i % 4)));
        }

        return array_values($output);
    }

    private function isUnicodeScalarValue(int $codePoint): bool
    {
        return 0 <= $codePoint
            && $codePoint <= 0x10FFFF
            && !(0xD800 <= $codePoint && $codePoint <= 0xDFFF);
    }
}
