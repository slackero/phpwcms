<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

use InvalidArgumentException;

/** @internal */
final class UnicodeNormalizer
{
    private const S_BASE = 0xAC00;
    private const L_BASE = 0x1100;
    private const V_BASE = 0x1161;
    private const T_BASE = 0x11A7;
    private const L_COUNT = 19;
    private const V_COUNT = 21;
    private const T_COUNT = 28;
    private const S_COUNT = self::L_COUNT * self::V_COUNT * self::T_COUNT;

    /** @var array<int, int> */
    private array $combiningClasses;

    /** @var array<int, list<int>> */
    private array $canonicalDecompositions;

    /** @var array<int, list<int>> */
    private array $compatibilityDecompositions;

    /** @var array<int, array<int, int>> */
    private array $compositions;

    public function __construct(int $idnVersion = 2008)
    {
        if ($idnVersion === 2003) {
            $this->combiningClasses = NormalizationData2003::COMBINING_CLASSES;
            $this->canonicalDecompositions = NormalizationData2003::CANONICAL_DECOMPOSITIONS;
            $this->compatibilityDecompositions = NormalizationData2003::COMPATIBILITY_DECOMPOSITIONS;
            $this->compositions = NormalizationData2003::COMPOSITIONS;

            return;
        }

        if ($idnVersion !== 2008) {
            throw new InvalidArgumentException('IDN version must be either 2003 or 2008');
        }

        $this->combiningClasses = NormalizationData2008::COMBINING_CLASSES;
        $this->canonicalDecompositions = NormalizationData2008::CANONICAL_DECOMPOSITIONS;
        $this->compatibilityDecompositions = [];
        $this->compositions = NormalizationData2008::COMPOSITIONS;
    }

    /**
     * @param list<int> $input
     *
     * @return list<int>
     */
    public function normalize(array $input): array
    {
        $decomposed = [];
        foreach ($input as $codePoint) {
            $this->decompose($codePoint, $decomposed);
        }

        return $this->compose($this->applyCanonicalOrdering($decomposed));
    }

    /**
     * @param list<int> $output
     */
    private function decompose(int $codePoint, array &$output): void
    {
        $decomposition = $this->compatibilityDecompositions[$codePoint] ?? null;
        $decomposition ??= $this->canonicalDecompositions[$codePoint] ?? null;
        if ($decomposition === null) {
            $output[] = $codePoint;

            return;
        }

        foreach ($decomposition as $decomposedCodePoint) {
            $this->decompose($decomposedCodePoint, $output);
        }
    }

    /**
     * @param list<int> $input
     *
     * @return list<int>
     */
    private function applyCanonicalOrdering(array $input): array
    {
        $output = [];
        /** @var list<array{int, int, int}> $nonStarters */
        $nonStarters = [];
        $sequence = 0;
        foreach ($input as $codePoint) {
            $combiningClass = $this->getCombiningClass($codePoint);
            if ($combiningClass === 0) {
                $this->appendOrderedNonStarters($output, $nonStarters);
                $nonStarters = [];
                $output[] = $codePoint;

                continue;
            }
            $nonStarters[] = [$codePoint, $combiningClass, $sequence++];
        }
        $this->appendOrderedNonStarters($output, $nonStarters);

        /** @var list<int> $output */
        return $output;
    }

    /**
     * @param list<int> $output
     * @param list<array{int, int, int}> $nonStarters
     */
    private function appendOrderedNonStarters(array &$output, array $nonStarters): void
    {
        usort(
            $nonStarters,
            static fn (array $left, array $right): int => $left[1] <=> $right[1] ?: $left[2] <=> $right[2],
        );
        foreach ($nonStarters as [$codePoint]) {
            $output[] = $codePoint;
        }
    }

    /**
     * @param list<int> $input
     *
     * @return list<int>
     */
    private function compose(array $input): array
    {
        if ($input === []) {
            return [];
        }

        $output = [$input[0]];
        $starter = $input[0];
        $starterPosition = 0;
        $previousCombiningClass = $this->getCombiningClass($starter);

        $inputLength = count($input);
        for ($position = 1; $position < $inputLength; ++$position) {
            $codePoint = $input[$position];
            $combiningClass = $this->getCombiningClass($codePoint);
            $composite = $this->composePair($starter, $codePoint);

            if (
                $composite !== null
                && ($previousCombiningClass === 0 || $previousCombiningClass < $combiningClass)
            ) {
                $output[$starterPosition] = $composite;
                $starter = $composite;

                continue;
            }

            if ($combiningClass === 0) {
                $starter = $codePoint;
                $starterPosition = count($output);
            }
            $output[] = $codePoint;
            $previousCombiningClass = $combiningClass;
        }

        /** @var list<int> $output */
        return $output;
    }

    private function composePair(int $starter, int $codePoint): ?int
    {
        $lIndex = $starter - self::L_BASE;
        $vIndex = $codePoint - self::V_BASE;
        if (0 <= $lIndex && $lIndex < self::L_COUNT && 0 <= $vIndex && $vIndex < self::V_COUNT) {
            return self::S_BASE + ($lIndex * self::V_COUNT + $vIndex) * self::T_COUNT;
        }

        $sIndex = $starter - self::S_BASE;
        $tIndex = $codePoint - self::T_BASE;
        if (
            0 <= $sIndex
            && $sIndex < self::S_COUNT
            && $sIndex % self::T_COUNT === 0
            && 0 < $tIndex
            && $tIndex < self::T_COUNT
        ) {
            return $starter + $tIndex;
        }

        return $this->compositions[$starter][$codePoint] ?? null;
    }

    private function getCombiningClass(int $codePoint): int
    {
        return $this->combiningClasses[$codePoint] ?? 0;
    }
}
