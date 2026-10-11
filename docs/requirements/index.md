---
title: System requirements
description: Server, PHP, database and extension requirements for running phpwcms 2.x.
sidebar_position: 1
---

# System requirements

> Source: `README.md` (Server & System Requirements), `composer.json`, `setup/setup.php`

## Server

| Requirement | Minimum | Recommended |
| ----------- | ------- | ----------- |
| PHP | 8.2 | 8.2 / 8.3 (tested on 8.2, 8.3, 8.4) |
| MySQL / MariaDB | 5.5 | MariaDB 10.4+ / MySQL 8.0+, InnoDB, utf8mb4 |
| Web server | Apache with `mod_rewrite`, Nginx or IIS | Apache / Nginx |

Server config templates ship in the repo root: `_.htaccess` (Apache), `nginx.conf`, `web.config` (IIS).

## PHP extensions

Required for all features:

- `intl` — collation, internationalization
- `gd` — image scaling, cropping, thumbnails
- `iconv`, `mbstring` — multi-byte encoding handling
- `mysqli` — database driver
- `fileinfo` — MIME validation on upload
- `xmlreader`, `libxml`, `dom`, `simplexml` — feeds, sitemaps, XML processing
- `openssl` — secure SMTP, crypto, API transport
- `curl`, `bcmath` — external HTTP APIs, payment calculations

Image tooling beyond GD: ImageMagick and GhostScript extend supported input formats (EPS/AI/PS/PDF conversion, sharpening, colorspace control) when configured; see [Image processing](../administration/image-processing.md).

## Composer dependencies

Releases ship `include/vendor/` prebuilt. Installing from Git requires `composer install` (PHP 8.2). Key libraries: PHPMailer 7+, TinyMCE 8, htmlpurifier, phpspreadsheet, simplepie, league/commonmark, oauth2 providers (Google, Azure).

## Client

Backend UI is Bootstrap 5 based and targets current evergreen browsers; no legacy IE support. Frontend output is what your templates build — phpwcms imposes no browser baseline beyond your own CSS.

## Docker

A complete local stack (PHP 8.2, MariaDB 10.11, phpMyAdmin, Mailpit, image tools) ships as `docker-compose.yml` / `Dockerfile` — see [Docker installation](../installation/docker.md).

## Related

- [Fresh installation](../installation/fresh-install.md)
- [Setup wizard](../installation/setup-wizard.md)