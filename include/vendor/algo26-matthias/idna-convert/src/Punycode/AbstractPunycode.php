<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\TranscodeUnicode\ByteLengthTrait;
use Algo26\IdnaConvert\TranscodeUnicode\Ucs4Codec;

abstract class AbstractPunycode
{
    use ByteLengthTrait;

    public const PUNYCODE_PREFIX = 'xn--';
    public const MAX_UCS = 0x10FFFF;
    public const BASE = 36;
    public const T_MIN = 1;
    public const T_MAX = 26;
    public const SKEW = 38;
    public const DAMP = 700;
    public const INITIAL_BIAS = 72;
    public const INITIAL_N = 0x80;

    /** @var list<int>|null */
    protected static ?array $prefixAsArray = null;
    protected static int $prefixLength;

    protected Ucs4Codec $ucs4Codec;

    /**
     * @throws InvalidCharacterException
     */
    public function __construct()
    {
        $this->ucs4Codec = new Ucs4Codec();

        if (self::$prefixAsArray === null) {
            self::$prefixAsArray = $this->ucs4Codec->decode(self::PUNYCODE_PREFIX);
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
