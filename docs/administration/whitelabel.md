---
title: Whitelabel branding
description: Signed license keys and configuration keys to rebrand the backend as an own product.
sidebar_position: 7
---

# Whitelabel branding

> Source: `include/inc_lib/whitelabel.inc.php`, `bin/WHITELABEL.md`, `setup/inc/setup.func.inc.php` (`setup_validate_whitelabel_key()`), `include/config/dist.conf.inc.php`

Whitelabel mode replaces the phpwcms identity in the backend with your own brand. It requires a valid signature — the feature is config-driven but cryptographically secured against arbitrary rebranding:

- The key is a single string: `<base64url(payload JSON)>.<base64url(Ed25519 signature)>`.
- Verification uses libsodium (`sodium_crypto_sign_verify_detached`) against the embedded master public key in `whitelabel.inc.php`. Without the `sodium` extension, whitelabel never activates.
- The payload carries licensee, domain pattern, brand name, table prefix and optional expiry (`valid_until` as Unix timestamp); an expired key deactivates whitelabel.
- The domain pattern matches the current `HTTP_HOST`: `*` for any host, exact domain, or `*.domain.com` wildcard.

The setup wizard validates a pasted key live and shows licensee, domain and expiry (see [Setup wizard](../installation/setup-wizard.md)); a CLI checker/inspector exists in the maintainer toolchain (`bin/whitelabel-license.php --verify="<key>"`).

## Configuration keys

> Source: `include/config/dist.conf.inc.php`, `include/inc_lib/whitelabel.inc.php`

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `whitelabel_key` | string | `''` | Signed license key; empty or invalid means standard phpwcms branding. |
| `brand_name` | string | `''` | Product name replacing "phpwcms" across backend UI, page titles, labels and messages. Falls back to the license payload's `brand_name` when empty. |
| `brand_logo_light` | string | `''` | Logo URL or path for the light theme (backend header, login page). |
| `brand_logo_dark` | string | `''` | Logo for dark theme; falls back to `brand_logo_light` when empty. |
| `brand_logo_email` | string | `''` | Email logo as URL or `data:image/...;base64` data URI (raster PNG/JPG/WebP/GIF only). Falls back to `brand_logo_light` when it is a raster image. |
| `brand_custom_css` | string | `''` | Backend stylesheet URL or path, injected as a `<link>` for UI theming. |
| `brand_support_url` | string | `''` | Custom support/documentation URL. |
| `brand_url` | string | `''` | Product website URL used in the footer; falls back to `brand_support_url`. |
| `brand_copyright` | string | `''` | Footer copyright text, prefixed with `Copyright © <year>` unless it already contains a copyright notice; falls back to the license payload's copyright, then licensee. |
| `brand_table_prefix` | string | `''` | Non-`phpwcms` database table prefix in whitelabel mode (see below). |

Configuration values always win over license payload values when both are set.

## What whitelabel mode changes

When `phpwcms_whitelabel_init()` activates (`PHPWCMS_WHITELABEL` defined, `is_whitelabel()` true):

- Language strings and UI text containing `phpwcms`, `phpWCMS` or `PHPWCMS` are rewritten to the brand name (`apply_brand_replacements()`), including release notes shown in the update panel.
- The backend footer, login page footer and the About dialog show brand name, logo, URL and copyright instead of the phpwcms attribution and GitHub link.
- `<meta name="generator">` and the `X-phpwcms-Release` and `X-phpwcms-Page-Processed-In` HTTP headers are disabled regardless of their own config keys.

Without an active license the standard branding (footer linking github.com/slackero/phpwcms, default logo and copyright) renders.

## Database table prefix

The effective table prefix is assembled in `include/inc_lib/dbcon.inc.php`:

```
DB_PREPEND = [<db_prepend>_]<brand_table_prefix>_
```

`brand_table_prefix` defaults to `phpwcms`, so standard installs keep `phpwcms_` table names. In whitelabel mode the prefix becomes `<brand_prefix>_` (e.g. `cmsgo_phpwcms_article`) — set it before the first install since it decides every table name; changing it later would leave the schema orphaned. The setup wizard exposes a *Brand Table Prefix* field on the database step when a valid license key is present (`[a-zA-Z0-9_]` only), and the standalone updater can repair it in existing installs ([Updating](../installation/updating.md)). `db_prepend` still works as an optional additional namespace in front of it.

## Example configuration

```php
$phpwcms['whitelabel_key']    = 'eyJsaWNlbnNlZSI6...';
$phpwcms['brand_name']        = 'Brand';
$phpwcms['brand_logo_light']  = 'img/brand-logo.svg';
$phpwcms['brand_logo_dark']   = 'img/brand-logo-dark.svg';
$phpwcms['brand_custom_css']  = 'template/inc_css/brand.css';
$phpwcms['brand_support_url'] = 'https://www.example.com/support';
$phpwcms['brand_url']         = 'https://www.example.com';
$phpwcms['brand_copyright']   = 'Example Dev Team';
$phpwcms['brand_table_prefix']= 'example';
```

## Related

- [Setup wizard](../installation/setup-wizard.md) — license entry during installation
- [Configuration file](../installation/config-file.md) — full key reference
- [Admin panel](admin-panel.md) — branding in the update panel