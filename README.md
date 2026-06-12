# cmsGO! 🚀

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-8892BF.svg?style=flat-edge)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-%3E%3D%205.1%20(5.5%2B%20rec)-blue.svg?style=flat-edge)](https://mysql.com)
[![Maintainer](https://img.shields.io/badge/Maintained%20by-pixels%20%26%20points-informational.svg?style=flat-edge)](https://pixels-points.ch)

**cmsGO!** is an exceptionally flexible, fast, robust, and developer-friendly web-based Content Management System (CMS) and CMS Framework. Crafted for speed, customizable architectures, and ease of use, cmsGO! empowers developers to build and manage highly-tailored digital experiences under PHP and MySQL/MariaDB.

Created and lovingly maintained by [pixels & points](https://pixels-points.ch).

---

## Key Features 🌟

* **Highly Flexible Framework**: Adaptive template system supporting standard web standards and semantic layouts.
* **Rich Component Ecosystem**: Ready-to-use core modules:
  * 🛍️ **Shop Module** (`mod_shop`): Integrated lightweight e-commerce module.
  * 📅 **Calendar Module** (`mod_calendar`): Event scheduling and management.
  * 📖 **Glossary & Ads Managers**: Built-in terminology indexation and banner advertising platforms.
  * 👥 **Address Manager**: Store and query contacts/locations efficiently.
  * 📊 **Statistics & SEO Loggers**: Trace page traffic and organic search patterns natively.
* **Modern Admin Toolkit**: Powered by standard text processors (CKEditor), responsive upload frameworks, and sleek image zoom/fancyBox assets.
* **Enterprise-Ready Emailing**: Standardized on **PHPMailer 7.1+** supporting secure modern mail transports, including OAuth2 protocols.

---

## Server & System Requirements 🖥️

This branch is fine-tuned and fully compatible with **PHP 8.2+**.

### Required Environment
| Requirement | Minimum Version | Recommended Version | Note |
|:---|:---|:---|:---|
| **PHP** | `8.2` | `8.2+` or `8.3` | Native type safety and memory optimizations |
| **MySQL / MariaDB** | `5.1` | `5.5+` | Support for transactional storage engines |
| **Web Server** | Apache (with `.htaccess`), Nginx, or IIS | Apache / Nginx | Configuration templates provided |

### PHP Extensions Required
To support all features, the following PHP modules must be enabled in your environment:
* `intl` (Internationalization)
* `gd` (Image scaling and thumbnail generation)
* `iconv` & `mbstring` (Multi-byte character handling & conversion)
* `mysqli` (MySQL driver)
* `fileinfo` (Safe mime-type upload validation)
* `xmlreader`, `libxml`, `dom`, `simplexml` (XML/HTML parsing feeds & sitemaps)
* `openssl` (Secure SMTP & external API handshakes)
* `zip` (Archive extraction and asset uploads)
* `curl` & `bcmath` (External requests & payment gateway computations)

---

## Directory Structure Overview 📂

* `/include` — Core CMS logic, extensions (`inc_ext`), language packs (`inc_lang`), modular libraries, and Composer dependencies (`include/vendor`).
* `/template` — Visual templates, site assets, layouts, scripts, and responsive library dependencies (e.g. jquery, fancyBox, cookieconsent).
* `/setup` — Automatic browser installation and database schema creation wizard.
* `cmsgo.php` & `index.php` — Primary bootstrap controllers routing the administration backend and public frontends.

---

## Installation & Setup ⚙️

1. **Clone & Extract**: Place the cmsGO! files in your web server's document root.
2. **Install Dependencies**: Run composer to retrieve current dependency configurations:
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
make phpstan-analyse
```

### Updating PHPStan Baseline
To record current static analysis exceptions to the baseline file:
```bash
make phpstan-update
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
You can find the setup guide for Google accounts and XOAUTH2 authentication in the [Using Gmail with XOAUTH2 PHPMailer Wiki](https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2).

---

© [pixels & points](https://pixels-points.ch). Licensed under the project's standard commercial/development terms. Refer to [LICENSE](file:///Users/slackero/Dropbox/Sites/pixels-points/cmsgo-v2.0/LICENSE) for legal definitions.

