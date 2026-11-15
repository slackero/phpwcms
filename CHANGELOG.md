# Changelog

All notable changes to the phpwcms v1.9-dev branch are documented in this file.
This branch is the legacy line for PHP 7.4 and older installations; new features land on v1.10-dev.

## [Unreleased]

### Security
- **Host Header Poisoning of Mailed Links:** `get_url_origin()` believes `X-Forwarded-Host` only when the request comes from a `trusted_proxies` entry, validates the resulting host (RFC 3986 reg-name, label and port limits) and honours the new `$phpwcms['trusted_hosts']` allowlist. Every mailer that embeds an absolute link — password reminder, form double opt-in, newsletter verification, guestbook notification, e-card, profile notifications, feed import status and the backend newsletter send — now uses `phpwcms_mail_site_url()` and skips the mail when the site URL was derived from an unverified request host. The setup wizard now stores the detected URL in `site` when the field is left empty, so a fresh install has a canonical host; a URL entered by the operator must be an absolute `http(s)` URL without credentials, query or fragment and is stored with a normalised trailing slash. Closes GHSA-x5fx-f274-39p8.
- **Local File Disclosure via Form Attachments:** only a field declared as `upload` may carry `folder`, `name` and `attachment` metadata. The resolved file must exist and pass `realpath()` containment inside `PHPWCMS_ROOT`. Array POST values keep their values but never their keys, and the same gate protects the error-path file deletion. Closes GHSA-9mvh-hxj5-9fgg.
- **Inline Download of Uploaded Files:** Inline rendering is now limited by an extension allowlist (raster images, PDF, plain text/CSV, audio and video); every other extension is delivered as attachment. The stored MIME type, which is client controlled at upload through `X-Mime-Type` and `$_FILES`, no longer decides the disposition: `dl_file_resume()` re-detects the type with `fileinfo` and falls back to attachment when the detected type can execute scripts. `download.php?file=` ignores the `?type=` parameter for inline responses, sends `Content-Disposition` on the iOS range download path, and both delivery paths send `X-Content-Type-Options: nosniff`. Closes GHSA-rmf4-vf3j-hq8g.

