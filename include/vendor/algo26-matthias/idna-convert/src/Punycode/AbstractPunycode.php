<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

use Algo26\IdnaConvert\TranscodeUnicode\ByteLengthTrait;
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;

abstract class AbstractPunycode
{
    const PUNYCODE_PREFIX = 'xn--';
    const MAX_UCS = 0x10FFFF;
    const BASE = 36;
    const T_MIN = 1;
    const T_MAX = 26;
    const SKEW = 38;
    const DAMP = 700;
    const INITIAL_BIAS = 72;
    const INITIAL_N = 0x80;

    protected static ?array $prefixAsArray = null;
    protected static int $prefixLength;

    protected TranscodeUnicode $unicodeTransCoder;

    use ByteLengthTrait;

    public function __construct()
    {
        $this->unicodeTransCoder = new TranscodeUnicode();

        if (self::$prefixAsArray === null) {
            self::$prefixAsArray = $this->unicodeTransCoder->convert(
                self::PUNYCODE_PREFIX,
                $this->unicodeTransCoder::FORMAT_UTF8,
                $this->unicodeTransCoder::FORMAT_UCS4_ARRAY
            );
            self::$prefixLength = $this->getByteLength(self::PUNYCODE_PREFIX);
        }
    }

    public function getPunycodePrefix(): string
    {
        return self::PUNYCODE_PREFIX;
    }

    protected function adapt(int $delta, int $nPoints, bool $isFirst): int
    {
        $delta = intval($isFirst ? ($delta / self::DAMP) : ($delta / 2));
        $delta += intval($delta / $nPoints);
        for ($k = 0; $delta > ((self::BASE - self::T_MIN) * self::T_MAX) / 2; $k += self::BASE) {
            $delta = intval($delta / (self::BASE - self::T_MIN));
        }

        return intval($k + (self::BASE - self::T_MIN + 1) * $delta / ($delta + self::SKEW));
    }
}
