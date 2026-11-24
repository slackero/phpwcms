<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

/** @internal */
final class UnicodeRange
{
    /** @param list<array{int, int}> $ranges */
    public static function contains(int $codePoint, array $ranges): bool
    {
        $low = 0;
        $high = count($ranges) - 1;
        while ($low <= $high) {
            $middle = intdiv($low + $high, 2);
            [$start, $end] = $ranges[$middle];
            if ($codePoint < $start) {
                $high = $middle - 1;
            } elseif ($codePoint > $end) {
                $low = $middle + 1;
            } else {
                return true;
            }
        }

        return false;
    }
}
