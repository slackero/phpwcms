<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\NamePrep;

class CaseFolding
{
    private CaseFoldingData $caseFoldingData;

    public function __construct()
    {
        $this->caseFoldingData = new CaseFoldingData();
    }

    public function apply(array $inputArray, int $idnaVersion): array
    {
        if ($idnaVersion === 2003) {
            return $inputArray;
        }

        $outputArray = [];
        foreach ($inputArray as $codePoint) {
            if (isset($this->caseFoldingData->foldingMap[$codePoint])) {
                foreach ($this->caseFoldingData->foldingMap[$codePoint] as $folded) {
                    $outputArray[] = $folded;
                }
            } else {
                $outputArray[] = $codePoint;
            }
        }

        return $outputArray;
    }
}
