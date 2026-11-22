# Web server path protection

`path-protection.sh` starts the Docker stack and probes every private path on
both web servers. Apache keeps port 8080, nginx (PHP-FPM) uses 8082.

```bash
tests/web/path-protection.sh
APACHE_URL=http://localhost:8080 NGINX_URL=http://localhost:8082 tests/web/path-protection.sh
```

Expected results:

- private paths (`content/{tmp,cache,log,backup,form}`, `include/config`,
  `include/vendor`, internal `include/` directories, `upload/`, `tests/`,
  `filearchive/`, hidden files) answer 403 or 404,
- public paths (`template/`, `img/`, `content/images/`, `include/inc_css/`,
  `include/inc_ext/ckeditor/`) answer 200,
- the document root is not answered with 404,
- `formfile.php?t=invalid` reaches PHP and answers `File not found.`, so the
  signed-link controller is not blocked by the server configuration.

The private storage root is a named volume mounted at
`/var/www/phpwcms-storage`, matching the default `../phpwcms-storage` relative
to `/var/www/html`.
