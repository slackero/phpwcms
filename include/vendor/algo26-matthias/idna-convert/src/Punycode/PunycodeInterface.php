<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\Punycode;

interface PunycodeInterface
{
    public function __construct(
        ?int $idnVersion = null,
        ?bool $useStd3AsciiRules = false
    );

    public function getPunycodePrefix(): string;
}
