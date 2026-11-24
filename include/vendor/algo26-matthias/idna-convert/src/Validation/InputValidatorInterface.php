<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Validation;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;

interface InputValidatorInterface
{
    /** @throws InvalidCharacterException */
    public function validateUtf8(string $input): void;

    /**
     * @param list<int> $input
     *
     * @throws InvalidCharacterException
     */
    public function validateUcs4(array $input): void;

    /** @throws InvalidCharacterException */
    public function validateUcs4String(string $input): void;

    /** @throws InvalidCharacterException */
    public function validatePunycode(string $input): void;
}
