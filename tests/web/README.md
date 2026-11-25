# Web server path protection tests

`path-protection.sh` starts the Docker stack (Apache, PHP-FPM, nginx, MariaDB)
and checks that every private, upload and internal path is refused while the
public paths stay reachable.

```bash
docker compose up -d --build     # or let the script start it
tests/web/path-protection.sh
```

Expected statuses:

- private, upload, internal and development files: `403` or `404`
- public assets and `content/form` uploads: `200`
- document root `/`: not `404`

The script only probes files that do not exist in the repository; existing
files such as `CHANGELOG.md` are tested with their real content and never
overwritten. Cleanup removes every probe file and stops the containers.

Override endpoints when needed:

```bash
APACHE_URL=http://localhost:8090 NGINX_URL=http://localhost:8092 tests/web/path-protection.sh
```

IIS is not covered: Windows containers cannot run on macOS or Linux. Its
`web.config` is validated as XML only.
