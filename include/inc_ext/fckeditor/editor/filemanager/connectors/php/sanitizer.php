<?php

/**
 * Regular expression to match various types of character references in
 * Sanitizer::normalizeCharReferences and Sanitizer::decodeCharReferences
 */
define( 'MW_CHAR_REFS_REGEX',
'/&([A-Za-z0-9\x80-\xff]+);
	 |&\#([0-9]+);
	 |&\#x([0-9A-Za-z]+);
	 |&\#X([0-9A-Za-z]+);
	 |(&)/x' );

/**
 * codepointToUtf8( UNICODE_REPLACEMENT )
 */
define( 'UTF8_REPLACEMENT', "\xef\xbf\xbd");

/**
 * Class borrowed from Mediawiki, based on the following files:
 * Sanitizer.php, SpecialUpload.php, UtfNormal.php, UtfNormalUtil.php
 *
 */
class Sanitizer {

	/**
    * List of all named character entities defined in HTML 4.01
    * http://www.w3.org/TR/html4/sgml/entities.html
    */
	var $htmlEntities = array(
	'Aacute'   => 193,
	'aacute'   => 225,
	'Acirc'    => 194,
	'acirc'    => 226,
	'acute'    => 180,
	'AElig'    => 198,
	'aelig'    => 230,
	'Agrave'   => 192,
	'agrave'   => 224,
	'alefsym'  => 8501,
	'Alpha'    => 913,
	'alpha'    => 945,
	'amp'      => 38,
	'and'      => 8743,
	'ang'      => 8736,
	'Aring'    => 197,
	'aring'    => 229,
	'asymp'    => 8776,
	'Atilde'   => 195,
	'atilde'   => 227,
	'Auml'     => 196,
	'auml'     => 228,
	'bdquo'    => 8222,
	'Beta'     => 914,
	'beta'     => 946,
	'brvbar'   => 166,
	'bull'     => 8226,
	'cap'      => 8745,
	'Ccedil'   => 199,
	'ccedil'   => 231,
	'cedil'    => 184,
	'cent'     => 162,
	'Chi'      => 935,
	'chi'      => 967,
	'circ'     => 710,
	'clubs'    => 9827,
	'cong'     => 8773,
	'copy'     => 169,
	'crarr'    => 8629,
	'cup'      => 8746,
	'curren'   => 164,
	'dagger'   => 8224,
	'Dagger'   => 8225,
	'darr'     => 8595,
	'dArr'     => 8659,
	'deg'      => 176,
	'Delta'    => 916,
	'delta'    => 948,
	'diams'    => 9830,
	'divide'   => 247,
	'Eacute'   => 201,
	'eacute'   => 233,
	'Ecirc'    => 202,
	'ecirc'    => 234,
	'Egrave'   => 200,
	'egrave'   => 232,
	'empty'    => 8709,
	'emsp'     => 8195,
	'ensp'     => 8194,
	'Epsilon'  => 917,
	'epsilon'  => 949,
	'equiv'    => 8801,
	'Eta'      => 919,
	'eta'      => 951,
	'ETH'      => 208,
	'eth'      => 240,
	'Euml'     => 203,
	'euml'     => 235,
	'euro'     => 8364,
	'exist'    => 8707,
	'fnof'     => 402,
	'forall'   => 8704,
	'frac12'   => 189,
	'frac14'   => 188,
	'frac34'   => 190,
	'frasl'    => 8260,
	'Gamma'    => 915,
	'gamma'    => 947,
	'ge'       => 8805,
	'gt'       => 62,
	'harr'     => 8596,
	'hArr'     => 8660,
	'hearts'   => 9829,
	'hellip'   => 8230,
	'Iacute'   => 205,
	'iacute'   => 237,
	'Icirc'    => 206,
	'icirc'    => 238,
	'iexcl'    => 161,
	'Igrave'   => 204,
	'igrave'   => 236,
	'image'    => 8465,
	'infin'    => 8734,
	'int'      => 8747,
	'Iota'     => 921,
	'iota'     => 953,
	'iquest'   => 191,
	'isin'     => 8712,
	'Iuml'     => 207,
	'iuml'     => 239,
	'Kappa'    => 922,
	'kappa'    => 954,
	'Lambda'   => 923,
	'lambda'   => 955,
	'lang'     => 9001,
	'laquo'    => 171,
	'larr'     => 8592,
	'lArr'     => 8656,
	'lceil'    => 8968,
	'ldquo'    => 8220,
	'le'       => 8804,
	'lfloor'   => 8970,
	'lowast'   => 8727,
	'loz'      => 9674,
	'lrm'      => 8206,
	'lsaquo'   => 8249,
	'lsquo'    => 8216,
	'lt'       => 60,
	'macr'     => 175,
	'mdash'    => 8212,
	'micro'    => 181,
	'middot'   => 183,
	'minus'    => 8722,
	'Mu'       => 924,
	'mu'       => 956,
	'nabla'    => 8711,
	'nbsp'     => 160,
	'ndash'    => 8211,
	'ne'       => 8800,
	'ni'       => 8715,
	'not'      => 172,
	'notin'    => 8713,
	'nsub'     => 8836,
	'Ntilde'   => 209,
	'ntilde'   => 241,
	'Nu'       => 925,
	'nu'       => 957,
	'Oacute'   => 211,
	'oacute'   => 243,
	'Ocirc'    => 212,
	'ocirc'    => 244,
	'OElig'    => 338,
	'oelig'    => 339,
	'Ograve'   => 210,
	'ograve'   => 242,
	'oline'    => 8254,
	'Omega'    => 937,
	'omega'    => 969,
	'Omicron'  => 927,
	'omicron'  => 959,
	'oplus'    => 8853,
	'or'       => 8744,
	'ordf'     => 170,
	'ordm'     => 186,
	'Oslash'   => 216,
	'oslash'   => 248,
	'Otilde'   => 213,
	'otilde'   => 245,
	'otimes'   => 8855,
	'Ouml'     => 214,
	'ouml'     => 246,
	'para'     => 182,
	'part'     => 8706,
	'permil'   => 8240,
	'perp'     => 8869,
	'Phi'      => 934,
	'phi'      => 966,
	'Pi'       => 928,
	'pi'       => 960,
	'piv'      => 982,
	'plusmn'   => 177,
	'pound'    => 163,
	'prime'    => 8242,
	'Prime'    => 8243,
	'prod'     => 8719,
	'prop'     => 8733,
	'Psi'      => 936,
	'psi'      => 968,
	'quot'     => 34,
	'radic'    => 8730,
	'rang'     => 9002,
	'raquo'    => 187,
	'rarr'     => 8594,
	'rArr'     => 8658,
	'rceil'    => 8969,
	'rdquo'    => 8221,
	'real'     => 8476,
	'reg'      => 174,
	'rfloor'   => 8971,
	'Rho'      => 929,
	'rho'      => 961,
	'rlm'      => 8207,
	'rsaquo'   => 8250,
	'rsquo'    => 8217,
	'sbquo'    => 8218,
	'Scaron'   => 352,
	'scaron'   => 353,
	'sdot'     => 8901,
	'sect'     => 167,
	'shy'      => 173,
	'Sigma'    => 931,
	'sigma'    => 963,
	'sigmaf'   => 962,
	'sim'      => 8764,
	'spades'   => 9824,
	'sub'      => 8834,
	'sube'     => 8838,
	'sum'      => 8721,
	'sup'      => 8835,
	'sup1'     => 185,
	'sup2'     => 178,
	'sup3'     => 179,
	'supe'     => 8839,
	'szlig'    => 223,
	'Tau'      => 932,
	'tau'      => 964,
	'there4'   => 8756,
	'Theta'    => 920,
	'theta'    => 952,
	'thetasym' => 977,
	'thinsp'   => 8201,
	'THORN'    => 222,
	'thorn'    => 254,
	'tilde'    => 732,
	'times'    => 215,
	'trade'    => 8482,
	'Uacute'   => 218,
	'uacute'   => 250,
	'uarr'     => 8593,
	'uArr'     => 8657,
	'Ucirc'    => 219,
	'ucirc'    => 251,
	'Ugrave'   => 217,
	'ugrave'   => 249,
	'uml'      => 168,
	'upsih'    => 978,
	'Upsilon'  => 933,
	'upsilon'  => 965,
	'Uuml'     => 220,
	'uuml'     => 252,
	'weierp'   => 8472,
	'Xi'       => 926,
	'xi'       => 958,
	'Yacute'   => 221,
	'yacute'   => 253,
	'yen'      => 165,
	'Yuml'     => 376,
	'yuml'     => 255,
	'Zeta'     => 918,
	'zeta'     => 950,
	'zwj'      => 8205,
	'zwnj'     => 8204 );

	/**
     * Return UTF-8 sequence for a given Unicode code point.
    * May die if fed out of range data.
    *
    * @param $codepoint Integer:
    * @return String
    * @public
    */
	function codepointToUtf8( $codepoint ) {
		if($codepoint <		0x80)
			return chr($codepoint);
		if($codepoint <    0x800)
			return chr($codepoint >>	6 & 0x3f | 0xc0) .
				chr($codepoint		  & 0x3f | 0x80);
		if($codepoint <  0x10000)
			return chr($codepoint >> 12 & 0x0f | 0xe0) .
				chr($codepoint >>	6 & 0x3f | 0x80) .
				chr($codepoint		  & 0x3f | 0x80);
		if($codepoint < 0x110000)
			return chr($codepoint >> 18 & 0x07 | 0xf0) .
				chr($codepoint >> 12 & 0x3f | 0x80) .
				chr($codepoint >>	6 & 0x3f | 0x80) .
				chr($codepoint		  & 0x3f | 0x80);

		return $codepoint ;
	}

	/**
	 * Decode any character references, numeric or named entities,
	 * in the text and return a UTF-8 string.
	 *
	 * @param string $text
	 * @return string
	 * @public
	 * @static
	 */
	function decodeCharReferences( $text ) {
		return preg_replace_callback( MW_CHAR_REFS_REGEX, array( $this, 'decodeCharReferencesCallback' ), $text ) ;
	}

	/**
	 * @param string $matches
	 * @return string
	 */
	function decodeCharReferencesCallback( $matches ) {
		if( $matches[1] != '' ) {
			return $this->decodeEntity( $matches[1] ) ;
		} elseif( $matches[2] != '' ) {
			return  $this->decodeChar( intval( $matches[2] ) ) ;
		} elseif( $matches[3] != ''  ) {
			return  $this->decodeChar( hexdec( $matches[3] ) ) ;
		} elseif( $matches[4] != '' ) {
			return  $this->decodeChar( hexdec( $matches[4] ) ) ;
		}
		# Last case should be an ampersand by itself
		return $matches[0] ;
	}

	/**
	 * Return UTF-8 string for a codepoint if that is a valid
	 * character reference, otherwise U+FFFD REPLACEMENT CHARACTER.
	 * @param int $codepoint
	 * @return string
	 */
	function decodeChar( $codepoint ) {
		if( $this->validateCodepoint( $codepoint ) ) {
			return $this->codepointToUtf8( $codepoint ) ;
		} else {
			return UTF8_REPLACEMENT ;
		}
	}

	/**
	 * If the named entity is defined in the HTML 4.0/XHTML 1.0 DTD,
	 * return the UTF-8 encoding of that character. Otherwise, returns
	 * pseudo-entity source (eg &foo;)
	 *
	 * @param string $name
	 * @return string
	 */
	function decodeEntity( $name ) {
		if( isset( $this->$htmlEntities[$name] ) ) {
			return $this->codepointToUtf8( $this->$htmlEntities[$name] ) ;
		} else {
			return "&$name;" ;
		}
	}

	/**
	 * Returns true if a given Unicode codepoint is a valid character in XML.
	 * @param int $codepoint
	 * @return bool
	 */
	function validateCodepoint( $codepoint ) {
		return ($codepoint ==    0x09)
		|| ($codepoint ==    0x0a)
		|| ($codepoint ==    0x0d)
		|| ($codepoint >=    0x20 && $codepoint <=   0xd7ff)
		|| ($codepoint >=  0xe000 && $codepoint <=   0xfffd)
		|| ($codepoint >= 0x10000 && $codepoint <= 0x10ffff) ;
	}

	/**
    * Heuristig for detecting files that *could* contain JavaScript instructions or
	* things that may look like HTML to a browser and are thus
	* potentially harmful. The present implementation will produce false positives in some situations.
	*
	* @param string $file Pathname to the file
	* @return bool true if the file contains something looking like embedded scripts
	*/
	function detectScript( $file ) {

		#For binarie field, just check the first K.

		$fp = fopen( $file, 'rb' ) ;
		$chunk = fread( $fp, 1024 ) ;
		fclose( $fp ) ;

		$chunk = strtolower( $chunk ) ;

		if (!$chunk)
			return false ;

		#decode from UTF-16 if needed (could be used for obfuscation).
		if ( substr( $chunk, 0, 2 ) == "\xfe\xff" )
			$enc = "UTF-16BE" ;
		elseif ( substr( $chunk, 0, 2 ) == "\xff\xfe" )
			$enc = "UTF-16LE" ;
		else
			$enc= NULL ;

		if ( $enc ) {
			$chunk_tmp = @iconv($enc, "ASCII//IGNORE", $chunk) ;
			if ( $chunk_tmp )
				$chunk = $chunk_tmp ;
		}

		$chunk = trim( $chunk ) ;

		#FIXME: convert from UTF-16 if necessarry!

		#check for HTML doctype
		if ( eregi( "<!DOCTYPE *X?HTML", $chunk ) ) {
			return true ;
		}

		/**
		* Internet Explorer for Windows performs some really stupid file type
		* autodetection which can cause it to interpret valid image files as HTML
		* and potentially execute JavaScript, creating a cross-site scripting
		* attack vectors.
		*
		* Apple's Safari browser also performs some unsafe file type autodetection
		* which can cause legitimate files to be interpreted as HTML if the
		* web server is not correctly configured to send the right content-type
		* (or if you're really uploading plain text and octet streams!)
		*
		* Returns true if IE is likely to mistake the given file for HTML.
		* Also returns true if Safari would mistake the given file for HTML
		* when served with a generic content-type.
		*/

		$tags = array(
		'<body',
		'<head',
		'<html',   #also in safari
		'<img',
		'<pre',
		'<script', #also in safari
		'<table',
		'<title'
		) ;


		foreach( $tags as $tag ) {
			if( false !== strpos( $chunk, $tag ) ) {
				return true ;
			}
		}

		/*
		* look for javascript
		*/

		#resolve entity-refs to look at attributes. may be harsh on big files... cache result?
		$chunk = $this->decodeCharReferences( $chunk ) ;

		#look for script-types
		if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) )
			return true ;

		#look for html-style script-urls
		if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
			return true ;

		#look for css-style script-urls
		if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
			return true ;

		return false ;
	}
}
?>