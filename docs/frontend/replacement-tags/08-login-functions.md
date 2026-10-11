---
title: Login functions
description: Template sections and tags for frontend login state and access control.
sidebar_position: 8
---

# Login functions

> Source: `include/inc_front/content.func.inc.php` (`LOGGED_*` sections, `PERMIT_ACCESS`), `include/inc_front/front.func.inc.php` (`_getFeUserLoginStatus()`, `_checkFrontendUserAutoLogin()`), `template/inc_script/frontend_render/disabled/felogin.render.php`

phpwcms distinguishes the backend session (editors, administrators) from a frontend session. The template layer reacts to both with section markup and, with the optional felogin script enabled, with login replacement tags.

## LOGGED_IN and LOGGED_OUT sections

```
<!--LOGGED_IN_START//-->…<!--LOGGED_IN_END//-->
<!--LOGGED_OUT_START//-->…<!--LOGGED_OUT_END//-->
[LOGGED_IN]…[/LOGGED_IN]
```

Write the two comment sections anywhere in layout template, content part template or admin header/footer code. While the page renders, `_getFeUserLoginStatus()` decides:

- User is logged in: the `LOGGED_IN` section content is kept (comment markers stripped), the whole `LOGGED_OUT` section is removed.
- User is not logged in: the `LOGGED_OUT` content is kept, the `LOGGED_IN` section is removed.

The check answers true only when a session exists and `$_SESSION[<session id>]` holds the login name of an activated user, with GET/POST injection guards on the session key. With no session it answers false.

Both comment sections resolve inside the core `content.func.inc.php` pass. The bracket form `[LOGGED_IN]…[/LOGGED_IN]` — keep content while logged in, `render_cnt_template()` semantics otherwise — ships as the optional frontend render script `template/inc_script/frontend_render/disabled/logged_in.php` and needs that script enabled.

A successful frontend logout runs through the `feLogout` GET or POST value; logout links therefore point at the current page extended with `feLogout`.

## PERMIT_ACCESS and protected levels

A structure level with the "registered only" flag (`acat_regonly`) locks every level below it. The flag walks the level path of the current request; one match sets `PERMIT_ACCESS` to false. When false and no frontend user is logged in:

1. The current URL is stored in `$_SESSION['LOGIN_REDIRECT']` for the way back.
2. The request redirects with HTTP 401 to the login form URL — the template's `feloginurl` field when set (a leading `{SITE}` expands), otherwise `$template_default['login_form_url']`.

Content parts carry their own granted flag (`acontent_granted`); with a frontend user logged in, parts with permission level 2 become visible, without a login they are skipped. Article selection respects the same flag (constant `FEUSER_LOGIN_STATUS`).

`_checkFrontendUserAutoLogin()` reads the `phpwcmsFeLoginRemember` cookie before page assembly, so a remembered login applies without a new POST.

## Frontend login content part

The built-in "Frontend login" content part (type 28, `include/inc_front/content/cnt28.article.inc.php`) renders the standard login form from its template. It uses the template sections `LOGIN_FORM` (form while logged out) and `LOGIN_IS` (state while logged in) plus profile and reminder sections, and fills:

| Field | Value |
| ----- | ----- |
| `{LOGIN_URL}` and `{FORM_TARGET}` | the form action URL; after login the reader returns to `$_SESSION['LOGIN_REDIRECT']` or the current page |
| `{LOGIN}` | current login name inside the logged-in section |
| `[REGISTER_PROFILE]` / `[MANAGE_PROFILE]` / `[REMINDER_FORM]` | links when the content part enables registration, profile management or password reminder |

The related sample script `template/inc_script/frontend_render/disabled/fe_user.php` adds `{FE_USER_REGISTER}` and `{FE_USER_MANAGE}` markup for a customized registration form.

## felogin script tags

The shipped sample login `template/inc_script/felogin/` works with two scripts that are disabled by default — copy them from `template/inc_script/frontend_init/disabled/felogin.init.php` and `template/inc_script/frontend_render/disabled/felogin.render.php` into the active folders. The users and passwords live in `template/inc_script/felogin/felogin.ini.php`; login levels map to structure level IDs.

| Tag | Value |
| --- | ----- |
| `{FELOGIN}` | Complete login or logout form, `felogin.login.html` / `felogin.logout.html`. |
| `{FELOGIN_USER}` | Login name of the currently logged-in user, HTML escaped. |
| `{FELOGOUT_PREFIX}` / `{FELOGOUT_SUFFIX}` | HTML around the user name in the logout link, from `FELOGIN_LOGOUT_LINK_PREFIX` / `SUFFIX`. |
| `{FELOGIN_ERROR}` | Error list wrapped by `FELOGIN_ERROR_PREFIX` / `SUFFIX`, empty when no error. |
| `{FELOGIN_ACTION}` | Inside the login template: form action pointing at the login level. |
| `{FELOGIN_LOGOUT_URL}` | Inside the logout template: logout URL with the configured `logout` GET value. |

The page level tags (`{FELOGIN}`, `{FELOGIN_USER}`, `{FELOGOUT_PREFIX}`, `{FELOGOUT_SUFFIX}`, `{FELOGIN_ERROR}`) render only while `FELOGIN_IS_LOGGED` is defined — that is, only when the init script is active. The `@@Text@@` markers inside the shipped templates resolve through the language pass.

The felogin logout link appears as a virtual last level in navigation when `FELOGIN_LOGOUT_LINK` is set in the INI file; the nav renderers pick it up as a normal entry.

## Backend login

The form at `login.php` handles backend login for editors and administrators. The `LOGIN_REDIRECT` way-back applies to the frontend login content part instead: after a successful login it sends the reader to `$_SESSION['LOGIN_REDIRECT']` (set by the `PERMIT_ACCESS` redirect) or back to the current page.

## Not present in 2.x

`{LOGIN}`, `{LOGIN_REDIRECT_ELSE}` and `{PERMIT_ACCESS…}` tags from older documentation have no handler in this code base. Use the section markup above and the `PERMIT_ACCESS` behavior, or a frontend render script.

## Related

- [Meta and head tags](06-meta.md) — head channel the login redirect uses for 401 handling
- [NAV_LIST_UL](01-nav-list-ul.md) — the logout entry joins the menu tree