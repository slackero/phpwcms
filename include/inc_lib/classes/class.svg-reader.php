<?php
/**
 * Extraction of SVG image metadata.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @description Originally taken from MediaWiki (by Derk-Jan Hartman and Brion Vibber)
 *              and updated/streamlined for phpwcms.
 * @file Defines classes to read SVG metadata
 * @author Derk-Jan Hartman <hartman _at_ videolan d0t org>
 * @author Brion Vibber
 * @author Oliver Georgi <og@phpwcms.org>
 * @copyright Copyright © 2010-2010 Brion Vibber, Derk-Jan Hartman
 * @copyright Copyright (c) 2010-2026, Oliver Georgi
 * @license GPL-2.0-or-later
 */

use enshrined\svgSanitize\Sanitizer;

class SVGMetadataExtractor {

    public const DEFAULT_WIDTH = PHPWCMS_IMAGE_WIDTH;
    public const DEFAULT_HEIGHT = PHPWCMS_IMAGE_HEIGHT;

    /**
     * Sanitize SVG file and return width & height dimensions
     *
     * @param string $filename Absolute path to SVG file
     * @param bool $sanitize Whether to run svgSanitize on the file
     * @return array{width: int, height: int, originalWidth: string, originalHeight: string}|false
     */
    public static function getMetadata($filename, $sanitize = true) {
        if (!is_file($filename)) {
            return false;
        }

        $svgContent = file_get_contents($filename);
        if (!$svgContent) {
            return false;
        }

        if ($sanitize) {
            $sanitizer = new Sanitizer();
            $sanitizer->minify(true);
            $sanitizer->removeXMLTag(true);
            $cleanSVG = $sanitizer->sanitize($svgContent);
            if ($cleanSVG) {
                $svgContent = trim($cleanSVG);
                file_put_contents($filename, $svgContent);
            }
        }

        // Suppress XML warnings and parse root <svg> tag with SimpleXML
        $useErrors = libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($svgContent);
        libxml_use_internal_errors($useErrors);

        if (!$xml || strtolower($xml->getName()) !== 'svg') {
            return false;
        }

        $attr = $xml->attributes();
        $rawWidth = isset($attr->width) ? trim((string)$attr->width) : null;
        $rawHeight = isset($attr->height) ? trim((string)$attr->height) : null;
        $rawViewBox = isset($attr->viewBox) ? trim((string)$attr->viewBox) : null;

        $defaultWidth = self::DEFAULT_WIDTH;
        $defaultHeight = self::DEFAULT_HEIGHT;
        $aspect = 1.0;

        if ($rawViewBox !== null && $rawViewBox !== '') {
            $viewBox = preg_split('/\s*[\s,]\s*/', $rawViewBox);
            if (count($viewBox) === 4) {
                $viewWidth = self::scaleSVGUnit($viewBox[2]);
                $viewHeight = self::scaleSVGUnit($viewBox[3]);
                if ($viewWidth > 0 && $viewHeight > 0) {
                    $aspect = $viewWidth / $viewHeight;
                    $defaultHeight = $defaultWidth / $aspect;
                }
            }
        }

        $width = $rawWidth !== null ? self::scaleSVGUnit($rawWidth, $defaultWidth) : null;
        $height = $rawHeight !== null ? self::scaleSVGUnit($rawHeight, $defaultHeight) : null;

        if ($width === null && $height === null) {
            $width = $defaultWidth;
            $height = $width / $aspect;
        } elseif ($width !== null && $height === null) {
            $height = $width / $aspect;
        } elseif ($height !== null && $width === null) {
            $width = $height * $aspect;
        }

        $calcWidth = max(1, (int)round($width));
        $calcHeight = max(1, (int)round($height));

        return [
            'width' => $calcWidth,
            'height' => $calcHeight,
            'originalWidth' => $rawWidth ?? (string)$calcWidth,
            'originalHeight' => $rawHeight ?? (string)$calcHeight
        ];
    }

    /**
     * Scale CSS/SVG unit to pixel value
     *
     * @param string $length CSS length string (e.g. 100px, 10cm, 50%)
     * @param float|int $viewportSize Viewport reference size for percentage units
     * @return float
     */
    public static function scaleSVGUnit($length, $viewportSize = 512) {
        static $unitLength = [
            'px' => 1.0,
            'pt' => 1.25,
            'pc' => 15.0,
            'mm' => 3.543307,
            'cm' => 35.43307,
            'in' => 90.0,
            'em' => 16.0,
            'ex' => 12.0,
            '' => 1.0
        ];

        if (preg_match('/^\s*([-+]?\d*(?:\.\d+|\d+)(?:[Ee][-+]?\d+)?)\s*(em|ex|px|pt|pc|cm|mm|in|%|)\s*$/', (string)$length, $matches)) {
            $value = (float)$matches[1];
            $unit = $matches[2];
            if ($unit === '%') {
                return $value * 0.01 * $viewportSize;
            }
            return $value * ($unitLength[$unit] ?? 1.0);
        }

        return (float)$length;
    }
}

