<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\TranscodeUnicode;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use LogicException;

/** @internal */
final class Ucs4Codec
{
    public function __construct(private readonly TranscodeUnicode $transcoder = new TranscodeUnicode())
    {
    }

    /**
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    public function decode(string $value, string $encoding = TranscodeUnicode::FORMAT_UTF8): array
    {
        $converted = $this->transcoder->convert($value, $encoding, TranscodeUnicode::FORMAT_UCS4_ARRAY);
        if (!is_array($converted)) {
            throw new LogicException('Conversion to a UCS-4 array returned an invalid result');
        }

        return $converted;
    }

    /**
     * @param list<int> $codePoints
     *
     * @throws InvalidCharacterException
     */
    public function encode(array $codePoints, string $encoding = TranscodeUnicode::FORMAT_UTF8): string
    {
        $converted = $this->transcoder->convert($codePoints, TranscodeUnicode::FORMAT_UCS4_ARRAY, $encoding);
        if (!is_string($converted)) {
            throw new LogicException('Conversion from a UCS-4 array returned an invalid result');
        }

        return $converted;
    }
}
