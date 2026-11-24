<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert\TranscodeUnicode;

interface TranscodeUnicodeInterface
{
    /**
     * @param string|list<int> $data
     *
     * @return string|list<int>
     */
    public function convert(
        string|array $data,
        string $fromEncoding,
        string $toEncoding,
        bool $safeMode = false,
        int $safeCodepoint = 0xFFFC
    ): array|string;
}
