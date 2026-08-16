# Rules
- Always use `/Applications/MAMP/bin/php/php8.2.32/bin/php` instead of `php` or `php8` for all CLI commands in this project.
- Always use double quotes for HTML tag attributes.
- Always use single quotes for PHP strings unless variable interpolation is required.
- NEVER use `--delete` with `rsync` when syncing to the web server. Existing files in `/filearchive/`, `content/images/`, and `content/tmp/` on the web server must NEVER be deleted. Copying and overwriting with new repository files is allowed.

