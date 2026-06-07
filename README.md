[![phpwcms](https://www.phpwcms.org/indeximg/phpwcms-logo.svg)](https://www.phpwcms.org)
=========

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-8892BF.svg?style=flat-edge)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-%3E%3D%205.6-blue.svg?style=flat-edge)](https://mysql.com)
[![License](https://img.shields.io/badge/License-GPL--2.0-green.svg?style=flat-edge)](https://opensource.org/licenses/GPL-2.0)

**phpwcms** is an exceptionally flexible, fast, robust, and customer- and developer-friendly web-based Content Management System (CMS) and CMS Framework. Crafted for high speed, customizable templating architectures, and ease of use, phpwcms runs smoothly under PHP and MySQL/MariaDB.

Created and actively maintained by [Oliver Georgi](https://github.com/slackero).

---

## Key Features 🌟

* **Highly Flexible Framework**: Adaptive template system supporting standard web standards and custom semantic layouts.
* **Rich Component Ecosystem**: Ready-to-use core modules:
  * 🛍️ **Shop Module** (`mod_shop`): Integrated lightweight e-commerce module.
  * 📅 **Calendar Module** (`mod_calendar`): Event scheduling and management.
  * 📧 **Newsletter Module**: Campaign management and subscriber list handling.
  * 📖 **Glossary & Ads Managers**: Built-in terminology indexation and banner advertising platforms.
  * 👥 **Address Manager**: Store and query contacts/locations efficiently.
* **Modern Admin Toolkit**: Powered by modern text processors (**TinyMCE 8** default, legacy **CKEditor 4.x** optional), responsive upload managers, and sleek file archives.
* **Enterprise-Ready Emailing**: Standardized on **PHPMailer 7.1+** supporting secure modern mail transports, including OAuth2 protocols.

---

## Server & System Requirements 🖥️

This branch is fine-tuned and fully compatible with **PHP 8.2+**.

### Required Environment
| Requirement | Minimum Version | Recommended Version | Note |
|:---|:---|:---|:---|
| **PHP** | `8.2` | `8.2+` or `8.3` | Native type safety and memory optimizations |
| **MySQL / MariaDB** | `5.6` | `5.7+` / `10.2+` | Support for transactional storage engines and strict modes |
| **Web Server** | Apache (with `.htaccess`), Nginx, or IIS | Apache / Nginx | Configuration templates provided |

### PHP Extensions Required
To support all features, the following PHP modules must be enabled in your environment:
* `intl` (Internationalization)
* `gd` (Image scaling, WebP, and thumbnail generation)
* `iconv` & `mbstring` (Multi-byte character handling & conversion)
* `mysqli` (MySQL driver)
* `fileinfo` (Safe mime-type upload validation)
* `xmlreader`, `libxml`, `dom`, `simplexml` (XML/HTML parsing feeds & sitemaps)
* `openssl` (Secure SMTP & external API handshakes)
* `zip` (Archive extraction and asset uploads)
* `curl` (External API requests & payment gateway integrations)

---

## Directory Structure Overview 📂

* `/include` — Core CMS logic, extensions (`inc_ext`), language packs (`inc_lang`), modular libraries, and Composer dependencies (`include/vendor`).
* `/template` — Visual templates, site assets, layouts, scripts, and library dependencies.
* `/setup` — Automatic browser installation and database schema creation wizard.
* `phpwcms.php` & `index.php` — Primary bootstrap controllers routing the administration backend and public frontends.

---

## Installation & Setup ⚙️

1. **Clone & Extract**: Place the phpwcms files in your web server's document root.
2. **Install Dependencies**: Run Composer to retrieve current dependency configurations:
   ```bash
   composer install
   ```
3. **Run the Installer**: Navigate to `http://your-domain.com/setup/` in your web browser and follow the on-screen configuration wizard to link your database and set up administrator credentials.
4. **Finalize**: Secure your server by removing or protecting the `/setup` folder after successful deployment. Configure either `nginx.conf` or `.htaccess` depending on your environment.

---

## Developer Workflow 🛠️

A native `Makefile` is provided to streamline development, static analysis, and code quality tasks:

### Running Static Analysis
Locally analyze code quality and type safety using PHPStan:
```bash
make phpstan-analyse PHP=/path/to/php
```

### Updating PHPStan Baseline
To record current static analysis exceptions to the baseline file:
```bash
make phpstan-update PHP=/path/to/php
```

### Dependency Mapping
Update the system dependencies graph and documentation indexes:
```bash
make stacklit-update
```

---

## FAQ ❓

### How to set up SMTP with Microsoft Azure and XOAUTH2?
You can find the complete step-by-step setup guide for Microsoft Azure and XOAUTH2 integration in the [PHPMailer Wiki Guide](https://github.com/PHPMailer/PHPMailer/wiki/Microsoft-Azure-and-XOAUTH2-setup-guide).

### How to set up SMTP with Google and XOAUTH2?
You can find the complete step-by-step setup guide for Google and XOAUTH2 in the [PHPMailer Wiki Guide](https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2).

---

## Community & Resources 🤝

* **Website**: [phpwcms.org](https://www.phpwcms.org)
* **Wiki**: [HowTo Wiki](https://wiki.phpwcms.org/)
* **Support**: [phpwcms support forum](https://forum.phpwcms.org)

---

## Copyright and License ⚖️

Copyright 2002-2026 [Oliver Georgi](mailto:og@phpwcms.org?subject=phpwcms)

Licensed under the GNU General Public License, Version 2 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

<https://opensource.org/licenses/GPL-2.0>

This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation; either version 2 of the License, or (at your option) any later version.
