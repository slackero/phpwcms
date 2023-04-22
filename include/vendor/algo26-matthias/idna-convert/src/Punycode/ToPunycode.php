<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

use Algo26\IdnaConvert\Exception\AlreadyPunycodeException;
use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;
use Algo26\IdnaConvert\Exception\Std3AsciiRulesViolationException;
use Algo26\IdnaConvert\NamePrep\NamePrep;

class ToPunycode extends AbstractPunycode implements PunycodeInterface
{
    private NamePrep $namePrep;

    /**
     * @throws InvalidIdnVersionException
     */
    public function __construct(
        ?int $idnVersion = null,
        private readonly ?bool $useStd3AsciiRules = false
    ) {
        $this->namePrep = new NamePrep($idnVersion);
        parent::__construct();
    }

    /**
     * @throws AlreadyPunycodeException
     * @throws InvalidCharacterException
     * @throws Std3AsciiRulesViolationException
     */
    public function convert(array $decoded): ?string
    {
        $decoded = $this->namePrep->do($decoded);

        $this->checkConvertPreconditions($decoded);
        // We will not try to encode strings consisting of basic code points only
        $canEncode = $this->checkForNonBasicCodepoints($decoded);

        $decodedLength = count($decoded);
        if (!$decodedLength) {
            return null; // Empty array
        }

        $codeCount = 0; // How many chars have been consumed
        $encoded = '';
        // Copy all basic code points to output
        for ($i = 0; $i < $decodedLength; ++$i) {
            $test = $decoded[$i];
            if (0x01 <= $test && $test <= 0x7f) {
                $encoded .= chr($decoded[$i]);
                $codeCount++;
            }
        }

        if (!$canEncode) {
            return $encoded;
        }

        if ($codeCount === $decodedLength) {
            return $encoded; // All codepoints were basic ones
        }

        // Start with the prefix; copy it to output
        $encoded = self::punycodePrefix . $encoded;
        // If we have basic code points in output, add a hyphen to the end
        if ($codeCount > 0) {
            $encoded .= '-';
        }

        // Now find and encode all non-basic code points
        $isFirst = true;
        $currentCode = self::initialN;
        $bias = self::initialBias;
        $delta = 0;

        while ($codeCount < $decodedLength) {
            $nextCode = self::maxUcs;
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
                    for ($q = $delta, $k = self::base; 1; $k += self::base) {
                        $t = ($k <= $bias)
                            ? self::tMin
                            : (($k >= $bias + self::tMax)
                                ? self::tMax
                                : $k - $bias
                            );
                        if ($q < $t) {
                            break;
                        }

                        $encoded .= $this->encodeDigit(intval($t + (($q - $t) % (self::base - $t))));
                        $q = (int) (($q - $t) / (self::base - $t));
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

    private function encodeDigit(int $d): string
    {
        return chr($d + 22 + 75 * ($d < 26));
    }

    /**
     * @throws AlreadyPunycodeException
     * @throws Std3AsciiRulesViolationException
     */
    private function checkConvertPreconditions(array $decoded): void
    {
        // We cannot encode a domain name containing the Punycode prefix
        $checkForPrefix = array_slice($decoded, 0, self::$prefixLength);
        if (self::$prefixAsArray === $checkForPrefix) {
            throw new AlreadyPunycodeException('This is already a Punycode string', 100);
        }

        if (!$this->useStd3AsciiRules) {
            return;
        }

        if ($decoded[0] === '-'
            || $decoded[array_key_last($decoded)] === '-'
        ) {
            throw new Std3AsciiRulesViolationException('No trailing / leading hyphens allowed', 103);
        }

        foreach ($decoded as $index => $codePoint) {
            if (!preg_match('[-a-zA-Z0-9]u', chr($codePoint))) {
                throw new Std3AsciiRulesViolationException(sprintf('Character at offset %d is outside the legal range', $index), 104);
            }
        }
    }

    private function checkForNonBasicCodepoints(array $decoded): bool
    {
        foreach ($decoded as $codePoint) {
            if ($codePoint > 0x7a) {
                return true;
            }
        }

        return false;
    }
}
