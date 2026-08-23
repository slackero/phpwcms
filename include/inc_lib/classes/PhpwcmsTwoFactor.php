<?php
/**
 * phpwcms Two-Factor Authentication (TOTP) & QR Code Helper
 * Wrapper around pragmarx/google2fa and bacon/bacon-qr-code
 *
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright (c) 2002-2026, Oliver Georgi
 * @license http://opensource.org/licenses/GPL-2.0 GNU GPL-2
 */

use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class PhpwcmsTwoFactor
{
    private static ?Google2FA $g2fa = null;

    /**
     * Get or create Google2FA instance.
     */
    public static function getInstance(): Google2FA
    {
        if (self::$g2fa === null) {
            self::$g2fa = new Google2FA();
        }
        return self::$g2fa;
    }

    /**
     * Generate a cryptographically secure Base32 secret key.
     *
     * @param int $length
     * @return string
     */
    public static function generateSecret(int $length = 32): string
    {
        return self::getInstance()->generateSecretKey($length);
    }

    /**
     * Generate standard TOTP code for a given timestamp and secret.
     *
     * @param string $secret Base32 encoded secret
     * @param int|null $timeSlice Unix timestamp (null for current time)
     * @return string
     */
    public static function getCode(string $secret, ?int $timeSlice = null): string
    {
        $g2fa = self::getInstance();
        if ($timeSlice !== null) {
            $timeStep = (int)floor($timeSlice / 30);
            return $g2fa->oathTotp($secret, $timeStep);
        }
        return $g2fa->getCurrentOtp($secret);
    }

    /**
     * Verify submitted TOTP code against secret allowing a time window drift.
     *
     * @param string $secret
     * @param string $code
     * @param int $discrepancy Allowed time window drift (1 = +/- 30s)
     * @param int|null $currentTime
     * @return bool
     */
    public static function verifyCode(string $secret, string $code, int $discrepancy = 1, ?int $currentTime = null): bool
    {
        $code = trim($code);
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $timestamp = self::getInstance()->verifyKeyNewer($secret, $code, null, $discrepancy, $currentTime);
        return $timestamp !== false;
    }

    /**
     * Generate standard otpauth URI for authenticator applications.
     *
     * @param string $label Account label (e.g. username)
     * @param string $secret Base32 secret
     * @param string $issuer Issuer name (default: phpwcms)
     * @return string
     */
    public static function getOtpAuthUrl(string $label, string $secret, string $issuer = 'phpwcms'): string
    {
        return self::getInstance()->getQRCodeUrl($issuer, $label, $secret);
    }

    /**
     * Generate a list of plaintext backup recovery codes (e.g. 8 codes of 8 uppercase hex characters).
     *
     * @param int $count
     * @return array<string>
     */
    public static function generateBackupCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $part1 = bin2hex(random_bytes(2));
            $part2 = bin2hex(random_bytes(2));
            $codes[] = strtoupper($part1 . '-' . $part2);
        }
        return $codes;
    }

    /**
     * Hash array of backup codes for secure database storage.
     *
     * @param array<string> $plainCodes
     * @return array<string>
     */
    public static function hashBackupCodes(array $plainCodes): array
    {
        $hashed = [];
        foreach ($plainCodes as $code) {
            $clean = str_replace('-', '', strtoupper(trim($code)));
            if ($clean !== '') {
                $hashed[] = password_hash($clean, PASSWORD_DEFAULT);
            }
        }
        return $hashed;
    }

    /**
     * Verify and consume a single-use backup recovery code.
     * Returns true and updates the array if matched.
     *
     * @param array<string> $hashedCodes Reference to hashed codes array
     * @param string $submittedCode
     * @return bool
     */
    public static function verifyAndConsumeBackupCode(array &$hashedCodes, string $submittedCode): bool
    {
        $clean = str_replace('-', '', strtoupper(trim($submittedCode)));
        if ($clean === '') {
            return false;
        }

        foreach ($hashedCodes as $index => $hash) {
            if (password_verify($clean, $hash)) {
                unset($hashedCodes[$index]);
                $hashedCodes = array_values($hashedCodes);
                return true;
            }
        }

        return false;
    }

    /**
     * Render an inline SVG QR Code directly from text content using bacon/bacon-qr-code.
     *
     * @param string $text Content (e.g. otpauth:// URL)
     * @param int $size Width/height in pixels
     * @return string Valid SVG markup
     */
    public static function getQrCodeSvg(string $text, int $size = 200): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($text);
    }
}
