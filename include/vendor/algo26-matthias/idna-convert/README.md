# IDNA Convert - pure PHP IDNA converter

![latest stable version](https://img.shields.io/github/tag/algo26-matthias/idna-convert.svg)
![Travis CI status](https://img.shields.io/travis/algo26-matthias/idna-convert.svg)

Project homepage: <http://idnaconv.net><br>
by Matthias Sommerfeld <matthias.sommerfeld@algo26.de><br>

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

Errors, incorrectly encoded or invalid strings will lead to various exceptions.  They should help you to find out, what went wrong.  

Unicode strings are expected to be UTF-8 strings. ACE strings (the Punycode form) are always 7bit ASCII strings.

## Installation

### Via Composer

```
composer require algo26-matthias/idna-convert
```

### Official ZIP Package

The official ZIP packages are discontinued. Stick to Composer or Github to acquire your copy, please.

## Upgrading from a previous version

See [the upgrading notes](./UPGRADING.md) to learn about upgrading from a previous version.


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

The input is read from a UCS-4 coded file and encoded line by line. By appending the optional second parameter we tell enode() about the input format to be used

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
$IDN = new ToIdn(2008);
// Sth. containing the German letter ß  
$input = 'meine-straße.example';
// Encode it to its punycode presentation  
$output = $IDN->convert($input);  
// Output, what we got now  
echo $output; // xn--meine-strae-46a.example
  
// Switch back to IDNA 2008
$IDN = new ToIdn(2003);
// Sth. containing the German letter ß  
$input = 'meine-straße.example';  
// Encode it to its punycode presentation  
$output = $IDN->convert($input);
// Output, what we got now  
echo $output; // meine-strasse.example
```


## Encoding helper

In case you have strings in encodings other than ISO-8859-1 and UTF-8 you might need to translate these strings to UTF-8 before feeding the IDNA converter with it.
PHP's built in functions `utf8_encode()` and `utf8_decode()` can only deal with ISO-8859-1.  
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

## Run PHPUnit tests

The library is supplied with a `docker-compose.yml`, that allows to run the supplied tests. This assumes, you have Docker installed and docker-compose available as a command. Just issue

```
docker-compose up
```
in you local command line and see the output of PHPUnit.

## Reporting bugs

Please use the [issues tab on GitHub](https://github.com/algo26-matthias/idna-convert/issues) to report any bugs or feature requests.

## Contact the author

For questions, bug reports and security issues just send me an email.

algo26 Beratungs GmbH<br>
c/o Matthias Sommerfeld<br>
Wichertstr. 5<br>
D-10439 Berlin<br>
<br>
Germany<br>
<br>
mailto:matthias.sommerfeld@algo26.de

