<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

use Algo26\IdnaConvert\TranscodeUnicode\ByteLengthTrait;
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;

abstract class AbstractPunycode
{
    const punycodePrefix = 'xn--';
    const invalidUcs = 0x80000000;
    const maxUcs = 0x10FFFF;
    const base = 36;
    const tMin = 1;
    const tMax = 26;
    const skew = 38;
    const damp = 700;
    const initialBias = 72;
    const initialN = 0x80;

    protected static $prefixAsArray;
    protected static $prefixLength;

    protected TranscodeUnicode $unicodeTransCoder;

    use ByteLengthTrait;

    public function __construct()
    {
        $this->unicodeTransCoder = new TranscodeUnicode();

        if (self::$prefixAsArray === null) {
            self::$prefixAsArray = $this->unicodeTransCoder->convert(
                self::punycodePrefix,
                $this->unicodeTransCoder::FORMAT_UTF8,
                $this->unicodeTransCoder::FORMAT_UCS4_ARRAY
            );
            self::$prefixLength = $this->getByteLength(self::punycodePrefix);
        }
    }

    public function getPunycodePrefix(): string
    {
        return self::punycodePrefix;
    }

    protected function adapt(int $delta, int $nPoints, bool $isFirst): int
    {
        $delta = intval($isFirst ? ($delta / self::damp) : ($delta / 2));
        $delta += intval($delta / $nPoints);
        for ($k = 0; $delta > ((self::base - self::tMin) * self::tMax) / 2; $k += self::base) {
            $delta = intval($delta / (self::base - self::tMin));
        }

        return intval($k + (self::base - self::tMin + 1) * $delta / ($delta + self::skew));
    }
}
