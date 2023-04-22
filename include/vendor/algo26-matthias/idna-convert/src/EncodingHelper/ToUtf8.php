<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\EncodingHelper;

class ToUtf8 implements EncodingHelperInterface
{
    private const DEFAULT_ENCODING = 'ISO-8859-1';

    private string $encoding = self::DEFAULT_ENCODING;

    public function convert(
        string $sourceString,
        ?string $encoding = self::DEFAULT_ENCODING,
        ?bool $safeMode = false
    ) {
        $safe = ($safeMode) ? $sourceString : false;

        $this->encoding = 'ISO-8859-1';
        if ($encoding !== null) {
            $this->encoding = strtoupper($encoding);
        }

        if ($this->encoding === 'UTF-8' || $this->encoding === 'UTF8') {
            return $sourceString;
        }

        if ($this->encoding === 'ISO-8859-1') {
            return utf8_encode($sourceString);
        }

        if ($this->encoding === 'WINDOWS-1252') {
            return utf8_encode($this->mapWindows1252ToIso8859_1($sourceString));
        }

        if ($this->encoding === 'UNICODE-1-1-UTF-7') {
            $this->encoding = 'UTF-7';
        }

        $converted = $this->convertWithLibraries($sourceString);
        if (null !== $converted) {
            return $converted;
        }

        return $safe;
    }

    /**
     * Special treatment for our guys in Redmond
     * Windows-1252 is basically ISO-8859-1 -- with some exceptions
     */
    private function mapWindows1252ToIso8859_1(string $string = ''): string
    {
        $return = '';
        for ($i = 0; $i < strlen($string); ++$i) {
            $codePoint = ord($string[$i]);
            $return .= match ($codePoint) {
                129 => chr(252),
                132 => chr(228),
                142 => chr(196),
                148 => chr(246),
                153 => chr(214),
                154 => chr(220),
                225 => chr(223),
                default => chr($codePoint),
            };
        }

        return $return;
    }

    private function convertWithLibraries(string $string): ?string
    {
        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($string, 'UTF-8', $this->encoding);
            if (false !== $converted) {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv($this->encoding, 'UTF-8', $string);
            if (false !== $converted) {
                return $converted;
            }
        }

        if (function_exists('libiconv')) {
            $converted = @libiconv($this->encoding, 'UTF-8', $string);
            if (false !== $converted) {
                return $converted;
            }
        }

        return null;
    }
}
