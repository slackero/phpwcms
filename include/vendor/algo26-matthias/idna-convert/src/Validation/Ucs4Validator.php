<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Validation;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use InvalidArgumentException;

/** @internal */
final class Ucs4Validator
{
    /**
     * @param array<int|string, mixed> $input
     *
     * @throws InvalidCharacterException
     */
    public function validate(array $input): void
    {
        if (!array_is_list($input)) {
            throw new InvalidArgumentException('UCS-4 input must be a list of code points', 308);
        }

        foreach ($input as $offset => $codePoint) {
            if (!is_int($codePoint)) {
                throw new InvalidArgumentException(
                    sprintf('UCS-4 code point at offset %d must be an integer', $offset),
                    308,
                );
            }
            if (!$this->isUnicodeScalarValue($codePoint)) {
                throw new InvalidCharacterException(
                    sprintf('Conversion from UCS-4 failed: malformed input at offset %d', $offset),
                    305,
                );
            }
        }
    }

    private function isUnicodeScalarValue(int $codePoint): bool
    {
        return 0 <= $codePoint
            && $codePoint <= 0x10FFFF
            && !(0xD800 <= $codePoint && $codePoint <= 0xDFFF);
    }
}
