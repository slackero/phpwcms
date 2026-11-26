[![phpwcms](https://www.phpwcms.org/indeximg/phpwcms-logo.svg)](https://www.phpwcms.org)
=========

**phpwcms** is a very flexible, fast, robust, customer and developer friendly
but yet powerful web based content management system and cms framework running
under PHP and MySQL/MariaDB. phpwcms is created and maintained by
[Oliver Georgi](http://twitter.com/slackero).

To get started, checkout [phpwcms.org](https://www.phpwcms.org) or the community driven
[HowTo Wiki](https://wiki.phpwcms.org/) (snapshot). Most questions are yet
answered on the [phpwcms support forum](https://forum.phpwcms.org).


Quick start
-----------

Stable releases can be used by cloning the repository, `git clone git://github.com/slackero/phpwcms.git` or
[download the archive](https://github.com/slackero/phpwcms/releases).

To start with the latest development version use `git clone -b v1.9-dev git://github.com/slackero/phpwcms.git` or
[download the archive](https://github.com/slackero/phpwcms/archive/refs/heads/v1.9-dev.zip).
If you have downloaded the archive instead of `git clone`, unarchive and copy the files to your web document
root or sub folder. Link your browser to the related URL and follow the install instructions.


Server system requirements
--------------------------

**phpwcms** version 1.9.56-dev requires a web server with PHP 7.4 or newer.
and a MySQL/MariaDB database (minimum version 5.1, recommend 5.5+).
If you already use PHP v8.x you should use the latest version of
[**phpwcms v1.11**](https://github.com/slackero/phpwcms/releases/tag/v1.11.0).


Web server hardening
--------------------

The shipped server configurations deny HTTP access to every path that must not
be reachable. Only public assets are served:

- `template/`, `img/`, `picture/`
- `content/form/`, `content/images/`, `content/rss/`, `content/pages/`,
  `content/marketing/`
- `include/inc_css/`, `include/inc_js/`, `include/inc_ext/ckeditor/`
- `setup/` (remove the folder after installation)
- PHP entry scripts in the document root

Everything else is refused: `content/tmp`, `content/cache`, `content/log`,
`content/backup`, `include/config`, `include/vendor`, the internal `include`
directories, `upload/`, `filearchive/`, `tests/`, hidden files, SQL/log/backup
files and development files.

| Server | Configuration | Behaviour |
|:---|:---|:---|
| Apache | `.htaccess` and `_.htaccess` (keep both in sync), plus `.htaccess` files inside private directories | `404` for blocked paths; per-directory `Deny` files return `403` |
| nginx | `nginx.conf` | `404`; the file must be included **before** the PHP location, otherwise a `.php` request is handled by PHP first |
| IIS | `web.config` | `404`; uses a default-deny allowlist and request filtering |

Notes:

- `.htaccess` is ignored by nginx and IIS, and is inert on Apache with
  `AllowOverride None`. On such hosts only the nginx/IIS configuration or a
  server-level Apache snippet protects the paths.
- `content/form` stays readable on purpose: forms with `attachment=0` link the
  uploaded file. Directory listing is prevented by `index.html`, and PHP
  execution is denied.
- `filearchive` is private by default. To enable the direct-download option of
  file list content parts: on Apache remove or edit `filearchive/.htaccess`
  (the content part checks it for `deny`); on nginx remove the
  `location ~ /filearchive` block and add `filearchive` to the allowlist; on
  IIS add `filearchive` to the `Allow public paths` rule. Files are then
  reachable directly instead of through `download.php` only.

### Renamed default folders

`content`, `upload`, `filearchive` and `template` are configurable in
`include/config/conf.inc.php`:

```php
$phpwcms['content_path'] = 'content';
$phpwcms['ftp_path']     = 'upload';
$phpwcms['file_path']    = 'filearchive';
$phpwcms['templates']    = 'template';
```

If you rename one of them:

1. Update the configuration value.
2. Update the server rules. Apache patterns are not anchored, but they still
   match the literal names; nginx and IIS use explicit allowlists. For a renamed
   `content`, replace the name in the `content/...` patterns of `nginx.conf`
   and in `Allow public paths` of `web.config`. The same applies to `upload`,
   `filearchive` and `template`.
3. Keep the per-directory `.htaccess` files inside the private folders; on
   Apache they travel with a renamed directory and keep protecting it.
4. Verify with `curl`, for example `curl -I https://example.com/<new-name>/tmp/`
   must not return `200`.


Docker test environment
-----------------------

A Docker setup is included for testing the web server configurations:

```bash
docker compose up -d --build
```

| Service | URL / port | Note |
|:---|:---|:---|
| Apache + PHP 7.4 | http://localhost:8090 | `.htaccess` enabled via `AllowOverride All` |
| nginx + PHP-FPM | http://localhost:8092 | loads `nginx.conf` before the PHP location |
| phpMyAdmin | http://localhost:8091 | database `phpwcms` |
| Mailpit | http://localhost:8036 | SMTP on port 1036 |
| MariaDB | `localhost:3317` | user `phpwcms`, password `phpwcmspassword` |

The installer is at http://localhost:8090/setup/ or http://localhost:8092/setup/.

Run the path protection test:

```bash
tests/web/path-protection.sh
```

It starts the stack, places probe files in every protected and public path,
checks the HTTP status on Apache and nginx, and removes the probes afterwards.

PHP 7.4 has no ARM64 image; on Apple Silicon the compose file runs the PHP
containers with `platform: linux/amd64`, and the Dockerfiles point apt at the
archived Debian repositories. IIS cannot run in Docker on macOS/Linux; its
configuration is validated as XML only.


Known problems
--------------

Because of the project history there are several probable problems regarding the database. 
MySQL changed the time and date related default values over the last years. Check to setup
the related config values to connect to the database in a more compatible way. MySQL Strict
is no good option. I work on this to [solve the problems](https://github.com/slackero/phpwcms/issues/275)
soon.


Bug tracker
-----------

Did you find a bug? Please create an **[issue here](https://github.com/slackero/phpwcms/issues)** on GitHub
that conforms with [necolas's guidelines](https://github.com/necolas/issue-guidelines).


Share with us
-------------

Keep up to date on announcements and more by following **phpwcms** on 
[Github](https://github.com/slackero/phpwcms))*


Creator
-------

**Oliver Georgi**

- <https://github.com/slackero>
- <https://www.linkedin.com/in/olivergeorgi>


Copyright and license
---------------------

Copyright © 2002-2026 [Oliver Georgi](mailto:og@phpwcms.org?subject=phpwcms)

Licensed under the GNU General Public License, Version 2 (the "License");
you may not use this work except in compliance with the License.
You may obtain a copy of the License in the LICENSE file, or at:  
<https://opensource.org/licenses/GPL-2.0>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the  
Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston,
MA 02110-1301,
USA.
