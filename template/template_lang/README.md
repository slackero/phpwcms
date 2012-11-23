phpwcms template_lang
=====================

phpwcms supports special replacement tag which can be used for easy internationalization.

Every `@@Replacer@@` will be rendered based on current active frontend language as defined in `$phpwcms['default_lang'] = 'en'`. In addition `$phpwcms['i18n_parse'] = 1;` is needed. If frontend parser detects `@@Street@@` this is replaced by `Street` and a new `$i18n_tokens['Street'] = 'Street';` will be added to `template/template_lang/en.php`.

If you might support German `de` and someone is visiting the website the file `de.php` with same content will be created. Now you are able to replace default text by translated `$i18n_tokens['Street'] = 'Straße';`.