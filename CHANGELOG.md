# Changelog

All notable changes to the phpwcms v1.9-dev branch are documented in this file.
This branch is the legacy line for PHP 7.4 and older installations; new features land on v1.10-dev.

## [Unreleased]

### Security
- **Form Upload Extension Check:** the public Form upload validated `which_ext()` of the raw multipart name but stored `sanitize_filename()` of it, so a name such as `marker.php.` had no extension for the deny list, and the cleaning removed the trailing dot, storing it as `<time>_marker.php` under `content/form/`. The PHP opening tag scan read only the first 8192 bytes, and the folder's `.htaccess` is inert when the web server runs with `AllowOverride None` (the stock Debian Apache default). The extension check and both accept/exclude regexps now run against the stored file name, so the trailing-dot name is denied as `.php`. The deny list also covers `php2`, `php6`, `php9`, `shtml` and `shtm`, the PHP opening tag scan reads the whole file in chunks instead of the first 8192 bytes, and the stored name carries a random component so it is no longer derivable from the response time. Revision `r563` quarantines existing uploads with an executable extension: it moves them, renamed with a `.quarantine` suffix, from `content/form` into `content/tmp/.form-upload-quarantine/<timestamp>/` and reports the list, so an administrator can review and restore them instead of losing them to a silent delete.
