# Changelog

All notable changes to the phpwcms v1.9-dev branch are documented in this file.
This branch is the legacy line for PHP 7.4 and older installations; new features land on v1.10-dev.

## [Unreleased]

### Security
- **Inline JavaScript escaping in the backend:** article and content-part titles reached the article structure list through `js_singlequote()`, which only HTML-encodes quotes. The HTML parser decodes `&#39;` before the inline handler is compiled, so a backend user below administrator could store `X'+((document.title='…'),'')+'` as an article title, content-part title, subtitle or comment and run script in an administrator's session when the list was opened, a row was hovered, or a delete action was confirmed. All inline handler values now use `js_inline_string()`, which escapes at JavaScript level first and then HTML-encodes, `Tip()` tooltip values use `js_tooltip_string()` so markup from a title, comment or file name is shown as text instead of being parsed by the tooltip's `innerHTML`; a structure name is escaped when it is confirmed for deletion instead of being passed through pre-encoded; and `js_string()` covers `<script>` blocks, where it also neutralises `</script>`, U+2028 and U+2029. The private file confirms, the file category, newsletter, subscriber, template, page layout, module (ads, calendar, feed import, glossary, shop) and front-end link handlers were converted in the same pass, and `js_singlequote()` is deprecated. A regression test in `tests/core/test-tooltip-escaping.php` now fails when a new output path uses it.
