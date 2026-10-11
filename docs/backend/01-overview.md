---
title: Backend overview
description: Layout, navigation, dashboard, editor settings and theme switching of the phpwcms backend.
sidebar_position: 1
---
# Backend overview

> Source: `phpwcms.php`, `include/inc_tmpl/be_start.tmpl.php`, `include/inc_lib/backend.functions.inc.php`, `include/inc_lib/whitelabel.inc.php`, `include/inc_lib/wysiwyg.editor.inc.php`, `include/inc_lib/default.inc.php` (theme helpers)

The backend lives at `/phpwcms.php` and requires a login through `login.php`. It is a Bootstrap 5 based single-column application: a fixed header bar, a collapsible left navigation, the content area, and a footer. Every page is output with the charset configured in the installation and a `Content-Security-Policy` meta tag assembled in `phpwcms.php`.

## Layout

| Area | Content |
| ---- | ------- |
| Header | Brand logo (link to the backend home), frontend preview link, search, profile link, logout, theme switcher |
| Left sidebar | Main navigation described below, a collapse button, and a small footer line listing loaded JS libraries |
| Content | The active section, wrapped as cards; a status message (success/error) is rendered above it |
| Footer | Version and copyright, switchable by the whitelabel feature (`is_whitelabel()`, `get_brand_name()`) |

Branding, logo, footer wording and custom CSS can be replaced by whitelabel settings from `include/config/custom_settings.php`; see `include/inc_lib/whitelabel.inc.php`.

## Main navigation

> Source: `phpwcms.php` (nav building), `include/inc_lib/navi_text.inc.php`

The sidebar shows an item only when the user has the matching permission. Permissions come from active, non-trashed usergroups whose `group_syskey` matches the key below, and super admins (`usr_admin = 1`) pass every check. See [Users and usergroups](../administration/users-usergroups.md).

| Item | Icon | Gated by | Links to |
| ---- | ---- | -------- | -------- |
| Dashboard | `fa-tachometer-alt` | always | `phpwcms.php` |
| Articles | `fa-copy` | `artcent` or `artnews` | Article center, New article (requires `artcent`), News (requires `artnews`) |
| Files | `fa-folder-open` | `filecent`; sub-items additionally `fileaction`, `fileupload` | File center, File actions, Multiple file upload |
| Modules | `fa-puzzle-piece` | `enable_backend_module` config plus `module` group; per-module `group_modkey` groups | one entry per module |
| Communication | `fa-envelope` | `enable_backend_newsletter` config plus `nl`; sub-items additionally `nlabo`, `nllist`, `nlrecip` | Newsletter subscriptions, Newsletter, Newsletter subscribers |
| Admin | `fa-cog` | `adm`; sub-items per page (`admlayout`, `admtempl`, `admuser`, `admugroup`, `admialias`, `admfilecat`, `admalias`, `admlink`, …) | Page layout, Templates, User administration, User groups, and so on |

Below the Admin menu, users with any admin permission also find two maintenance actions: *Flush image cache* (AJAX call to `include/inc_act/ajax_connector.php`) and *Move deleted files* (`include/inc_act/act_file.php?movedeletedfiles=…`). Super admins additionally get *System update* and `phpinfo()`.

The header holds the per-user items instead: the account menu opens the [Profile](06-profile.md) page (visible when the user is member of the `profile` group or is an admin, with different icons for super admin, frontend user and backend user), and *Logout* ends the session.

## Dashboard

> Source: `include/inc_tmpl/be_start.tmpl.php`, plus `template/inc_default/startup.php`

The dashboard (`phpwcms.php` without parameters) lists two tables: the most recently edited articles and the most recently edited [content parts](../general/concepts.md#the-content-hierarchy). For both, the top drop-down chooses the row count — 5 to 150 or all — and the value is stored in the `homeMaxArticles` / `homeMaxCntParts` cookies for one year. A hidden content type filter (`homeCntType`) narrows the content part list. Both lists offer a visibility toggle (eye icon) and an edit link per row; non-admin users only ever see their own articles here.

The installation's own `template/inc_default/startup.php` renders additional dashboard widgets and can be customized per template.

## Header search

> Source: `phpwcms.php` (search form), `include/inc_tmpl/be_start.tmpl.php`

The search icon in the header opens a form whose term is stored in `$_SESSION['phpwcms_backend_search']` and applied on the next dashboard view: article rows are matched against title, subtitle and summary; content part rows against title, subtitle, text and HTML body. The heading above each list shows `Search: <term>` while a term is set; clearing the search resets the session value.

## User context

Login state is kept in the session (`checkLogin()`). The most relevant values:

| Session value | Meaning |
| ------------- | ------- |
| `wcs_user_id`, `wcs_user`, `wcs_user_name` | Account ID, login name, display name |
| `wcs_user_admin` | 1 = super admin; bypasses every group check `has_admin_permission()` performs |
| `wcs_user_lang` | Backend language; validated against `include/inc_lang/backend/<lang>/` |
| `wcs_user_theme` | Backend theme, see below |
| `WYSIWYG_EDITOR` | WYSIWYG choice, see below |
| `wcs_user_cp` | Content parts the user has selected to be offered when adding parts |
| `wcs_allowed_cp` | Content parts allowed by the account (empty = all) |
| `structure` | Structure tree expansion state per user, persisted in `usr_var_structure` |

## WYSIWYG editor settings

> Source: `include/inc_lib/wysiwyg.editor.inc.php`

The editor used for the [article summary](03-articles.md) and for content part rich text is chosen per user in the [profile](06-profile.md#account-card):

| Value | Editor |
| ----- | ------ |
| 0 | Plain textarea (editor off) |
| 1 | CKEditor 4 — loaded from `include/inc_ext/ckeditor/`; custom configuration in `template/config/ckeditor/ckeditor.config.js` (or `ckeditor.config-tabs.js` for tabbed forms) when present, otherwise a built-in toolbar |
| 2 | TinyMCE 8 — loaded from `include/vendor/`, German and other UI languages via `include/vendor/mklkj/tinymce-i18n/` |

With `FCK_FileBrowser` enabled in the configuration, both editors get file/article browser pickers backed by `filebrowser.php` and `articlebrowser.php`.

## Theme switching

> Source: `phpwcms.php`, `include/inc_lib/backend.functions.inc.php` (`get_backend_theme()`, `set_theme_cookie()`), `include/inc_lib/default.inc.php` (`get_theme_boot_script()`)

The adjustment icon in the header opens a drop-down with **Auto**, **Light** and **Dark**. The choice is persisted as `wcs_user_theme` in the session and database, mirrored into the `phpwcmsBETheme` cookie and into `localStorage("phpwcms_theme")` as an AJAX fallback. An inline boot script set in `get_theme_boot_script()` sets `data-theme` and resolves `data-bs-theme` to the stored value — or to `prefers-color-scheme` for *Auto* — before the stylesheet renders, so no light/dark flash occurs. The same choice exists as a form field in the [profile](06-profile.md#account-card) page.

## Other interface conventions

- **Icons**: FontAwesome 7 Free/Brands glyphs bundled in `include/inc_css/backend.min.css`; icon-only controls carry real `<i>` elements and tooltips.
- **CSRF**: every backend link carries a token generated by `get_token_get_string()`; POST forms include hidden token fields (`validate_csrf_tokens()` runs on every request).
- **Sidebar**: the collapse toggle stores its state in `localStorage("phpwcms_sidebar_collapsed")`; on small screens the bars icon (`#button-menu`) toggles the menu instead.
- **Status messages**: backend actions report through `set_status_message()` / `show_status_message()` and appear at the top of the next page.
- **Keyboard shortcuts**: the current backend defines no global keyboard shortcuts; interaction is mouse/touch based.

## Related

- [Structure tree](02-structure-tree.md)
- [Articles](03-articles.md)
- [Files — file center](04-files-filecenter.md)
- [Messaging](05-messaging.md)
- [Profile](06-profile.md)