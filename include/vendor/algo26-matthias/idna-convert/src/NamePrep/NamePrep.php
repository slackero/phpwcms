<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;

class NamePrep implements NamePrepInterface
{
    private const S_BASE = 0xAC00;
    private const L_BASE = 0x1100;
    private const V_BASE = 0x1161;
    private const T_BASE = 0x11A7;
    private const L_COUNT = 19;
    private const V_COUNT = 21;
    private const T_COUNT = 28;
    private const N_COUNT = 588;   // V_COUNT * T_COUNT
    private const S_COUNT = 11172; // L_COUNT * T_COUNT * V_COUNT

    private NamePrepDataInterface $namePrepData;
    private CaseFolding $caseFolding;

    /**
     * @throws InvalidIdnVersionException
     */
    public function __construct(?int $idnVersion = null)
    {
        $this->caseFolding = new CaseFolding();

        if ($idnVersion === null || $idnVersion === 2008) {
            $this->namePrepData = new NamePrepData2008();

            return;
        }

        if ($idnVersion === 2003) {
            $this->namePrepData = new NamePrepData2003();

            return;
        }

        throw new InvalidIdnVersionException('IDN version must be either 2003 or 2008', 400);
    }

    /**
     * @throws InvalidCharacterException
     */
    public function do(array $inputArray): array
    {
        $outputArray = $this->applyCharacterMaps($inputArray);
        $outputArray = $this->hangulCompose($outputArray);
        $outputArray = $this->combineCodePoints($outputArray);

        return $this->caseFolding->apply(
            $outputArray,
            $this->namePrepData->version,
        );
    }

    /**
     * @throws InvalidCharacterException
     */
    private function applyCharacterMaps(array $inputArray): array
    {
        $outputArray = [];
        foreach ($inputArray as $codePoint) {
            if (in_array($codePoint, $this->namePrepData->mapToNothing)) {
                continue;
            }
            if (in_array($codePoint, $this->namePrepData->prohibit)
                || in_array($codePoint, $this->namePrepData->generalProhibited)
            ) {
                throw new InvalidCharacterException(sprintf('Prohibited input U+%08X', $codePoint), 101);
            }
            foreach ($this->namePrepData->prohibitRanges as $range) {
                if ($range[0] <= $codePoint && $codePoint <= $range[1]) {
                    throw new InvalidCharacterException(sprintf('Prohibited input U+%08X', $codePoint), 102);
                }
            }

            if (0xAC00 <= $codePoint && $codePoint <= 0xD7AF) {
                foreach ($this->hangulDecompose($codePoint) as $decomposed) {
                    $outputArray[] = (int) $decomposed;
                }
            } elseif (isset($this->namePrepData->replaceMaps[$codePoint])) {
                foreach ($this->applyCanonicalOrdering($this->namePrepData->replaceMaps[$codePoint]) as $reordered) {
                    $outputArray[] = (int) $reordered;
                }
            } else {
                $outputArray[] = (int) $codePoint;
            }
        }

        return $outputArray;
    }

    private function combineCodePoints(array $codePoints): array
    {
        $previousClass = 0;
        $previousStarter = 0;
        $outputLength = count($codePoints);
        for ($outerIndex = 0; $outerIndex < $outputLength; ++$outerIndex) {
            $combiningClass = $this->getCombiningClass($codePoints[$outerIndex]);
            if ($combiningClass !== 0
                && ($previousClass === 0 || $previousClass > $combiningClass)
            ) {
                // Try to match
                $sequenceLength = $outerIndex - $previousStarter;
                $combined = $this->combine(array_slice($codePoints, $previousStarter, $sequenceLength));
                // On match: Replace the last starter with the composed character and remove
                // the now redundant non-starter(s)
                if (null !== $combined) {
                    $codePoints[$previousStarter] = $combined;
                    if ($sequenceLength > 1) {
                        for ($innerIndex = $outerIndex + 1; $innerIndex < $outputLength; ++$innerIndex) {
                            $codePoints[$innerIndex - 1] = $codePoints[$innerIndex];
                        }
                        unset($codePoints[$outputLength]);
                    }
                    // Rewind the for loop by one, since there can be more possible compositions
                    $outerIndex--;
                    $outputLength--;
                    $previousClass = 0;
                    if ($outerIndex !== $previousStarter) {
                        $previousClass = $this->getCombiningClass($codePoints[$outerIndex - 1]);
                    }

                    continue;
                }
            }

            if ($combiningClass === 0) {
                $previousStarter = $outerIndex;
            }
            $previousClass = $combiningClass;
        }

        return $codePoints;
    }

    /**
     * Decomposes a Hangul syllable
     * (see http://www.unicode.org/unicode/reports/tr15/#Hangul
     */
    private function hangulDecompose(int $codePoint): array
    {
        $sIndex = $codePoint - self::S_BASE;
        if ($sIndex < 0 || $sIndex >= self::S_COUNT) {
            return [$codePoint];
        }

        $result = [
            (int) self::L_BASE + $sIndex / self::N_COUNT,
            (int) self::V_BASE + ($sIndex % self::N_COUNT) / self::T_COUNT,
        ];
        $T = intval(self::T_BASE + $sIndex % self::T_COUNT);
        if ($T != self::T_BASE) {
            $result[] = $T;
        }

        return $result;
    }

    /**
     * Compose a Hangul syllable
     * (see http://www.unicode.org/unicode/reports/tr15/#Hangul
     */
    private function hangulCompose(array $input): array
    {
        $inputLength = count($input);
        if ($inputLength === 0) {
            return [];
        }

        $previousCharCode = (int) $input[0];

        $result = [$previousCharCode];

        for ($i = 1; $i < $inputLength; ++$i) {
            $charCode = (int) $input[$i];
            $sIndex = $previousCharCode - self::S_BASE;
            $lIndex = $previousCharCode - self::L_BASE;
            $vIndex = $charCode - self::V_BASE;
            $tIndex = $charCode - self::T_BASE;

            // Determine if two current characters are LV and T
            if (0 <= $sIndex
                && $sIndex < self::S_COUNT
                && ($sIndex % self::T_COUNT == 0)
                && 0 <= $tIndex
                && $tIndex <= self::T_COUNT
            ) {
                // Create syllable of form LVT
                $previousCharCode += $tIndex;
                $result[(count($result) - 1)] = $previousCharCode; // reset last

                continue; // discard char
            }

            // Determine if two current characters form L and V
            if (0 <= $lIndex
                && $lIndex < self::L_COUNT
                && 0 <= $vIndex
                && $vIndex < self::V_COUNT
            ) {
                // Create syllable of form LV
                $previousCharCode = (int) self::S_BASE + ($lIndex * self::V_COUNT + $vIndex) * self::T_COUNT;
                $result[(count($result) - 1)] = $previousCharCode; // reset last

                continue; // discard char
            }

            $previousCharCode = $charCode;
            $result[] = $charCode;
        }

        return $result;
    }

    private function getCombiningClass(int $char): int
    {
        return $this->namePrepData->normalizeCombiningClasses[$char] ?? 0;
    }

    private function applyCanonicalOrdering(array $input): array
    {
        $needsSwapping = true;
        $inputLength = count($input);
        while ($needsSwapping) {
            $needsSwapping = false;
            $previousClass = $this->getCombiningClass(intval($input[0]));
            for ($outerIndex = 0; $outerIndex < $inputLength - 1; ++$outerIndex) {
                $nextClass = $this->getCombiningClass(intval($input[$outerIndex + 1]));
                if ($nextClass !== 0 && $previousClass > $nextClass) {
                    // Move item leftward until it fits
                    for ($innerIndex = $outerIndex + 1; $innerIndex > 0; --$innerIndex) {
                        if ($this->getCombiningClass(intval($input[$innerIndex - 1])) <= $nextClass) {
                            break;
                        }
                        $charToMove = intval($input[$innerIndex]);
                        $input[$innerIndex] = intval($input[$innerIndex - 1]);
                        $input[$innerIndex - 1] = $charToMove;
                        $needsSwapping = true;
                    }
                    // Reentering the loop looking at the old character again
                    $nextClass = $previousClass;
                }
                $previousClass = $nextClass;
            }
        }

        return $input;
    }

    private function combine(array $input): ?int
    {
        if ($input === []) {
            return null;
        }

        foreach ($this->namePrepData->replaceMaps as $namePrepSource => $namePrepTarget) {
            if ($namePrepTarget === $input) {
                return $namePrepSource;
            }
        }

        return null;
    }
}
