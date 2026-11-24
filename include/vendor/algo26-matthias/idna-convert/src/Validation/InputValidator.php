<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Validation;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;

final class InputValidator implements InputValidatorInterface
{
    public function __construct(
        private readonly Ucs4Validator $ucs4Validator = new Ucs4Validator(),
        private readonly PunycodeValidator $punycodeValidator = new PunycodeValidator(),
        private readonly TranscodeUnicode $transcoder = new TranscodeUnicode(),
    ) {
    }

    public function validateUtf8(string $input): void
    {
        $this->transcoder->convert(
            $input,
            TranscodeUnicode::FORMAT_UTF8,
            TranscodeUnicode::FORMAT_UCS4_ARRAY,
        );
    }

    public function validateUcs4(array $input): void
    {
        $this->ucs4Validator->validate($input);
    }

    public function validateUcs4String(string $input): void
    {
        $this->transcoder->convert(
            $input,
            TranscodeUnicode::FORMAT_UCS4,
            TranscodeUnicode::FORMAT_UCS4_ARRAY,
        );
    }

    public function validatePunycode(string $input): void
    {
        if (!$this->punycodeValidator->isValid($input)) {
            throw new InvalidCharacterException('The given string is not valid Punycode', 307);
        }
    }
}
