---
title: Profile
description: Personal account settings — login data, language, theme, editor and content part selection.
sidebar_position: 6
---
# Profile

> Source: `include/inc_tmpl/profile.account.tmpl.php`, `include/inc_lib/profile.updateaccount.inc.php`, `phpwcms.php` (do=profile dispatch)

The profile page (`phpwcms.php?do=profile`) is reached from the user icon in the header; it shows only when the user belongs to the `profile` SYSGROUP. Super admins are seeded into every SYSGROUP when the group does not exist yet, but can be removed from the group afterwards — in that case the header link disappears. The page exists in two variants: backend accounts and frontend accounts render the same form — the icon differs (`fa-user-shield` for admins, `fa-user-check` for frontend users, `fa-user` / `fa-user-cog` in between).

Saving requires the account not to be the `guest` login; changed values are written to `phpwcms_user` and mirrored into the session.

## Account card

> Source: `include/inc_tmpl/profile.account.tmpl.php`

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Username (`form_loginname`) | text | current login | Unique check runs before saving; a taken name aborts the update. |
| New password / Repeat (`form_password`, `form_password2`) | password | empty | Both must match; empty leaves the password unchanged; minimum length 5 characters. Show/hide toggle per field. |
| Email (`form_useremail`) | text | current email | Validated; on change a notification is sent to the old address, and a password change triggers a confirmation email to the account address, both rendered from the system email templates. |
| Language (`form_lang`) | select | current backend language | Backend UI language, one entry per `include/inc_lang/backend/<lang>/lang.inc.php`; legacy directory aliases (`cz`, `se`, `vn`, `el`) are mapped to their ISO codes and an invalid value falls back to the default language or `en`. Also sets the `phpwcmsBELang` cookie. |
| Theme (`form_theme`) | select | auto | Backend theme: auto (follows system scheme), light, dark. Writes the session value and the `phpwcmsBETheme` cookie; see [Backend overview](01-overview.md#theme-switching). |
| WYSIWYG (`form_wysiwyg`) | select | inactive | 0 = textarea, 1 = CKEditor (labeled legacy), 2 = TinyMCE 8 (labeled default); see the [backend overview](01-overview.md#wysiwyg-editor-settings). |
| Content parts (`profile_account_cp[]`) | checkbox grid | all checked | Selects which content part types the editor offers when adding parts to an article (`wcs_user_cp`); types blocked by the account's `allowed_cp` list render disabled and cannot be checked. |

## Two-factor authentication card

A second card manages TOTP 2FA for the account:

- **Enable**: the page generates a pending secret (session `pending_2fa_secret`), shows its QR code and `otpauth://` URL, and activates after a 6-digit code from the authenticator app is confirmed. On success, eight single-use backup recovery codes are displayed once and stored hashed in `usr_vars`.
- **Disable**: requires the current password; clears the secret and backup codes.

Both actions post `form_aktion` = `enable_2fa` / `disable_2fa`, handled by `profile.updateaccount.inc.php` in the same `do=profile` dispatch.

## Data storage

| Value | Stored in |
| ----- | --------- |
| Login name, password, email | `phpwcms_user` (`usr_login`, `usr_pass` bcrypt-hashed, `usr_email`) |
| Backend language | `usr_lang` plus the `phpwcmsBELang` cookie |
| Theme and WYSIWYG choice | `usr_vars` (serialized `theme`); WYSIWYG additionally in the `usr_wysiwyg` column and session `WYSIWYG_EDITOR` |
| Selected content parts | `usr_vars` (serialized `selected_cp`), kept in `wcs_user_cp` |
| 2FA state | `usr_2fa_enabled`, `usr_2fa_secret`, backup codes in `usr_vars` (`2fa_backup_codes`) |

The profile form does not change group memberships, admin flags or [structure-level](02-structure-tree.md) permissions — those belong to the [user administration](../administration/users-usergroups.md).