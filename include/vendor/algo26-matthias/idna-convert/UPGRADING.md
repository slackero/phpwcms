# Upgrading from previous versions

## 5.0.0

Unicode normalization now uses dedicated generated tables. IDNA 2003 labels use Unicode 3.2 and NFKC as
required by RFC 3491; IDNA 2008 labels use Unicode 18.0 and NFC. Both paths include recursive decomposition,
canonical ordering, composition exclusions, and algorithmic Hangul normalization. Consequently, labels that
previously retained decomposed sequences can now produce their canonical composed representation.

IDNA 2008 input is now processed with the Unicode 18.0 UTS #46 non-transitional compatibility mapping and
then validated against IDNA2008, including ContextJ, ContextO, and bidirectional-label rules. This preserves
`ß` and final sigma instead of applying IDNA 2003 case folding, and rejects symbols that are not valid IDNA2008
code points. For example, `daẞ.example` now becomes `xn--da-hia.example`.

IDNA validity checks are stricter. Empty labels, labels beginning with a Unicode mark, invalid IDNA2003
bidirectional text, leading or trailing hyphens, hyphens in positions three and four, and labels that map to
an empty value are rejected. Existing canonical A-labels such as `xn--mller-kva` are accepted idempotently;
invalid or non-canonical A-labels are rejected.

`ToIdn::__construct()` accepts two additional flags after `useStd3AsciiRules`: `checkHyphens` and
`verifyDnsLength`. Both default to `true`. DNS length validation enforces the 63-byte label and 253-byte
domain limits and rejects an empty root label. Pass `false` as the fourth argument only when the caller is
deliberately processing a domain fragment or performs DNS length validation elsewhere.

IDNA2003 now applies prohibited-output and bidirectional checks after mapping and Unicode 3.2 NFKC, as
required by Nameprep. Inputs that previously produced invalid output, such as U+037A mapping to an ASCII
space, now throw `InvalidCharacterException`.

Unicode-derived tables are reproducibly generated and covered by the Unicode License v3 in `UNICODE-LICENSE`.

Native parameter and return types were added to public APIs. Custom implementations of the supplied
interfaces and subclasses overriding these methods must update their signatures accordingly.

**BC break — UCS-4 arrays:** When converting from `ucs4array` to another encoding with the default
`safeMode: false`, `TranscodeUnicode::convert()` now requires a zero-based, consecutive list of integer
Unicode scalar values. `ToPunycode::convert()` also enforces this requirement. For example, `[4 => 97]`
previously converted to `"a"` through the transcoder but now throws `InvalidArgumentException` (code 308).
Use `array_values($codePoints)` to reindex existing arrays without changing their order. Non-integer
elements are rejected with code 308; surrogate code points and values outside U+0000–U+10FFFF are rejected
with `InvalidCharacterException` (code 305). Converting between identical encodings still returns the
input unchanged and is not a validation operation.

**BC break — Punycode subclasses:** The protected `AbstractPunycode::$unicodeTransCoder` property has
been removed. Subclasses using it must either own a `TranscodeUnicode` instance or migrate UTF-8/UCS-4
conversion to the protected `$ucs4Codec`: use `$this->ucs4Codec->decode($utf8)` for a code-point list and
`$this->ucs4Codec->encode($codePoints)` for UTF-8. Other encoding conversions still require
`TranscodeUnicode`.

**BC break — Punycode interface implementations:** `PunycodeInterface::__construct()` now declares
`bool $checkHyphens = true` and `?bool $checkBidi = null` after `?bool $useStd3AsciiRules = false`.
Custom implementations must add compatible parameters, even if they do not use these options; otherwise
PHP rejects the class declaration. Existing calls with the original two arguments remain valid.

The abandoned `jakeasmith/http_build_url` package is no longer required. URL conversion now replaces
only the host in the original URL, preserving the spelling and encoding of all other components.

## 4.2.0

The extra dependency to ext/intl, accidentally introduced with v 4.1 is no longer required.


## 4.1.0

It is strongly advised to install and configure either the 
[Internationalization Functions](https://www.php.net/manual/en/book.intl.php) or the
[Multibyte Strings](https://www.php.net/manual/de/book.mbstring.php)
extension. _Intl_ is preferred.

If neither is available, you might experience wrong conversion results due to insufficient case folding behaviour for three-codepoint letters. See the [associated issue](https://github.com/algo26-matthias/idna-convert/issues/45)
for details.

There's a wide range of third party users of this library, who cannot enforce either of the extensions. So we will have to resort to recommend installation.


## 4.0.0

**The minimum PHP version is now 8.1.**

**BC break:**
We changed the behaviour of the encoding step a bit to be more in line with the actual RFCs. This means that the NAMEPREP step is now performed on ANY label
being passed to the `ToPunycode::convert()` method. This means, that case mangling (transforming UPPERCASE to lowercase) will always happen, even for ASCII only labels.

**BC break:**
Also, we added the flag `useStd3AsciiRules` (default: false) to `ToPunycode::__construct()` (and to `ToIdn::__construct()` as well) in order to allow control over this specific behaviour.
Enabling this flag will lead to a stricter rule set of characters being allowed (only the range [-a-zA-Z0-9]) and enforcing the absence of leading and trailing
hyphens in labels. In case of violating these rules a new `Std3AsciiRulesViolationException` will be thrown.

**BC break:**
When stating the IDNA version (2003 or 2008) one must always use an integer. From now on strict type checking is in place.

**BC break:**
In older version labels containing characters prohibited according to NAMEPREP were silently ignored. Now we are throwing an `InvalidCharacterException`.


## 3.2.0

The extra dependency to ext/intl, accidentally introduced with v 3.1 is no longer required.


## 3.1.0

We changed the behaviour of the Punycode algorithm to now include all basic ASCII characters in the output when using `ToPunycode->convert()`.
This change is expected to have no negative effect, but work more closely to the respective RFCs. The old behaviour even led to some endless loops for a few Unicode characters.


## 3.0.0

The library has been broken down into various specific classes, thus more closely following SOLID principles.

As such the single class `IdnaConvert` has been broken down into `ToIdn` and `ToUnicode` respectively. Their naming reflects 
the format of the outcome, so it's easier picking the right conversion direction.
Usually you will only need one conversion direction per script run, so why bother loading and parsing all the other unused code, then?  

Also, the handling of host names (simple labels like `my-hostname` or FQHNs like `some-host.my-domain.example`) is now separated from
that of email addresses and URLs. 
Both classes offer the same set of public methods:

| Method                  |                                     |
|-------------------------|-------------------------------------|
| `convert()`             |  To convert host names              | 
| `convertEmailAddress()` |  To convert email addresses         | 
| `convertUrl()`          |  To convert the host name of an URL | 

There's no "strict mode" anymore, this is achieved by the separate methods above. The IDN version is selected when instantiating the class, no more setting during runtime.
Also, the encoding (for the Unicode side of things) is now **always UTF-8**. Use `TransCodeUnicode` or `EncodingHelper` for converting to and from various encodings to UTF-8.

All actual subclasses like that for NamePrep and the actual Punycode transformation are put in their own namespaces under `Algo26\IdnaConvert`, e.g. `Algo26\IdnaConvert\NamePrep`. 
Interfaces and Exceptions also have their own namespace to declutter the class structure even more.

The class `EncodingHelper` is now called separated into the two classes `ToUtf8` and `FromUtf8` respectively and lies under the namespace `Algo26\idnaConvert\EncodingHelper`.
The class `UnicodeTranscoder` is now called `TransCodeUnicode` under the namespace `Algo26\idnaConvert\TransCodeUnicode`.

All examples are updated to reflect the new usage. See the ReadMe for more details.

Finally, the **minimum PHP version is now 7.2**.


## 2.0.0
The library has been handed over to actively maintained GitHub and Packagist accounts. This led to a change in the namespace.
Replace all occurrences of 
`Mso\IdnaConvert` or `PhlyLabs\IdnaConvert` to `Algo26\IdnaConvert`.
There's no further changes to the class signatures. 


## 1.0.0
**BC break:**
As of version 1.0.0 the class closely follows the PSRs PSR-1, PSR-2 and PSR-4 of the PHP-FIG. 
As such the classes' naming has been changed, a namespace has been introduced and the default IDN version has changed from 2003 to 2008 and minimum PHP engine version raised to 5.6.0.


## 0.8.0
As of version 0.8.0 the class fully supports IDNA 2008. 
Thus, the aforementioned parameter is deprecated and replaced by a parameter to switch between the standards. See the updated example 5 in the ReadMe.


## 0.6.4
**BC break:** 
As of version 0.6.4 the class per default allows the German ligature ß to be encoded as the DeNIC, the registry for .DE allows domains containing ß.  


## 0.6.0
**ATTENTION:** As of version 0.6.0 this class is written in the OOP style of PHP 5. 
Since PHP 4 is no longer actively maintained, you should switch to PHP 5 as quickly as possible.
We expect no compatibility issues with the upcoming ~~PHP 6~~ PHP 7 as well.
