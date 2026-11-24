<?php

declare(strict_types=1);

namespace Algo26\IdnaConvert;

use InvalidArgumentException;

abstract class AbstractIdnaConvert
{
    abstract public function convert(string $host): string;

    public function convertEmailAddress(string $emailAddress): string
    {
        $separatorPosition = strrpos($emailAddress, '@');
        if ($separatorPosition === false) {
            throw new InvalidArgumentException('The given string does not look like an email address', 206);
        }

        return sprintf(
            '%s@%s',
            substr($emailAddress, 0, $separatorPosition),
            $this->convert(substr($emailAddress, $separatorPosition + 1))
        );
    }

    public function convertUrl(string $url): string
    {
        // PHP 8.5's parse_url() replaces bytes from unencoded Unicode characters.
        // Percent-encode those bytes for validation, but modify the original URL below.
        $parsed = parse_url($this->encodeNonAsciiBytes($url));
        if ($parsed === false) {
            throw new InvalidArgumentException('The given string does not look like a URL', 206);
        }

        if (!isset($parsed['host'])) {
            return $url;
        }

        $hostRange = $this->extractHostRange($url);
        if ($hostRange === null) {
            throw new InvalidArgumentException('The given string does not look like a URL', 206);
        }

        [$host, $hostOffset] = $hostRange;
        if ($host[0] === '[') {
            return $url;
        }

        return substr_replace(
            $url,
            $this->convert($host),
            $hostOffset,
            strlen($host)
        );
    }

    /**
     * @return array{string, int}|null
     */
    private function extractHostRange(string $url): ?array
    {
        if (
            preg_match(
                '~^(?:[a-z][a-z0-9+.-]*:)?//([^/?#]*)~i',
                $url,
                $matches,
                PREG_OFFSET_CAPTURE
            ) !== 1
        ) {
            return null;
        }

        [$authority, $authorityOffset] = $matches[1];
        $atPosition = strrpos($authority, '@');
        $hostOffset = $atPosition === false ? 0 : $atPosition + 1;
        $hostAndPort = substr($authority, $hostOffset);

        if (str_starts_with($hostAndPort, '[')) {
            // The caller only needs to recognize an IP literal; parse_url() already validated its syntax.
            $host = $hostAndPort;
        } else {
            $portSeparator = strrpos($hostAndPort, ':');
            $host = $portSeparator === false
                ? $hostAndPort
                : substr($hostAndPort, 0, $portSeparator);
        }

        if ($host === '') {
            return null;
        }

        return [$host, $authorityOffset + $hostOffset];
    }

    private function encodeNonAsciiBytes(string $url): string
    {
        $encoded = '';
        $length = strlen($url);
        for ($offset = 0; $offset < $length; ++$offset) {
            $byte = ord($url[$offset]);
            $encoded .= ($byte & 0x80) !== 0
                ? sprintf('%%%02X', $byte)
                : $url[$offset];
        }

        return $encoded;
    }
}
