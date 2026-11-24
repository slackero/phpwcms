<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;

/** @internal */
final class NamePrepProcessor2003
{
    public function __construct(
        private readonly NamePrepData2003 $data = new NamePrepData2003(),
        private readonly UnicodeNormalizer $normalizer = new UnicodeNormalizer(2003),
    ) {
    }

    /**
     * @param list<int> $input
     *
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    public function process(array $input): array
    {
        $mapped = [];
        foreach ($input as $codePoint) {
            if (in_array($codePoint, $this->data->mapToNothing, true)) {
                continue;
            }
            foreach ($this->data->replaceMaps[$codePoint] ?? [$codePoint] as $replacement) {
                $mapped[] = $replacement;
            }
        }

        $normalized = $this->normalizer->normalize($mapped);
        if ($normalized !== [] && UnicodeRange::contains($normalized[0], NormalizationData2003::MARK_RANGES)) {
            throw new InvalidCharacterException('An IDNA label must not start with a combining mark', 101);
        }
        $this->validateProhibitedOutput($normalized);
        $this->validateBidi($normalized);

        return $normalized;
    }

    /** @param list<int> $output */
    private function validateProhibitedOutput(array $output): void
    {
        foreach ($output as $codePoint) {
            if (
                in_array($codePoint, $this->data->prohibit, true)
                || in_array($codePoint, $this->data->generalProhibited, true)
            ) {
                throw new InvalidCharacterException(sprintf('Prohibited output U+%08X', $codePoint), 101);
            }
            foreach ($this->data->prohibitRanges as [$start, $end]) {
                if ($start <= $codePoint && $codePoint <= $end) {
                    throw new InvalidCharacterException(sprintf('Prohibited output U+%08X', $codePoint), 102);
                }
            }
        }
    }

    /** @param list<int> $output */
    private function validateBidi(array $output): void
    {
        if (!$this->containsRightToLeft($output)) {
            return;
        }

        foreach ($output as $codePoint) {
            if (UnicodeRange::contains($codePoint, NormalizationData2003::BIDI_RANGES['L'])) {
                throw new InvalidCharacterException('The label violates the IDNA2003 bidirectional text rule', 101);
            }
        }

        $lastIndex = array_key_last($output);
        if ($lastIndex === null) {
            return;
        }
        $last = $output[$lastIndex];
        if (!$this->isRightToLeft($output[0]) || !$this->isRightToLeft($last)) {
            throw new InvalidCharacterException('The label violates the IDNA2003 bidirectional text rule', 101);
        }
    }

    /** @param list<int> $output */
    private function containsRightToLeft(array $output): bool
    {
        foreach ($output as $codePoint) {
            if (
                UnicodeRange::contains($codePoint, NormalizationData2003::BIDI_RANGES['R'])
                || UnicodeRange::contains($codePoint, NormalizationData2003::BIDI_RANGES['AL'])
            ) {
                return true;
            }
        }

        return false;
    }

    private function isRightToLeft(int $codePoint): bool
    {
        return UnicodeRange::contains($codePoint, NormalizationData2003::BIDI_RANGES['R'])
            || UnicodeRange::contains($codePoint, NormalizationData2003::BIDI_RANGES['AL']);
    }
}
