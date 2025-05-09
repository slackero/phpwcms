<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert;

use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;
use Algo26\IdnaConvert\Punycode\ToPunycode;
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;

class ToIdn extends AbstractIdnaConvert implements IdnaConvertInterface
{
    private TranscodeUnicode $unicodeTransCoder;

    private ToPunycode $punycodeEncoder;

    /**
     * @throws InvalidIdnVersionException
     */
    public function __construct(
        ?int $idnVersion = null,
        bool $useStd3AsciiRules = false
    ) {
        $this->unicodeTransCoder = new TranscodeUnicode();
        $this->punycodeEncoder = new ToPunycode($idnVersion, $useStd3AsciiRules);
    }

    /**
     * @throws InvalidCharacterException
     * @throws Exception\AlreadyPunycodeException
     */
    public function convert(string $host): string
    {
        if (strlen($host) === 0) {
            return $host;
        }

        if (str_contains($host, '/')
            || str_contains($host, ':')
            || str_contains($host, '?')
            || str_contains($host, '@')
        ) {
            throw new InvalidCharacterException('Neither email addresses nor URLs are allowed', 205);
        }

        // These three punctuation characters are treated like the dot
        $host = str_replace(['。', '．', '｡'], '.', $host);

        // Operate per label
        $hostLabels = explode('.', $host);
        foreach ($hostLabels as $index => $label) {
            $asUcs4Array = $this->unicodeTransCoder->convert(
                $label,
                $this->unicodeTransCoder::FORMAT_UTF8,
                $this->unicodeTransCoder::FORMAT_UCS4_ARRAY
            );
            $encoded = $this->punycodeEncoder->convert($asUcs4Array);
            if ($encoded) {
                $hostLabels[$index] = $encoded;
            }
        }

        return implode('.', $hostLabels);
    }
}
