<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Validation;

use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;
use Algo26\IdnaConvert\ToIdn;
use InvalidArgumentException;

/**
 * Validates Unicode labels and domain names using the same rules as ToIdn.
 */
final class IdnaValidator
{
    private ToIdn $converter;

    /**
     * @throws InvalidIdnVersionException
     */
    public function __construct(
        ?int $idnVersion = null,
        bool $useStd3AsciiRules = false,
        bool $checkHyphens = true,
        bool $verifyDnsLength = true,
    ) {
        $this->converter = new ToIdn($idnVersion, $useStd3AsciiRules, $checkHyphens, $verifyDnsLength);
    }

    public function validateLabel(string $label): void
    {
        if (str_contains(str_replace(['。', '．', '｡'], '.', $label), '.')) {
            throw new InvalidArgumentException('An IDNA label must not contain a dot', 105);
        }

        $this->converter->convert($label);
    }

    public function validateDomain(string $domain): void
    {
        $this->converter->convert($domain);
    }
}
