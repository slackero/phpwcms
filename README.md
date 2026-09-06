# phpwcms 🚀

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-8892BF.svg?style=flat-edge)](https://php.net)
[![Database](https://img.shields.io/badge/MySQL-%3E%3D%205.5%20(MariaDB%20rec)-blue.svg?style=flat-edge)](https://mysql.com)
[![Maintainer](https://img.shields.io/badge/Maintained%20by-Oliver%20Georgi-informational.svg?style=flat-edge)](https://www.phpwcms.org)
[![License: GPL-2.0](https://img.shields.io/badge/License-GPL--2.0-green.svg?style=flat-edge)](https://opensource.org/licenses/GPL-2.0)

**phpwcms** is an exceptionally flexible, fast, robust, and developer-friendly web-based Content Management System (CMS) and CMS Framework. Crafted for speed, customizable architectures, and ease of use, phpwcms empowers developers to build and manage highly-tailored digital experiences under PHP and MySQL/MariaDB.

Created and lovingly maintained by [Oliver Georgi](https://www.phpwcms.org).

---

## Key Features 🌟

* **Highly Flexible Framework**: Adaptive template system supporting standard web standards, semantic layouts, and designated block replacement tags (`{CONTENT}`, `{HEADER}`, `{NAV_LIST_UL}`).
* **Built-in System Updater**: One-click in-app self-update engine (**Admin -> System Update**) and standalone CLI/web updater (`setup/upgrade.php`) with automated database dumps, file backups, and rollback capabilities.
* **Modern Administration UI**: Clean Bootstrap 5 backend with theme switching (Auto / Light / Dark modes), FontAwesome 7 icon set, accessible toggle switches, and centralized modal confirmations.
* **Master Email Templates**: Visual system email template manager (**Admin -> Email Templates**) with modular layouts (`mail_layout`, `mail_header`, `mail_footer`) and responsive HTML formatting.
* **Rich Component Ecosystem**: Ready-to-use core modules:
  * 🛍️ **Shop Module** (`mod_shop`): Integrated lightweight e-commerce module.
  * 📅 **Calendar Module** (`mod_calendar`): Event scheduling and management.
  * 📖 **Glossary & Ads Managers**: Built-in terminology indexation and banner advertising platforms.
  * 👥 **Address Manager**: Store and query contacts/locations efficiently.
  * 📊 **Statistics & SEO Loggers**: Trace page traffic and organic search patterns natively.
* **Comprehensive Localization**: 100% translation parity across 35 European, regional, and Asian language packs with language-specific typographic formatting.
* **Enterprise-Ready Emailing**: Standardized on **PHPMailer 7.1+** supporting modern secure transports including OAuth2 (Google & Microsoft Azure).
* **AI & LLM Integration**: Built-in standard `ai.txt` (AI bot crawling rules and EU TDM Article 4(3) reservation) and `llms.txt` (structured project context for AI models).
* **Docker Ready**: Complete local development environment with PHP 8.2, MariaDB 10.11, phpMyAdmin, Mailpit email capture, and image processing tools.

---

## Server & System Requirements 🖥️

This version is fine-tuned and fully compatible with **PHP 8.2+** (tested on PHP 8.2, 8.3, and 8.4).

### Required Environment
| Requirement | Minimum Version | Recommended Version | Note |
|:---|:---|:---|:---|
| **PHP** | `8.2` | `8.2+` or `8.3` | Native type safety, JIT support, and memory optimizations |
| **MySQL / MariaDB** | `5.5` | `10.4+` (MariaDB) / `8.0+` (MySQL) | InnoDB transactional storage engines and utf8mb4 |
| **Web Server** | Apache (with `mod_rewrite`), Nginx, or IIS | Apache / Nginx | Configuration templates provided (`.htaccess`, `nginx.conf`, `web.config`) |

### PHP Extensions Required
To support all features, the following PHP extensions must be enabled in your environment:
* `intl` (Internationalization & collation)
* `gd` (Image scaling, cropping, and thumbnail generation)
* `iconv` & `mbstring` (Multi-byte character handling & encoding conversion)
* `mysqli` (MySQL database driver)
* `fileinfo` (Safe MIME-type upload validation)
* `xmlreader`, `libxml`, `dom`, `simplexml` (XML/HTML parsing feeds & sitemaps)
* `openssl` (Secure SMTP, cryptographic operations, & external API handshakes)
* `zip` (Archive extraction, backup generation, & asset updates)
* `curl` & `bcmath` (External API requests & payment computations)

---

## Directory Structure Overview 📂

* `/include` — Core CMS logic, extensions (`inc_ext`), language packs (`inc_lang`), modular libraries, update engine, and Composer dependencies (`include/vendor`).
* `/template` — Visual templates, site assets, layouts, frontend render scripts (`inc_script/frontend_render`), and client libraries.
* `/setup` — Automatic browser installation wizard and standalone CLI/web upgrader (`setup/upgrade.php`).
* `/content` — Internal cache, generated images (`content/images`), temporary files (`content/tmp`), and automated backups (`content/backup`).
* `/filearchive` — Secure centralized storage for all uploaded media assets and files.
* `phpwcms.php` & `index.php` — Primary bootstrap entry points routing the administration backend and public frontend.

---

## Installation ⚙️

### Option A: Standard Web Installation

1. **Download & Extract**: Extract the phpwcms release archive into your web server's document root.
2. **Install Dependencies**: If installing directly from Git, install Composer dependencies:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
3. **Set File Permissions**: Ensure the web server has write permissions to:
   * `content/` (and all subfolders: `content/images/`, `content/tmp/`, `content/cache/`, `content/form/`, `content/backup/`)
   * `filearchive/`
   * `include/config/`
   * `template/`
4. **Run the Installer**: Navigate to `https://your-domain.com/setup/` in your browser.
   * The installer will verify PHP requirements and directory permissions.
   * Enter your database credentials (the installer can automatically create the database if it does not yet exist).
   * Configure administrator credentials, default language, timezone, and initial site structure.
5. **Finalize**: Secure your server by removing or blocking access to the `/setup` directory after installation. Configure your web server rewrite rules using the provided `_.htaccess` (rename to `.htaccess`) or `nginx.conf`.

### Option B: Docker Quickstart

A pre-configured development stack is included:

```bash
# Start Apache PHP 8.2, MariaDB 10.11, phpMyAdmin, and Mailpit
docker compose up -d
```

* **phpwcms Web**: `http://localhost:8080` (or run installer at `http://localhost:8080/setup/`)
* **phpMyAdmin**: `http://localhost:8081` (User: `phpwcms`, Password: `password`)
* **Mailpit (Email Capture)**: `http://localhost:8026`

---

## Upgrading Existing Installations 🔄

> [!IMPORTANT]
> **Before upgrading**: Always create a full backup of your database and files. Ensure your host environment runs **PHP 8.2 or higher**.

phpwcms provides three seamless upgrade methods depending on your preferences:

### Method 1: In-App System Update (Recommended for Backend Admins)

If you are already running phpwcms with the self-update engine:
1. Log in to the backend as an administrator.
2. Navigate to **Admin -> System Update** (`phpwcms.php?do=admin&p=update`).
3. Click **Check for Updates** to query the latest release from GitHub.
4. Review the change summary and release notes.
5. Click **Install Update**. The system will:
   * Create an automated pre-update database dump and file backup in `content/backup/`.
   * Enable maintenance mode to prevent traffic inconsistencies.
   * Atomically stage and apply updated files while preserving configuration (`conf.inc.php`), `.htaccess`, `robots.txt`, `filearchive/`, and custom templates.
   * Automatically execute any pending database migrations.
   * Automatically revert via rollback if an error occurs.

### Method 2: Standalone Upgrade Script (`setup/upgrade.php`)

If your `setup/` directory was removed after initial installation (standard security practice) or you are upgrading an older phpwcms installation, you can download `setup/upgrade.php` directly into your existing site.

#### Download the Upgrade Script

Starting from your phpwcms document root (`cd /path/to/phpwcms`), run the following quick command:

```bash
mkdir -p setup && curl -fsSL -o setup/upgrade.php https://raw.githubusercontent.com/slackero/phpwcms/v2-dev/setup/upgrade.php
```

Or run the full bash script with document root verification and fallback support:

```bash
#!/usr/bin/env bash
# Download setup/upgrade.php into a local phpwcms installation
# Usage: ./download-upgrade.sh [branch-or-tag]  (default: v2-dev)
set -euo pipefail

# Verify execution inside phpwcms document root
if [ ! -f "phpwcms.php" ] || [ ! -d "include" ]; then
    echo "Error: Current directory is not a phpwcms document root." >&2
    echo "Please change to your installation directory first: cd /path/to/phpwcms" >&2
    exit 1
fi

mkdir -p setup

BRANCH="${1:-v2-dev}"
UPGRADE_URL="https://raw.githubusercontent.com/slackero/phpwcms/${BRANCH}/setup/upgrade.php"

echo "Downloading setup/upgrade.php from branch '${BRANCH}'..."
if command -v curl >/dev/null 2>&1; then
    curl -fsSL "$UPGRADE_URL" -o setup/upgrade.php
elif command -v wget >/dev/null 2>&1; then
    wget -qO setup/upgrade.php "$UPGRADE_URL"
else
    echo "Error: Neither curl nor wget is available." >&2
    exit 1
fi

chmod 644 setup/upgrade.php
echo "Successfully downloaded setup/upgrade.php."
echo "Run 'php setup/upgrade.php' or open /setup/upgrade.php in your browser."
```

#### Run the Upgrade

The standalone upgrade script can update existing installations from the command line or a browser:

* **Via CLI**:
  ```bash
  php setup/upgrade.php
  ```
  *(Prompts for admin authentication, creates backups, applies file updates, and executes database revisions r401–r559).*

  Supported CLI options:
  * `--reinstall` (or `--force`): Reinstall and overwrite the currently installed version.
  * `--allow-unverified`: Allow unverified legacy package upgrade (phpwcms < 2.0.0 without manifest).

* **Via Browser**:
  Navigate to `https://your-domain.com/setup/upgrade.php`, log in with your admin credentials, and follow the interactive upgrade steps.

### Method 3: Manual Upgrade (Archive Extraction)

1. **Backup**: Back up your database (via mysqldump or phpMyAdmin) and existing file directory.
2. **Extract Files**: Extract the new phpwcms release files, overwriting core files in your web root.
   * **DO NOT overwrite** `include/config/conf.inc.php`.
   * **DO NOT delete** existing files in `filearchive/`, `content/images/`, or `template/`.
3. **Run Migrations**: Run the database migration script via CLI (`php setup/upgrade.php`) or log in to the backend (`phpwcms.php`). The system will detect outdated revisions and run pending database updates automatically.
4. **Clear Cache**: Empty `content/tmp/` and `content/cache/` to ensure new templates and assets take immediate effect.

---

## Developer Workflow 🛠️

A native `Makefile` is included to streamline development, code quality, and testing tasks:

```bash
make help             # Display all available make targets
make phpstan-analyse  # Run PHPStan Level 5 static analysis (100% passing)
make phpstan-update   # Regenerate PHPStan baseline file
make minify           # Compile and minify CSS and JS frontend/backend bundles
make sync             # Synchronize local repository changes to test server
make docker-up        # Start containerized development environment
make docker-down      # Stop containerized development environment
make stacklit-update  # Update codebase architecture maps and documentation
```

---

## FAQ ❓

### How to set up SMTP with Microsoft Azure and XOAUTH2?
You can find the complete step-by-step setup guide for Microsoft Azure and XOAUTH2 integration in the [PHPMailer Wiki Guide](https://github.com/PHPMailer/PHPMailer/wiki/Microsoft-Azure-and-XOAUTH2-setup-guide). To easily generate the required refresh token, run `php bin/get-oauth-token.php --provider=azure`.

### How to set up SMTP with Google and XOAUTH2?
You can find the setup guide for Google accounts and XOAUTH2 authentication in the [Using Gmail with XOAUTH2 PHPMailer Wiki](https://github.com/PHPMailer/PHPMailer/wiki/Using-Gmail-with-XOAUTH2). To easily generate the required refresh token, run `php bin/get-oauth-token.php --provider=google`.

### Where can I find AI crawler and LLM context files?
phpwcms includes standard `/ai.txt` and `/llms.txt` files in the repository root. Distribution templates are also provided as `example.ai.txt` and `example.llms.txt`.

---

## License & Copyright 📄

phpwcms is open-source software released under the **[GNU General Public License v2 (GPL-2.0)](https://opensource.org/licenses/GPL-2.0)**.  
Copyright &copy; 2002&ndash;2026 [Oliver Georgi](https://www.phpwcms.org). All rights reserved.
