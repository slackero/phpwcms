<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert;

use Algo26\IdnaConvert\Punycode\FromPunycode;

class ToUnicode extends AbstractIdnaConvert implements IdnaConvertInterface
{
    private FromPunycode $punycodeEncoder;

    public function __construct()
    {
        $this->punycodeEncoder = new FromPunycode();
    }

    public function convert(string $host): string
    {
        // Drop any whitespace around
        $input = trim($host);

        $hostLabels = explode('.', $input);
        foreach ($hostLabels as $index => $label) {
            $return = $this->punycodeEncoder->convert($label);
            if (!$return) {
                $return = $label;
            }
            $hostLabels[$index] = $return;
        }

        return implode('.', $hostLabels);
    }
}
