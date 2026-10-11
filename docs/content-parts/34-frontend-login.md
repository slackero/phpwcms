---
title: Frontend login
description: Login, logout, password reminder, registration and profile editing for frontend users on a template-driven form.
sidebar_position: 34
---
# Frontend login (content part 28)

> Source: `include/inc_lib/content/cnt28.readform.inc.php`, `include/inc_front/content/cnt28.article.inc.php`, `include/inc_tmpl/content/cnt28.inc.php`, `template/inc_cntpart/felogin/`

The login CP, labeled `Frontend login`. It authenticates against the frontend user table (`phpwcms_userdetail`) and optionally the backend user table, manages the session, sets the remember cookie and renders the appropriate template section: login form, logged-in box, reminder form, registration form or profile editor.

## Purpose

Gate protected content parts and levels. Combined with article usergroup settings it covers private areas. Logout is handled globally: a posted or queried `feLogout` destroys the session (`include/inc_front/front.func.inc.php`).

## Backend form

Fields top to bottom (`include/inc_tmpl/content/cnt28.inc.php`).

| Field | Type | Default | Description |
| ----- | ---- | ------- | ----------- |
| Template | select | *(empty)* | File from `template/inc_cntpart/felogin/` (`fe_login.tmpl`, `BS5.tmpl`). Without a file nothing renders. |
| Cookie runtime | number, seconds | 2592000 (30 days) | Lifetime of the remember-me cookie `phpwcmsFeLoginRemember`, only set when "Remember" is checked and the value is non-zero. |
| Date format | text | `%m/%d/%y` | `strftime()` format used by the profile editor. |
| Locale | text | — | `setlocale()` value for the profile editor (`de`, `de_DE`). |
| Validate against | checkbox set | userdetail on, backend user on | Which tables accept logins: frontend user profiles (`phpwcms_userdetail`) and/or backend users (`phpwcms_user`). |
| Accept email as login | checkbox | off | Allows the stored email address in the login field. |
| Profile: registration | checkbox | on | Enables the `profile_register=…` URL switch rendering the `PROFILE_REGISTER` section. |
| Profile: manage | checkbox | on | Enables the `profile_manage=…` URL switch rendering the `PROFILE_MANAGE` section. |
| Manage redirect | text | — | Target alias, `aid=<id>` or `id=<id>` for the manage link; picked with the article browser (`articlebrowser.php?opt=6`). |

## Rendering

The template file is split into sections with `<!--SECTION_START//-->` markers: `LOGIN_SETTINGS`, `LOGIN_FORM`, `LOGIN_IS`, `PROFILE_REGISTER`, `PROFILE_MANAGE`, `REMINDER_FORM`, `REMINDER_SUCCESS`, `REMINDER_EMAIL`. The CP picks the section to render by state.

### Login state

Posts `feLogin`/`fePassword` are checked with `_checkFrontendUserLogin()`; success stores `$_SESSION[<session id>]` and the user data array, sets the remember cookie if requested, and redirects back to the current URL. Failed attempts re-render `LOGIN_FORM` with `[ERROR]`.

### Reminder

Posts `feReminder` resolve login or email: with a valid email the CP looks up the login and mails it; with a login it resets the password to a random 8-character string and mails the new one. `[SUBJECT]`, `[LOGIN_EMAIL]` and `[PASSWORD_EMAIL]` sections inside `REMINDER_EMAIL` build subject and body, `{LOGIN}`, `{PASSWORD}`, `{LOGIN_URL}` are replaced. Errors render `REMINDER_FORM` with `[ERROR]` and the typed value in `{REMINDER}`.

### Template tags

| Tag | Meaning |
| --- | ------- |
| `{FORM_TARGET}` | Current URL without `profile_*` and `feLogout` parameters. |
| `{LOGIN}` `{PASSWORD}` `{REMEMBER}` | Refill login form; `REMEMBER` is a `checked` fragment. |
| `[ERROR]…[/ERROR]` `[ERROR_ELSE]…[/ELSE_ERROR]` | Validation state of the active form. |
| `{REGISTER_PROFILE}` `[REGISTER_PROFILE]` | URL to the registration form; block stripped when registration is off. |
| `{MANAGE_PROFILE}` `[MANAGE_PROFILE]` `[MANAGE_PROFILE_ELSE]` | URL to the profile editor (uses the redirect setting); stripped when manage is off. |
| `{REMINDER}` `[REMINDER_FORM]` | Typed reminder value / URL to the reminder form. |
| `{LOGIN_URL}` | URL to the plain login view. |
| `{FE_USER_REGISTER}` `{FE_USER_MANAGE}` | Registration/editor markup. The CP only places the placeholders; you provide the forms via a frontend render script such as `template/inc_script/frontend_render/disabled/fe_user.php`. |

### Redirect and visibility interplay

Articles or levels can require a login. When access is denied and no user is logged in, `include/inc_front/content.func.inc.php` stores `$_SESSION['LOGIN_REDIRECT'] = FE_CURRENT_URL` and redirects to the login page given by the template setting `feloginurl` (`template_felogin_url` in the backend templates section). The CP consumes that session value after a successful login and redirects there; without it you land back on the login page URL. `block['feloginurl']` wins over the template default when set.

### LOGGED_IN sections

In addition to the CP, templates and articles support `<!--LOGGED_IN_START//-->…<!--LOGGED_IN_END//-->` comment blocks and `[LOGGED_IN]…[/LOGGED_IN]` / `[LOGGED_OUT]…[/LOGGED_OUT]` sections, processed globally in `content.func.inc.php` based on `_getFeUserLoginStatus()`. Use them for small switches; use this CP for the forms.

## Storage

- `acontent_template` — template file name.
- `acontent_form` — serialized settings (cookie runtime, date format, locale, validation switches, profile switches, manage redirect).
- Sessions and the `phpwcmsFeLoginRemember` cookie carry the login state; user data stays in `phpwcms_userdetail` (frontend) and `phpwcms_user` (backend).

## Related

- Users, groups and permission models: [Users and usergroups](../administration/users-usergroups.md#backend-vs-frontend-users).
- Guestbook login gating: [Guestbook/comment (cp 18)](28-guestbook.md).