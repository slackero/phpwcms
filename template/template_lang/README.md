phpwcms template_lang
=====================

phpwcms supports special replacement tag which can be used for easy internationalization.

Every `@@Replacer@@` will be rendered based on current active frontend language as defined in `$phpwcms['default_lang'] = 'en'`. In addition `$phpwcms['i18n_parse'] = 1;` is needed. If frontend parser detects `@@Street@@` this is replaced by `Street` and a new `$i18n_tokens['Street'] = 'Street';` will be added to `template/template_lang/en.php`.

If you might support German `de` and someone is visiting the website the file `de.php` with same content will be created. Now you are able to replace default text by translated `$i18n_tokens['Street'] = 'Straße';`.

Limits and safety
-----------------

Every new `@@Text@@` token appends one entry to the language file of the active frontend language. Two settings in `include/config/conf.inc.php` bound that growth:

| Setting | Default | Meaning |
| ------- | ------- | ------- |
| `$phpwcms['i18n_lang_file_max_size']` | `1048576` | Maximum size in bytes of a language file. Once a file exceeds it, new tokens are rendered but not appended. `0` = unlimited. |
| `$phpwcms['i18n_token_max_length']` | `1024` | Maximum character length of a single token. Longer tokens render as plain text and are not stored. `0` = unlimited. |

Tokens containing control characters (`0x00`-`0x1F`, `0x7F`) are rejected as well.

Translation text is written with `var_export()`, so quotes, backslashes and newlines cannot escape the generated PHP literal. Generated language files are included on every frontend request - never add executable PHP to them, and delete a file to drop its entries and start over.
