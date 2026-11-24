<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;

class NamePrep implements NamePrepInterface
{
    private IdnaProcessor2008|NamePrepProcessor2003 $processor;

    /**
     * @throws InvalidIdnVersionException
     */
    public function __construct(
        ?int $idnVersion = null,
        bool $checkHyphens = true,
        ?bool $checkBidi = null,
    ) {
        if ($idnVersion === null || $idnVersion === 2008) {
            $this->processor = new IdnaProcessor2008($checkHyphens, $checkBidi);
        } elseif ($idnVersion === 2003) {
            $this->processor = new NamePrepProcessor2003();
        } else {
            throw new InvalidIdnVersionException('IDN version must be either 2003 or 2008', 400);
        }
    }

    /**
     * @param list<int> $inputArray
     *
     * @return list<int>
     *
     * @throws InvalidCharacterException
     */
    public function do(array $inputArray): array
    {
        return $this->processor->process($inputArray);
    }
}
