<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

interface NamePrepInterface
{
    /**
     * @param list<int> $inputArray
     *
     * @return list<int>
     */
    public function do(array $inputArray): array;
}
