<?php declare(strict_types=1);

namespace Algo26\IdnaConvert\EncodingHelper;

class FromUtf8 implements EncodingHelperInterface
{
    private const DEFAULT_ENCODING = 'ISO-8859-1';

    private string $encoding = self::DEFAULT_ENCODING;

    public function convert(
        string $sourceString,
        ?string $encoding = self::DEFAULT_ENCODING,
        ?bool $safeMode = false
    ): string {
        $safe = ($safeMode) ? $sourceString : false;

        $this->encoding = 'ISO-8859-1';
        if ($encoding !== null) {
            $this->encoding = strtoupper($encoding);
        }

        if ($this->encoding === 'UTF-8' || $this->encoding === 'UTF8') {
            return $sourceString;
        }

        if ($this->encoding === 'ISO-8859-1') {
            return utf8_decode($sourceString);
        }

        if ($this->encoding === 'WINDOWS-1252') {
            return self::mapIso8859_1ToWindows1252(utf8_decode($sourceString));
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
    private function mapIso8859_1ToWindows1252(string $string = ''): string
    {
        $return = '';
        for ($i = 0; $i < strlen($string); ++$i) {
            $codePoint = ord($string[$i]);
            $return .= match ($codePoint) {
                196 => chr(142),
                214 => chr(153),
                220 => chr(154),
                223 => chr(225),
                228 => chr(132),
                246 => chr(148),
                252 => chr(129),
                default => chr($codePoint),
            };
        }

        return $return;
    }

    private function convertWithLibraries(string $string): ?string
    {
        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($string, $this->encoding, 'UTF-8');
            if (false !== $converted) {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', $this->encoding, $string);
            if (false !== $converted) {
                return $converted;
            }
        }

        if (function_exists('libiconv')) {
            $converted = @libiconv('UTF-8', $this->encoding, $string);
            if (false !== $converted) {
                return $converted;
            }
        }

        return null;
    }
}
