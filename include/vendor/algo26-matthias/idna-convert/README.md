# IDNA Convert - pure PHP IDNA converter

![latest stable version](https://img.shields.io/github/tag/algo26-matthias/idna-convert.svg)

## Introduction

The library IdnaConvert allows to convert internationalized domain names (see 
[RFC 3492](http://www.ietf.org/rfc/rfc3492.txt), 
[RFC 5890](http://www.ietf.org/rfc/rfc5890.txt),
[RFC 5891](http://www.ietf.org/rfc/rfc5891.txt),
[RFC 5892](http://www.ietf.org/rfc/rfc5892.txt),
[RFC 5893](http://www.ietf.org/rfc/rfc5893.txt),
[RFC 5894](http://www.ietf.org/rfc/rfc5894.txt),
[RFC 6452](http://www.ietf.org/rfc/rfc6452.txt), 
for details) as they can be used with various registries worldwide to be translated between their original (localized) form and their encoded form as it will be used in the DNS (Domain Name System).

The library provides two classes (`ToIdn` and `ToUnicode` respectively), which expose three public methods to convert between the respective forms. See the Example section below. 
This allows you to convert host names (simple labels like `localhost` or FQHNs like `some-host.domain.example`), email addresses and complete URLs.

Errors, incorrectly encoded or invalid strings will lead to various exceptions.  They should help you find out, what went wrong.  

Unicode strings are expected to be UTF-8 strings. ACE strings (the Punycode form) are always 7bit ASCII strings.

## Validating input

When input is accepted from an external source, its representation can be checked before it is passed to a codec:

```php
use Algo26\IdnaConvert\Validation\InputValidator;
use Algo26\IdnaConvert\Validation\IdnaValidator;

$validator = new InputValidator();
$validator->validateUtf8($input);
$validator->validateUcs4($codePoints);
$validator->validatePunycode($asciiLabel);

$idnaValidator = new IdnaValidator(2008);
$idnaValidator->validateLabel($label);
$idnaValidator->validateDomain($domain);
```

The validator methods return `void` and throw the same exceptions and error codes as the conversion classes. `validatePunycode()` checks the Punycode label syntax. `IdnaValidator` applies the complete IDNA mapping, normalization, contextual rules and domain-name constraints used by `ToIdn`.

## Installation

### Via Composer

```
composer require algo26-matthias/idna-convert
```

### Official ZIP Package

The official ZIP packages are discontinued. Stick to Composer or GitHub to acquire your copy, please.

## Upgrading from a previous version

See [the upgrading notes](./UPGRADING.md) to learn about upgrading from a previous version.

## Version support

The project aims to maintain at most two major versions: the current version and its immediate
predecessor. The transition to v5 includes a short final security-support period for v3.

The support periods start with **v5.0.0 stable, released on 24 November 2026**. Prereleases do not count toward these periods.

| Version | Maintenance scope | Support period |
| --- | --- | --- |
| v5 | Bug fixes, security fixes, new features, and compatibility with new PHP versions | While it is the current major version |
| v4 | Security fixes and important backward-compatible bug fixes; no new features | Until 24 November 2027 |
| v3 | Critical security fixes only, where a backport is feasible | Until 24 February 2027, then end of life |

New PHP compatibility updates for v3 stop with the announcement of this policy. Its support for
PHP 7.2–8.0 is intended for legacy installations; those PHP versions are no longer supported upstream.
The longer v4 transition allows applications to adapt to v5's stricter validation, changed normalization,
and additional exceptions, including effects on stored domain names and comparisons.

At end of life, releases and branches remain available, but further maintenance is not guaranteed.
See the [maintenance and security policy](./SECURITY.md) for backport rules and the
[upgrading notes](./UPGRADING.md) for migration guidance.


## Examples

### Example 1. 

Say we wish to encode the domain name nörgler.com:

```php
<?php  
// Include the class
use Algo26\IdnaConvert\ToIdn;
// Instantiate it
$IDN = new ToIdn();
// The input string, if input is not UTF-8 or UCS-4, it must be converted before  
$input = utf8_encode('nörgler.com');  
// Encode it to its punycode presentation  
$output = $IDN->convert($input);  
// Output, what we got now  
echo $output; // This will read: xn--nrgler-wxa.com
```


### Example 2. 

We received an email from a internationalized domain and are want to decode it to its Unicode form.

```php
<?php  
// Include the class
use Algo26\IdnaConvert\ToUnicode;
// Instantiate it
$IDN = new ToUnicode();
// The input string  
$input = 'andre@xn--brse-5qa.xn--knrz-1ra.info';  
// Encode it to its punycode presentation  
$output = $IDN->convertEmailAddress($input);  
// Output, what we got now, if output should be in a format different to UTF-8  
// or UCS-4, you will have to convert it before outputting it  
echo utf8_decode($output); // This will read: andre@börse.knörz.info
```


### Example 3. 

The input is read from a UCS-4 coded file and encoded line by line. By appending the optional second parameter we tell encode() about the input format to be used

```php
<?php  
// Include the class
use Algo26\IdnaConvert\ToIdn;
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;
// Instantiate
$IDN = new ToIdn();
$UCTC = new TranscodeUnicode();
// Iterate through the input file line by line  
foreach (file('ucs4-domains.txt') as $line) {
    $utf8String = $UCTC->convert(trim($line), 'ucs4', 'utf8');
    echo $IDN->convert($utf8String);
    echo "\n";
}
```


### Example 4. 

We wish to convert a whole URI into the IDNA form, but leave the path or query string component of it alone. Just using encode() would lead to mangled paths or query strings. Here the public method convertUrl() comes into play:

```php
<?php  
// Include the class
use Algo26\IdnaConvert\ToIdn;
// Instantiate it
$IDN = new ToIdn();
// The input string, a whole URI in UTF-8 (!)  
$input = 'http://nörgler:secret@nörgler.com/my_päth_is_not_ÄSCII/');  
// Encode it to its punycode presentation  
$output = $IDN->convertUrl($input);
// Output, what we got now  
echo $output; // http://nörgler:secret@xn--nrgler-wxa.com/my_päth_is_not_ÄSCII/
```


### Example 5. 

Per default, the class converts strings according to IDNA version 2008. To support IDNA 2003, the class needs to be invoked with an additional parameter.

```php
<?php  
// Include the class  
use Algo26\IdnaConvert\ToIdn;
// Instantiate it, switching to IDNA 2003, the original, now outdated standard
$IDN = new ToIdn(2003);
// Something containing the German letter ß  
$input = 'meine-straße.example';
// Encode it to its punycode representation  
$output = $IDN->convert($input);  
// Output what we got now  
echo $output; // meine-strasse.example
  
// Switch back to IDNA 2008
$IDN = new ToIdn(2008);
// Something containing the German letter ß  
$input = 'meine-straße.example';  
// Encode it to its punycode representation  
$output = $IDN->convert($input);
// Output what we got now  
echo $output; // xn--meine-strae-46a.example
```


## Encoding helper

In case you have strings in encodings other than ISO-8859-1 and UTF-8 you might need to translate these strings to UTF-8 before feeding the IDNA converter with it.
PHP's built-in functions `utf8_encode()` and `utf8_decode()` can only deal with ISO-8859-1.  
Use the encoding helper class supplied with this package for the conversion. It requires either iconv, libiconv or mbstring installed together with one of the relevant PHP extensions. The functions you will find useful are
`toUtf8()` as a replacement for `utf8_encode()` and
`fromUtf8()` as a replacement for `utf8_decode()`.

Example usage:

```php
<?php  
use Algo26\IdnaConvert\ToIdn;
use Algo26\IdnaConvert\EncodingHelper\ToUtf8;

$IDN = new ToIdn();
$encodingHelper = new ToUtf8();

$mystring = $encodingHelper->convert('<something in e.g. ISO-8859-15', 'ISO-8859-15');
echo $IDN->convert($mystring);
```


## UCTC &mdash; Unicode Transcoder

Another class you might find useful when dealing with one or more of the Unicode encoding flavours. It can transcode into each other:
- UCS-4 string / array  
- UTF-8  
- UTF-7  
- UTF-7 IMAP (modified UTF-7)  
All encodings expect / return a string in the given format, with one major exception: UCS-4 array is just an array, where each value represents one code-point in the string, i.e. every value is a 32bit integer value.

Example usage:

```php
<?php  
use Algo26\IdnaConvert\TranscodeUnicode\TranscodeUnicode;
$transcodeUnicode = new TranscodeUnicode();

$mystring = 'nörgler.com';  
echo $transcodeUnicode->convert($mystring, 'utf8', 'utf7imap');
```

## Running tests

The library is supplied with a `compose.yml`, that allows to run the supplied tests. This assumes, you have Docker installed and docker compose available as a command. Just issue

```
docker compose run --rm idna-convert-test # Unit tests
docker compose run --rm idna-convert-infection # Mutation tests
```
in you local command line and see the output of PHPUnit / Infection.

### IDNA conformance checks

CI runs the complete pinned Unicode 18.0.0 `IdnaTestV2.txt` corpus against `ToIdn`, with
non-transitional UTS #46 mapping followed by strict IDNA2008 validation. STD3, hyphen, and DNS-length
checks are enabled. To run this check locally after installing the development dependencies, download
the [test corpus](https://www.unicode.org/Public/18.0.0/idna/IdnaTestV2.txt) and the
[mapping reference](https://www.unicode.org/Public/18.0.0/idna/IdnaMappingTable.txt), then run:

```sh
php tools/verify-idna.php /path/to/IdnaTestV2.txt /path/to/IdnaMappingTable.txt
```

Both reference files are checksum-verified. Expected stricter rejections are determined from the
upstream mapping reference, independently of the library's generated tables. The checker verifies
all 6,396 cases without skipping any:

- 398 cases require an exact ToASCII result.
- 5,836 cases require rejection according to the corpus's non-transitional ToASCII error status.
- 160 otherwise successful UTS #46 cases require rejection under the stricter IDNA2008 profile.
- 2 cases containing isolated surrogates are supplied as illegal UTF-8 bytes and must be rejected.

Only explicit input-validation exceptions count as expected rejection. Internal errors such as
`TypeError`, unexpected exceptions, incorrect output, and unexpected acceptance fail the check.
These results cover this ToASCII profile; they do not assert full UTS #46 conformance, transitional
processing, or conformance of the separate `ToUnicode` decoder. ContextO rules, which the upstream
corpus does not test, are covered by the library's unit tests.

## Reporting bugs

Please use the [issues tab on GitHub](https://github.com/algo26-matthias/idna-convert/issues) to report any bugs or feature requests.
