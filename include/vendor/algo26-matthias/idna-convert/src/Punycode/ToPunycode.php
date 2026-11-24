<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

use Algo26\IdnaConvert\Exception\AlreadyPunycodeException;
use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;
use Algo26\IdnaConvert\Exception\Std3AsciiRulesViolationException;
use Algo26\IdnaConvert\NamePrep\NamePrep;
use Algo26\IdnaConvert\Validation\Ucs4Validator;
use OutOfBoundsException;

class ToPunycode extends AbstractPunycode implements PunycodeInterface
{
    private NamePrep $namePrep;
    private Ucs4Validator $ucs4Validator;

    /**
     * @throws InvalidIdnVersionException|InvalidCharacterException
     */
    public function __construct(
        ?int $idnVersion = null,
        private readonly ?bool $useStd3AsciiRules = false,
        bool $checkHyphens = true,
        ?bool $checkBidi = null,
    ) {
        $this->namePrep = new NamePrep($idnVersion, $checkHyphens, $checkBidi);
        $this->ucs4Validator = new Ucs4Validator();
        parent::__construct();
    }

    /**
     * @param list<int> $decoded
     *
     * @throws AlreadyPunycodeException
     * @throws InvalidCharacterException
     * @throws Std3AsciiRulesViolationException
     */
    public function convert(array $decoded): ?string
    {
        $this->ucs4Validator->validate($decoded);
        $this->checkForPunycodePrefix($decoded);
        if (
            $this->useStd3AsciiRules
            && $decoded !== []
            && ($decoded[0] === 0x2D || $decoded[array_key_last($decoded)] === 0x2D)
        ) {
            throw new Std3AsciiRulesViolationException('No trailing / leading hyphens allowed', 103);
        }

        $decoded = $this->namePrep->do($decoded);

        $decodedLength = count($decoded);
        if (!$decodedLength) {
            return null; // Empty array
        }

        $this->checkConvertPreconditions($decoded);

        $codeCount = 0; // How many chars have been consumed
        $encoded = '';
        // Copy all basic code points to output
        for ($i = 0; $i < $decodedLength; ++$i) {
            $test = $decoded[$i];
            if (0x01 <= $test && $test <= 0x7f) {
                $encoded .= chr($test);
                $codeCount++;
            }
        }

        if ($codeCount === $decodedLength) {
            return $encoded; // All codepoints were basic ones
        }

        // Start with the prefix
        $encoded = self::PUNYCODE_PREFIX . $encoded;
        // If we have basic code points in output, add a hyphen to the end
        if ($codeCount > 0) {
            $encoded .= '-';
        }

        // Now find and encode all non-basic code points
        $isFirst = true;
        $currentCode = self::INITIAL_N;
        $bias = self::INITIAL_BIAS;
        $delta = 0;

        while ($codeCount < $decodedLength) {
            $nextCode = self::MAX_UCS;
            // Find the next largest code point to $currentCode
            foreach ($decoded as $nextLargestCandidate) {
                if ($nextLargestCandidate >= $currentCode && $nextLargestCandidate <= $nextCode) {
                    $nextCode = $nextLargestCandidate;
                }
            }

            $codeCountPlusOne = $codeCount + 1;

            $delta += ($nextCode - $currentCode) * $codeCountPlusOne;
            $currentCode = $nextCode;

            // Scan input again and encode all characters whose code point is $currentCode
            for ($i = 0; $i < $decodedLength; $i++) {
                if ($decoded[$i] < $currentCode) {
                    $delta++;
                }

                if ($decoded[$i] === $currentCode) {
                    for ($q = $delta, $k = self::BASE; 1; $k += self::BASE) {
                        $t = ($k <= $bias)
                            ? self::T_MIN
                            : (($k >= $bias + self::T_MAX)
                                ? self::T_MAX
                                : $k - $bias
                            );
                        if ($q < $t) {
                            break;
                        }

                        $encoded .= $this->encodeDigit(intval($t + (($q - $t) % (self::BASE - $t))));
                        $q = (int) (($q - $t) / (self::BASE - $t));
                    }
                    $encoded .= $this->encodeDigit($q);
                    $bias = $this->adapt($delta, $codeCountPlusOne, $isFirst);
                    $codeCount++;
                    $delta = 0;
                    $isFirst = false;
                }
            }

            $delta++;
            $currentCode++;
        }

        return $encoded;
    }

    private function encodeDigit(int $digit): string
    {
        if ($digit < 0 || $digit >= self::BASE) {
            throw new OutOfBoundsException(sprintf('Invalid Punycode digit %d', $digit));
        }

        return chr($digit + 22 + 75 * ($digit < 26));
    }

    /**
     * @param non-empty-list<int> $decoded
     *
     * @throws AlreadyPunycodeException
     * @throws Std3AsciiRulesViolationException
     */
    private function checkConvertPreconditions(array $decoded): void
    {
        // We cannot encode a domain name containing the Punycode prefix
        $this->checkForPunycodePrefix($decoded);

        if (!$this->useStd3AsciiRules) {
            return;
        }

        if (
            $decoded[0] === 0x2D
            || $decoded[array_key_last($decoded)] === 0x2D
        ) {
            throw new Std3AsciiRulesViolationException('No trailing / leading hyphens allowed', 103);
        }

        foreach ($decoded as $index => $codePoint) {
            if ($codePoint > 0x7F) {
                continue;
            }
            $isDigit = 0x30 <= $codePoint && $codePoint <= 0x39;
            $isLowercaseAscii = 0x61 <= $codePoint && $codePoint <= 0x7A;
            if (!$isDigit && !$isLowercaseAscii && $codePoint !== 0x2D) {
                throw new Std3AsciiRulesViolationException(
                    sprintf('Character at offset %d is outside the legal range', $index),
                    104,
                );
            }
        }
    }

    /**
     * @param list<int> $decoded
     *
     * @throws AlreadyPunycodeException
     */
    private function checkForPunycodePrefix(array $decoded): void
    {
        if (self::$prefixAsArray === array_slice($decoded, 0, self::$prefixLength)) {
            throw new AlreadyPunycodeException('This is already a Punycode string', 100);
        }
    }
}
