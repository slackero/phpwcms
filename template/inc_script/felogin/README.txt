Frontend Login
==============

This is a lightweight set of scripts to provide simple frontend login protection
for multiple users and multiple site levels based on an INI configuration file.

Setup:
------
1. Copy `felogin.init.php` to `template/inc_script/frontend_init/felogin.init.php`
   (or remove from the `disabled/` subdirectory).
2. Copy `felogin.render.php` to `template/inc_script/frontend_render/felogin.render.php`
   (or remove from the `disabled/` subdirectory).
3. Configure `template/inc_script/felogin/felogin.ini.php`.
   Passwords can be plain text or generated using modern `password_hash()` (e.g. Bcrypt/Argon2).
4. Text strings support multilingual translation via phpwcms `@@Text@@` replacement tags.

Usage:
------
To render the login/logout form, place the `{FELOGIN}` replacement tag in an article
or HTML content part.

Other available replacement tags:
- `{FELOGIN_USER}`       - login name of the currently logged-in user
- `{FELOGIN_ERROR}`      - login form error messages
- `{FELOGOUT_PREFIX}`    - logout link prefix
- `{FELOGOUT_SUFFIX}`    - logout link suffix

Checking login state in custom templates / inline PHP:
```php
[PHP]
if (defined('FELOGIN_IS_LOGGED') && FELOGIN_IS_LOGGED) {
    echo '@@Hello@@ {FELOGIN_USER}<br />';
    echo '<a href="index.php?id='.FELOGIN_LEVEL_ID.'&amp;logout='.FELOGIN_LOGOUT_GET_VALUE.'">@@Logout@@</a>';
} else {
    echo '@@You are not logged in.@@';
}
[/PHP]
```

--
Copyright (c) 2008-2026 Oliver Georgi <og@phpwcms.org>