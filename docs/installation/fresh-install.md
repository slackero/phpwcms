---
title: Fresh installation
description: Download, directory permissions, setup wizard run and hardening after installation.
sidebar_position: 1
---

# Fresh installation

> Source: `README.md` (Installation), `setup/index.php`, `setup/inc/step6.inc.php`, `setup/inc/setup.func.inc.php`

Confirm the [system requirements](../requirements/index.md) first — PHP 8.4+, a MySQL 5.5+/MariaDB database, and at least one graphics library. For a prebuilt local stack, see [Docker installation](docker.md).

## Install steps

1. **Download and extract** a phpwcms release archive (releases ship `include/vendor/` prebuilt, so no Composer step is needed) into the web server document root. Installing from Git instead requires `composer install --no-dev --optimize-autoloader` under PHP 8.4.
2. **Configure the web server**: use the shipped templates — rename `_.htaccess` to `.htaccess` for Apache (the setup wizard offers to do this), adapt `nginx.conf` or `web.config`. Rewritten URLs need `mod_rewrite`/matching server rules; see [SEO settings](../administration/seo-settings.md).
3. **Set write permissions** for the web server user on:
   - `content/` and its subfolders (`content/images/`, `content/tmp/`, `content/cache/`, `content/form/`, `content/rss/`, `content/pages/`)
   - `filearchive/` and `upload/`
   - `include/config/`
   - `template/` (and `template/template_lang/`)
   - `setup/setup.conf.inc.php` (the wizard's working config, chmod 666 if needed)
4. **Run the setup wizard**: open `https://your-domain.com/setup/` in the browser — see [Setup wizard](setup-wizard.md) for the seven steps including database creation, admin account and SMTP settings.
5. **Finalize and harden** as below.

Ownership should stay with the user the web server runs as (or a shared group) so the wizard can write `include/config/conf.inc.php`, `.htaccess` and the content directories.

## Post-install hardening

The wizard's final step displays the required warning itself: **delete or secure the `setup` directory before using phpwcms in production.**

- Remove the whole `/setup/` directory, or block access to it at the web server level. Re-adding it later only for upgrades is the documented workflow — `setup/upgrade.php` is downloaded separately per [Updating](updating.md).
- If automated writing of `include/config/conf.inc.php` failed during setup, the wizard links `setup/get_conf_file.php`, which downloads `conf.inc.php` as an attachment; place it into `include/config/conf.inc.php`. Downloading the file once appends `$NO_ACCESS = true;` to `setup/setup.conf.inc.php`, which blocks further wizard access until the line is removed.
- Verify `include/config/conf.inc.php` is not served as plain text — the `_.htaccess` and shipped server configs block `include/` and dot-prefixed files.
- Check `content/cache/`, `content/backup/` (created later by the updater with a deny-all `.htaccess`) and `filearchive/` listing behavior; deny direct directory listing for storage folders.
- Log into `login.php` with the admin account created in the wizard and change defaults as needed: [Users and usergroups](../administration/users-usergroups.md), [Cache and performance](../administration/cache-performance.md).
- Keep `template/` and `content/` under your own backup routine; the built-in [update engine](updating.md#in-app-system-update) backs up only on updates.

## Verify the install

- Frontend loads a default page on `https://your-domain.com/`; backend at `https://your-domain.com/login.php`.
- Admin → phpinfo shows the expected PHP version and extensions ([Admin panel](../administration/admin-panel.md)).
- Uploading an image to the file center generates a thumbnail — confirms the [image engine](../administration/image-processing.md) works.

## Related

- [Setup wizard](setup-wizard.md)
- [Docker installation](docker.md)
- [Configuration file](config-file.md)