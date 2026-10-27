---
title: Setup wizard
description: The browser based installation wizard flow, checks, written configuration and re-run behavior.
sidebar_position: 2
---

# Setup wizard

> Source: `setup/index.php`, `setup/setup.php`, `setup/inc/setup.func.inc.php`, `setup/inc/setup.check.inc.php`, `setup/inc/step0.inc.php`–`step6.inc.php`, `setup/get_conf_file.php`

The wizard installs phpwcms through a browser. Start it at `https://your-domain.com/setup/`. It refuses to run when `include/config/conf.inc.php` already exists — an existing installation blocks setup (the start page explains removal to re-run), and `setup/setup.php` redirects to the start page in that case.

## Steps

| Page | Title | Content |
| ---- | ----- | ------- |
| `setup/index.php` | Licence | GNU GPL-2.0 text in a scrollable box; the *I Agree* checkbox unlocks only after scrolling to the end. |
| `setup.php?step=0` | System requirements & environment check | Web server, PHP version (8.4+ required), MySQLi extension and client driver, sodium availability. Detects image tools (Imagick, ImageMagick CLI, GraphicsMagick CLI, GD, Ghostscript, NetPBM) with supported formats, plus recommended extensions (curl, mbstring, intl, openssl, zip, fileinfo) and PHP directives (memory limit, post/upload sizes). Blocks on an unwritable `setup/setup.conf.inc.php` (offers `chmod 666`); warns when recommendations are unmet. |
| `step=1` | Edition & whitelabel license | Standard GPL edition (leave the key empty) or Whitelabel edition: paste a signed license key, validated live via libsodium with licensee, domain and expiry shown. |
| `step=2` | Database server connection | Host, port (auto-detected), user, password, database name (created automatically when missing, utf8mb4), table prefix, persistent connection; with a whitelabel key a *Brand Table Prefix* field appears. After connecting: MySQL version, default/allowed language selection with browser-language detection, collation choice (utf8mb4 variants), *Create initial phpwcms database tables* checkbox, then the superuser form (name, username, password + repeat, email) writing the `usr_admin` account. |
| `step=3` | Site URL & email transport | Detected site basis URL, timezone select, sender email/name, mail transport (`mail()`, SMTP, unix sendmail), host/port, encryption (none/STARTTLS/SMTPS), SMTP authentication and credentials; whitelabel licenses additionally expose brand name, website URL and copyright. |
| `step=4` | Internal paths & storage | Document root, subdirectory (`root`), filestorage, template and FTP upload directory names, optional CDN image URL. Values must not start or end with a slash. |
| `step=5` | Content & media defaults | Max upload size (bytes), content column width, thumbnail and preview sizes, session timeout, graphics engine select (priority Imagick > ImageMagick > GraphicsMagick > GD2 > NetPBM with installed/badges), CLI library path, JPEG/WebP quality, sharpen level. |
| `step=6` | Finalization & permissions check | Writability check (auto-`chmod 0777` on failure) for the directories listed below, writes the configuration and `.htaccess`, displays the remove-`setup`-warning and a link to the login page. |

## What the wizard writes

| Target | Content |
| ------ | ------- |
| `setup/setup.conf.inc.php` | The wizard's working state — initialized by copying `include/config/dist.conf.inc.php`, then rewritten from the `write_conf_file()` template with every form value after each step. Keys not on any form keep the shipped defaults. |
| `include/config/conf.inc.php` | Copy of the state file; written in step 6 with write → copy → rename fallbacks. Only written when absent. Falls back to the `setup/get_conf_file.php` manual download when all writes fail. |
| `.htaccess` (document root) | Created from `_.htaccess` when missing; inside a subdirectory the wizard rewrites `RewriteBase /` to the subfolder path. Existing `.htaccess` files are left untouched. |
| `setup/.htaccess` | Written with `Deny from all` as a baseline block of the setup directory. |
| Database tables | From `setup/default_sql/phpwcms_init.sql` and `phpwcms_inserts.sql`, executed with `CREATE TABLE IF NOT EXISTS` / `INSERT IGNORE` guards on submit; existing tables with a different prefix are reported so you can change the prefix. |
| Admin account | Inserted into or updated in `phpwcms_user` with `usr_admin = 1`, hashed password, backend language, TinyMCE default. The *Edit Account* link reopens the form. |

The generated configuration enables the backend module and newsletter menus by default (`enable_backend_module`, `enable_backend_newsletter` = `true`) and generates a random `form_salt`. Modules install separately on first use in the backend, not from the wizard.

## Re-run behavior

- With `conf.inc.php` present, the start page blocks and setup redirects — delete `include/config/conf.inc.php` to start over (this does not drop database tables; the existing-prefix detection then reports and reuses them).
- The state lives in `setup/setup.conf.inc.php`, not in the session: you can leave mid-way and resume, and the file carries previous settings forward.
- After the configuration download through `setup/get_conf_file.php` the appended `$NO_ACCESS = true;` locks the wizard; remove the line to re-enable.

## Related

- [Fresh installation](fresh-install.md) — permissions and hardening around the wizard
- [Configuration file](config-file.md) — every key the generated file contains
- [Whitelabel branding](../administration/whitelabel.md) — license mechanics