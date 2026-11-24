<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Validation;

use Algo26\IdnaConvert\Punycode\AbstractPunycode;

/** @internal */
final class PunycodeValidator
{
    public function isValid(string $input): bool
    {
        return $this->hasPayload($input)
            && preg_match('/\\Axn--[A-Za-z0-9-]+\\z/D', $input) === 1;
    }

    /** Preserve the decoder's legacy precondition; invalid digits are reported by the decoder. */
    public function hasPayload(string $input): bool
    {
        return str_starts_with($input, AbstractPunycode::PUNYCODE_PREFIX)
            && strlen(trim($input)) > strlen(AbstractPunycode::PUNYCODE_PREFIX);
    }
}
