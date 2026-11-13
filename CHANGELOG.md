# Changelog

All notable changes to the phpwcms v1.9-dev branch are documented in this file.
This branch is the legacy line for PHP 7.4 and older installations; new features land on v1.10-dev.

## [Unreleased]

### Security
- **Inline Download of Uploaded Files:** Inline rendering is now limited by an extension allowlist (raster images, PDF, plain text/CSV, audio and video); every other extension is delivered as attachment. The stored MIME type, which is client controlled at upload through `X-Mime-Type` and `$_FILES`, no longer decides the disposition: `dl_file_resume()` re-detects the type with `fileinfo` and falls back to attachment when the detected type can execute scripts. `download.php?file=` ignores the `?type=` parameter for inline responses, sends `Content-Disposition` on the iOS range download path, and both delivery paths send `X-Content-Type-Options: nosniff`. Closes GHSA-rmf4-vf3j-hq8g.

