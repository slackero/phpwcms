---
title: Configuration file
description: Every include/config/conf.inc.php key with type, default and effect.
sidebar_position: 5
---

# Configuration file

> Source: `include/config/dist.conf.inc.php` (all defaults), `include/inc_lib/default.inc.php`, cross-linked feature pages

`include/config/conf.inc.php` is the single runtime configuration of a phpwcms install, created by the [setup wizard](setup-wizard.md) and never touched by updates. All entries live in the `$phpwcms` array as `$phpwcms['key'] = value;` statements. Defaults below quote the shipped `include/config/dist.conf.inc.php` — never copy values from another installation's file. Edit by hand, then clear affected [caches](../administration/cache-performance.md) where noted.

The setup-generated file adds one legacy key that `dist.conf.inc.php` no longer ships: `GETparameterName` (`'id'`) — it is read nowhere in 2.x code.

## Database

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `db_host` | string | `'localhost'` | Database server host or socket path. |
| `db_port` | int | `3306` | Database server port. |
| `db_user` | string | `''` | Database user name. |
| `db_pass` | string | `''` | Database password; also seeds `USER_KEY` and the `form_salt` fallback. |
| `db_table` | string | `''` | Database name. |
| `db_prepend` | string | `''` | Optional namespace in front of the table prefix; final prefix is `[<db_prepend>_]<brand_table_prefix>_` (see [Whitelabel branding](../administration/whitelabel.md)). |
| `db_pers` | bool | `0` | Use a persistent (non-closing) mysqli connection. |
| `db_charset` | string | `'utf8mb4'` | Default charset for new tables and connections. |
| `db_collation` | string | `'utf8mb4_unicode_ci'` | Default collation for new tables. |
| `db_version` | string | `''` | Recorded MySQL server version from install time; filled automatically. |
| `db_timezone` | string | `''` | MySQL session time zone name or offset; empty keeps the server default. |
| `db_sql_mode` | string | `'NO_ENGINE_SUBSTITUTION'` | MySQL `sql_mode` set per session. |
| `db_errorlog` | bool | `false` | Log database queries and errors; see [Cache and performance](../administration/cache-performance.md). |

## Site URL and SSL

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `site` | string | `''` | Base URL of the site; empty auto-detects. Example value: `'http://'.$_SERVER['SERVER_NAME'].'/'`. |
| `site_ssl_mode` | bool | `0` | Turns SSL support of the CMS on (`1`) or off (`0`). |
| `site_ssl_url` | string | `''` | URL of the SSL certificate host, e.g. `'https://'.$_SERVER['SERVER_NAME'].'/'`. |
| `site_ssl_port` | int | `443` | Port the SSL service listens on. |

## Administrator account

Defaults written into a fresh install by the setup wizard; the wizard stores a `password_hash()` of the chosen password.

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `admin_name` | string | `'Webmaster'` | Display name of the install's administrator. |
| `admin_user` | string | `'admin'` | Login name of the super admin account. |
| `admin_pass` | string | `''` | Password hash; must be set — the setup wizard requires a password on install. |
| `admin_email` | string | `'noreply@example.com'` | Administrator email used for system communication. |

## Paths and directories

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `DOC_ROOT` | string | `$_SERVER['DOCUMENT_ROOT']` | Absolute document root; override for CLI or nonstandard layouts. |
| `root` | string | `''` | Subdirectory below the document root when phpwcms does not run at domain root; no leading/trailing slash. |
| `file_path` | string | `'filearchive'` | Filestorage directory name; see [File storage](../administration/file-storage.md). |
| `templates` | string | `'template'` | Template directory. |
| `content_path` | string | `'content'` | Frontend runtime directory for cache, images, tmp, rss, pages. |
| `cimage_path` | string | `'images'` | Image directory name below `content_path`. |
| `cdn_image_url` | string | `''` | Base URL of a CDN proxying `/content/images/` (e.g. `https://cdn.example.com`); the CDN must forward to the origin folder. |
| `ftp_path` | string | `'upload'` | FTP takeover directory; see [File storage](../administration/file-storage.md). |
| `ads_path` | string | `'marketing'` | Ads module storage name below `content_path` (the former `ads`). |

## Content and media limits

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `file_maxsize` | int | `52428800` | Per-file upload cap in bytes (50 MB); see [File storage](../administration/file-storage.md). |
| `content_width` | int | `978` | Max article content column width, important for multi-column image rendering. |
| `img_list_width` / `img_list_height` | int | `160` / `100` | List thumbnail size; see [Image processing](../administration/image-processing.md). |
| `img_prev_width` / `img_prev_height` | int | `978` / `734` | Large preview size and fallback for one-sided target dimensions. |
| `max_time` | int | `1800` | Backend logout after this many seconds of inactivity. |
| `responsive` | bool | `1` | `1` keeps image sizes as given, `0` caps width at `content_width`. |
| `preserve_image_name` | bool | `1` | Keep the original file name for resized image versions. |
| `cnt_sort` | string | `'a-z'` | Content sorting of listings: empty/`false` behaves like before, `'a-z'` or `'z-a'`. |

## URL rewriting and aliases

See [SEO settings](../administration/seo-settings.md) for rendering details.

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `rewrite_url` | bool | `1` | Rewritten URLs via the web server rules. |
| `rewrite_ext` | string | `'.html'` | Suffix of rewritten URLs (`/alias.html`, `/alias/`). |
| `alias_allow_slash` | bool | `1` | Allow `/` in aliases. |
| `alias_allow_utf8` | bool | `1` | Keep UTF-8 special characters in aliases (charset utf-8). |
| `allow_empty_alias` | bool | `false` | `true` disables alias auto-creation when the field is empty. |
| `reserved_alias` | array | `[]` | Alias names that must stay free. |
| `force301_id2alias` | bool | `false` | 301 redirect from ID URL to alias URL. |
| `force301_2struct` | bool | `false` | 301 redirect to the structure level for single-article levels. |
| `GET_pageinfo` | bool | `0` | Append breadcrumb path as `pageinfo` GET parameter to links. |
| `remove_empty_get_vars` | bool | `true` | Drop valueless GET parameters except the alias. |
| `unregister_getVar` | array | `[]` | Custom GET var names kept out of `rel_url()`/`abs_url()` globals. |
| `preserve_getVar` | array | `[]` | GET vars phpwcms normally strips but you want preserved. |
| `log_404error` | bool | `false` | Log every 404 for the redirect editor. |
| `disable_next_prev` | bool | `false` | Drop `<link rel="prev"/"next">` from pagination. |

## Language and charset

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `default_lang` | string | `'de'` | Default language code. |
| `DOCTYPE_LANG` | string | `''` | Language written into the doctype/`<html lang>`; defaults to `default_lang`. |
| `allowed_lang` | array | `array('en', 'de', 'fr', 'es')` | Languages the frontend may switch between. |
| `frontend_lang_key` | string | `'phpwcms_frontend_lang'` | Session/cookie key for frontend language choice. |
| `lang_parse` | bool | `true` | Parse frontend language block tags `[LANG:xx]…[/LANG]` / `[xx]…[/xx]`. |
| `use_content_lang` | bool | `false` | Use the article/structure-level language when resolving content. |
| `be_lang_parse` | mixed | `false` | Backend language block parsing: `false`, `'BBCode'` or `'BraceCode'`. |
| `charset` | string | `'utf-8'` | Page charset; keep utf-8. |
| `php_charset` | bool | `false` | `true` sets PHP's default charset to `charset`. |
| `i18n_parse` | bool | `1` | Parse browser-language `@@Text@@` translation markers. |
| `i18n_complex` | bool | `0` | `0` matches the easy two-char language form, `1` the `en-gb` form. |
| `php_timezone` | string | `''` | Override the PHP date default time zone. |

## Rendering and output

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `mode_XHTML` | int | `3` | Doctype: `1` XHTML 1.0 Transitional, `0` HTML 4.01 Transitional, `2` XHTML 1.0 Strict, `3` HTML5. |
| `header_XML` | bool | `0` | `1` sends `application/xhtml+xml` instead of `text/html`. |
| `IE7-js` | bool | `0` | Legacy IE7-js loader; obsolete, keep `0`. |
| `render_device` | bool | `0` | Enable user-agent specific template blocks `<!--if:mobile-->`, `<!--!if:mobile-->`, `<!--!if:default-->`. |
| `detect_pixelratio` | bool | `0` | Inject JavaScript that detects Retina devices. |
| `render_clean_html` | bool | `0` | Strip HTML comments from output; experimental. |
| `parse_html_mode` | string | `'before'` | When replacement tags parse against raw HTML: `before`, `after`, `before+after`. |
| `js_in_body` | bool | `0` | `1` moves `<script>` output before `</body>` instead of `<head>`. |
| `viewport` | string | `'width=device-width, initial-scale=1'` | Content of the viewport meta tag. |
| `X-UA-Compatible` | string | `''` | Legacy `X-UA-Compatible` meta/edge mode; keep empty. |
| `base_href` | mixed | `true` | Emit `<base href>`: `true` auto-detects, a string sets it, `false` disables. |
| `header_comment` | string | `''` | Custom comment emitted at the top of the HTML source. |
| `cp_default` | int | `0` | Default content part ID preselected where used; see structure level editor. |
| `frontend_edit` | bool | `0` | `1` adds direct-into-backend links to frontend content. |
| `allow_ext_init` | bool | `1` | Allow including custom scripts at frontend initialization. |
| `allow_ext_render` | bool | `1` | Allow including custom scripts at frontend rendering. |
| `allow_cntPHP_rt` | bool | `0` | Allow PHP replacement tags/includes inside content parts. |
| `allow_remote_URL` | bool | `0` | `1` permits remote URLs in `{PHP:...}` tags. |
| `enable_inline_php` | bool | `false` | Enables `[PHP]` blocks and `{PHP…}` tags in content. |
| `enable_deprecated` | bool | `false` | Re-enable functionality phpwcms marked deprecated. |
| `recipient_count` | int | `0` | Legacy, read nowhere in 2.x. |
| `set_article_active` | bool | `1` | Articles are active (`1`) or disabled (`0`) by default when created. |
| `set_category_active` | bool | `1` | Structure levels default to active on create. |
| `set_file_active` | bool | `1` | Files and folders default to active on create. |
| `set_news_active` | bool | `1` | News entries default to active on create. |
| `enable_backend_newsletter` | bool | `true` | Shows the newsletter menu in the backend; access is then governed by usergroups. |
| `enable_backend_module` | bool | `true` | Shows the module menu in the backend; access is then governed by usergroups. |

## Editors and frontend libraries

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `wysiwyg_editor` | int | `2` | `0` none, `1` CKEditor (legacy), `2` TinyMCE 8. |
| `wysiwyg_template` | array | `[]` | Deprecated, unused. |
| `FCK_FileBrowser` | bool | `1` | Use the phpwcms file browser inside FCKeditor instead of its built-in one. |
| `js_lib` | array | `[]` | Extends the default template library settings. |
| `glightbox_options` | array | `[]` | Extra GLightbox options, e.g. `array('selector' => 'a[rel^="lightbox"]', 'loop' => true)`. |
| `video-js` | string | `''` | Video.js host or local path, e.g. `'template/lib/video-js/'` for self-hosting. |
| `lazy_loading` | string | `'lazy'` | `lazy`, `eager` or `auto` loading for images/iframes; see [Cache and performance](../administration/cache-performance.md). |
| `wkhtmltopdf_path` | string | `''` | Full path to wkhtmltopdf used for PDF generation, e.g. `'/usr/bin/wkhtmltopdf'`. |

## Images

See [Image processing](../administration/image-processing.md).

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `image_library` | string | `'GD2'` | Engine: `GD2`, `Imagick`, `ImageMagick`, `GraphicsMagick`/`GM` or `NetPBM`. |
| `library_path` | string | `''` | Binary directory for CLI engines. |
| `jpg_quality` | int | `85` | JPEG quality 25–100. |
| `webp_enable` | bool | `1` | Deliver resized images as WebP when supported. |
| `webp_quality` | int | `85` | WebP quality. |
| `resize_animated_gif` | bool | `true` | Try to resize animated GIFs. |
| `sharpen_level` | int | `1` | Sharpening 0–5, ImageMagick-family only. |
| `im_fix_colorspace` | string | `'RGB'` | Colorspace for ImageMagick output; `'SRGB'` when colors break. |
| `gd_memcheck_off` | bool | `0` | `1` disables the GD memory pre-check. |
| `imgext_disabled` | string | `''` | Comma-separated image types excluded from handling, e.g. `'pdf,ps'`. |
| `cmsimage_settings` | array | `[]` | Allowlist of dynamic image sizes, e.g. `array('500x500x0', 'default' => '1280x800x1')`. |
| `cmsimage_redirect` | bool | `false` | `true` redirects to the generated image file instead of streaming it. |
| `opengraph_imagesize` | string | `'1200x630x0'` | `og:image` size spec `WxH[x1=crop]`; see [SEO settings](../administration/seo-settings.md). |

## Cache and debugging

See [Cache and performance](../administration/cache-performance.md) for behavior details.

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `cache_enabled` | bool | `0` | Legacy page-cache switch; read nowhere in 2.x. |
| `cache_timeout` | int | `0` | Seconds for `Expires`/`Cache-Control: max-age` page headers. |
| `version_check` | bool | `1` | Check the online phpwcms release for update notices. |
| `disable_generator` | bool | `false` | `1` drops the `generator` meta and `X-phpwcms-Release` header. |
| `disable_processed_in` | bool | `false` | `1` drops the `X-phpwcms-Page-Processed-In` header. |
| `browser_check` | array | `array('fe' => false, 'be' => false, 'vs' => '', 'insecure' => true, 'required' => '')` | Browser-update.org notice in frontend/backend with version rules. |

## Sessions and security

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `Login_IPcheck` | bool | `0` | Bind backend sessions to the login IP. |
| `trusted_proxies` | array | `[]` | CIDR ranges of reverse proxies/CDNs whose forwarded headers may be believed, e.g. `array('173.245.48.0/20', '2400:cb00::/32')`. |
| `trusted_proxy_priority_header` | string | `''` | Preferred client-IP header behind a trusted proxy, e.g. `'CF-Connecting-IP'`. |
| `SESSION_FEinit` | bool | `0` | Start PHP sessions for the frontend (`1`) or not (`0`, default). |
| `BOTS` | array | googlebot, msnbot, bingbot, ... | User agents that never start sessions. |
| `session.cookie_httponly.off` | bool | `false` | `true` makes the session cookie JavaScript-accessible. |
| `session.cookie_samesite` | string | `'Lax'` | Cookie SameSite: `None`, `Lax` or `Strict`. |
| `login_autocomplete` | bool | `true` | Allows the browser to store/autofill backend credentials. |
| `enable_GDPR` | bool | `true` | Anonymize IPs and other GDPR handling inside phpwcms logging. |
| `usergroup_support` | bool | `false` | Experimental group feature switch. |

## Files, forms and messaging

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `allowed_upload_ext` | string | long comma list | Upload extension whitelist; see [File storage](../administration/file-storage.md). |
| `multimedia_ext` | string | `'aif,aiff,mov,...'` | Extensions treated as multimedia in the file center. |
| `trash_delete_files` | bool | `false` | Delete stored files physically when trash is emptied. |
| `sanitize_dlname` | bool | `0` | Sanitize special characters in download filenames. |
| `inline_download` | bool | `1` | Open downloads inline where possible. |
| `form_tracking` | bool | `1` | Store each form submission in the database. |
| `form_salt` | string | `''` | Secret salt for form tracking; falls back to `db_pass` when empty, generated by setup. |
| `formmailer_set` | array | `array('allow_send_copy' => 0, 'global_recipient_email' => 'mail@example.com')` | Formmailer switches: copy-to-sender permission and fallback recipient. |
| `enable_messages` | bool | `0` | Internal messaging system; no longer recommended to use. |
| `feuser_regkey` | string | `'FEUSER'` | Key namespace for frontend user registration data. |
| `login.php` | string | `'login.php'` | Name of the backend login file, resolvable in URL helpers. |

## Social and SEO

See [SEO settings](../administration/seo-settings.md).

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `set_sociallink` | array | `array('article' => false, 'articlecat' => false, 'news' => false, 'shop' => false, 'render' => true)` | Defaults of the social/OpenGraph switch per type and `render` as global switch. |
| `twitter_site` | string | `''` | `@handle` for the `twitter:site` meta tag. |
| `canonical_off` | bool | `false` | `true` disables canonical link output. |
| `enable_seolog` | bool | `1` | Log search engine referrer data for the SEO log module. |

## Email (PHPMailer)

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `SMTP_FROM_EMAIL` | string | `'info@localhost'` | Default from/reply address. |
| `SMTP_FROM_NAME` | string | `'My Name'` | Default from/reply name. |
| `SMTP_HOST` | string | `'localhost'` | SMTP server host or IP. |
| `SMTP_PORT` | int | `25` | SMTP server port. |
| `SMTP_MAILER` | string | `'mail'` | Transport: `mail`, `smtp`, `sendmail` or `qmail`. |
| `SMTP_USER` | string | `'user'` | SMTP login name. |
| `SMTP_PASS` | string | `'pass'` | SMTP password. |
| `SMTP_SECURE` | string | `''` | `''`, `'ssl'` or `'tls'`. |
| `SMTP_AUTH` | bool | `0` | Enable SMTP authentication. |
| `SMTP_AUTH_TYPE` | string | `''` | `CRAM-MD5`, `LOGIN`, `PLAIN` or `XOAUTH2`. |
| `SMTP_XOAUTH_PROVIDER` | string | `''` | OAuth2 provider: `'Google'`, `'Microsoft'` or `'Azure'`. |
| `SMTP_CLIENT_ID` | string | `''` | OAuth2 client ID. |
| `SMTP_CLIENT_SECRET` | string | `''` | OAuth2 client secret. |
| `SMTP_TENANT_ID` | string | `''` | Microsoft OAuth2 tenant ID. |
| `SMTP_REFRESH_TOKEN` | string | `''` | OAuth2 refresh token; obtain via `bin/get-oauth-token.php`. |
| `SMTP_DEBUG` | int | `0` | SMTP debug level 0–4. |

## Support block

| Key | Sub-key | Default | Effect |
| --- | ------- | ------- | ------ |
| `support` | `name` | `''` | Custom company/support name shown in the backend dashboard. |
| `support` | `address` | `''` | Custom support address. |
| `support` | `phone` | `''` | Custom support phone number. |
| `support` | `email` | `''` | Custom support email; falls back to the GitHub support page when empty. |

## Whitelabel branding

Full mechanics in [Whitelabel branding](../administration/whitelabel.md).

| Key | Type | Default | Effect |
| --- | ---- | ------- | ------ |
| `whitelabel_key` | string | `''` | Signed Ed25519 license key. |
| `brand_name` | string | `''` | Product name replacing phpwcms. |
| `brand_logo_light` | string | `''` | Light theme logo URL or path. |
| `brand_logo_dark` | string | `''` | Dark theme logo URL or path. |
| `brand_logo_email` | string | `''` | Email logo URL or raster data URI. |
| `brand_custom_css` | string | `''` | Backend stylesheet URL or path. |
| `brand_support_url` | string | `''` | Support/documentation URL. |
| `brand_url` | string | `''` | Product website URL. |
| `brand_copyright` | string | `''` | Copyright text. |
| `brand_table_prefix` | string | `''` | Database table prefix in whitelabel mode (default `phpwcms`). |

## Related

- [Setup wizard](setup-wizard.md) — how the file gets written
- [Fresh installation](fresh-install.md) — surrounding hardening
- [Updating](updating.md) — why updates never touch this file