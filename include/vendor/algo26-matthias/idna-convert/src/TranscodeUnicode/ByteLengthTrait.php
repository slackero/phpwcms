<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\TranscodeUnicode;

trait ByteLengthTrait
{
    protected function getByteLength(string $string): int
    {
        return strlen($string);
    }
}
