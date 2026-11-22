---
title: Docker installation
description: The bundled docker-compose development stack with Apache, PHP-FPM, nginx, MariaDB, phpMyAdmin and Mailpit.
sidebar_position: 3
---

# Docker installation

> Source: `docker-compose.yml`, `Dockerfile`, `Dockerfile.fpm`, `nginx.conf`

The repository ships a complete local development stack: PHP 8.4 with Apache, PHP-FPM and nginx, MariaDB, phpMyAdmin and Mailpit, including the full graphics toolchain. It is a development environment — do not expose it to production as-is.

## Starting

```bash
docker compose up -d
```

Then open the setup wizard at `http://localhost:8080/setup/` to install (see [Setup wizard](setup-wizard.md)). If `include/config/conf.inc.php` exists from a previous run, setup blocks and the site serves directly at `http://localhost:8080`.

## Services

| Service | Image | Ports (host:container) | Purpose |
| ------- | ----- | ---------------------- | ------- |
| `web` | built from local `Dockerfile` (`php:8.4-apache`) | `8080:80` | phpwcms with Apache; repository bind-mounted at `/var/www/html`. |
| `php` | built from local `Dockerfile.fpm` (`php:8.4-fpm`) | — | PHP-FPM worker for the nginx service; same extensions as the Apache image. |
| `nginx` | `nginx:1.27-alpine` | `8082:80` | nginx with the repository `nginx.conf` included before the PHP location; use it to verify the default-deny rules. |
| `db` | `mariadb:10.11` | `3307:3306` | Database with `--character-set-server=utf8mb4 --collation-server=utf8mb4_unicode_ci`; data in the named volume `db_data`. |
| `mailpit` | `axllent/mailpit:latest` | `8026:8025` (web), `1026:1025` (SMTP) | Captures all outgoing mail; limited to `MP_MAX_MESSAGES = 500`. |
| `phpmyadmin` | `phpmyadmin:latest` | `8081:80` | `PMA_HOST=db`, `PMA_PORT=3306`. |

## Environment values

| Variable | Value |
| -------- | ----- |
| `MYSQL_ROOT_PASSWORD` | `rootpassword` |
| `MYSQL_DATABASE` | `phpwcms` |
| `MYSQL_USER` | `phpwcms` |
| `MYSQL_PASSWORD` | `phpwcmspassword` |

Enter these in the setup wizard's database step: host `db`, port `3306`, database `phpwcms`, user `phpwcms`, password `phpwcmspassword`. The named volumes `db_data` and `phpwcms_storage` persist; the web directory is your working copy. `phpwcms_storage` is mounted at `/var/www/phpwcms-storage` in `web`, `php` and `nginx` (read-only for nginx), matching the default `../phpwcms-storage` relative to `/var/www/html`; the `php` service chowns it to `www-data` on start.

## PHP image details

The Dockerfile builds the web container with:

- PHP extensions: `gd` (freetype/jpeg/webp), `mysqli`, `pdo_mysql`, `intl`, `zip`, `mbstring`, `opcache`, plus `imagick` from PECL. `Dockerfile.fpm` installs the same set for the nginx/FPM pair.
- Image tools: ImageMagick, GraphicsMagick, NetPBM, Ghostscript — with the ImageMagick `policy.xml` entries for PDF, EPS, PS and XPS granted `read|write` rights so document preview rendering works; see [Image processing](../administration/image-processing.md).
- PHP settings via a custom INI: `memory_limit = 256M`, `upload_max_filesize = 64M`, `post_max_size = 64M`, `max_execution_time = 300`, UTC timezone, and `display_errors` on for development.
- Apache modules `rewrite` and `headers` enabled, `AllowOverride All` for `.htaccess` support.
- Mail transport wired through msmtp: `sendmail_path = "/usr/bin/msmtp -t --host=mailpit --port=1025"`, so every `mail()` call from the CMS (SMTP_MAILER `mail`) lands in Mailpit.

## Web server path tests

`tests/web/path-protection.sh` starts the stack and probes both servers (Apache `8080`, nginx `8082`): private paths must answer 403/404, public assets 200, `/` must not be 404, and `formfile.php?t=invalid` must reach PHP (`File not found.`). Teardown uses `docker compose down` without `-v`, so the volumes survive. See the script and its README for `APACHE_URL`/`NGINX_URL` overrides.

## Mail testing with Mailpit

Mailpit exposes a web UI at `http://localhost:8026` where every caught message is readable, searchable and holdable, and an SMTP endpoint at `mailpit:1025` (host port 1026) that can also be used as an explicit SMTP target in the wizard or SMTP config. Delete all messages in the UI when a test run produced junk. Because the sendmail path already routes to Mailpit, newsletter and form-mail tests work without configuring real SMTP.

## Related

- [Fresh installation](fresh-install.md) — manual alternative to the wizard steps
- [System requirements](../requirements/index.md) — host-based requirement check