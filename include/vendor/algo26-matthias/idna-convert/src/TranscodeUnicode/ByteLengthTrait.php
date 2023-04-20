<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\TranscodeUnicode;

trait ByteLengthTrait
{
    protected function getByteLength(string $string): int
    {
        if ((extension_loaded('mbstring')
             && (ini_get('mbstring.func_overload') & 0x02) === 0x02)
        ) {
            return mb_strlen($string, '8bit');
        }

        return strlen((binary) $string);
    }
}
