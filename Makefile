# Makefile for phpwcms-v2.0 development tasks

.PHONY: phpstan baseline stacklit help sync minify-sync changed-archive

# Default PHP executable (check for php8 in PATH, fallback to MAMP php8.4.27)
PHP ?= $(shell which php8 2>/dev/null || echo /Applications/MAMP/bin/php/php8.4.27/bin/php)
PHPSTAN = $(PHP) -d memory_limit=1G include/vendor/bin/phpstan
STACKLIT ?= stacklit
SYNC_TARGET ?= /Users/slackero/Sites/dev-phpwcms
# Archive of files changed since FROM (tag or commit hash, compared against HEAD)
FROM ?=
ARCHIVE_FORMAT ?= tar.gz
ARCHIVE_OUT ?= phpwcms-$(subst /,-,$(FROM))-to-HEAD.$(ARCHIVE_FORMAT)

# Default target
all: help

help:
	@echo "Available commands:"
	@echo "  make sync            - Sync repository changes to local test web server"
	@echo "  make minify-sync     - Minify all CSS/JS assets and sync to local test web server"
	@echo "  make changed-archive - Archive files changed between FROM tag/commit and HEAD (FROM=<ref>)"
	@echo "  make css-minify      - Build and minify backend.min.css"
	@echo "  make js-minify       - Minify phpwcms.js to phpwcms.min.js"
	@echo "  make minify          - Build all minified CSS and JS assets"
	@echo "  make phpstan-analyse - Run phpstan analysis"
	@echo "  make phpstan-update  - Update phpstan baseline file"
	@echo "  make stacklit-update - Update stacklit index and CLAUDE.md map"
	@echo "  make docker-up       - Start Docker testing containers (web, db, phpmyadmin)"
	@echo "  make docker-down     - Stop Docker testing containers"
	@echo "  make docker-build    - Build Docker web container image"
	@echo "  make docker-logs     - Follow Docker container logs"
	@echo "  make docker-shell    - Open bash shell inside web container"

sync:
	git ls-files -z --cached --others --exclude-standard | rsync -av --files-from=- ./ $(SYNC_TARGET)/

minify-sync: minify sync

# Archive files changed between FROM (tag/commit) and HEAD, content taken from HEAD
#   make changed-archive FROM=v2.0.0-beta.2
#   make changed-archive FROM=598ec8e59 ARCHIVE_FORMAT=tar.gz
changed-archive:
	@test -n "$(FROM)" || { echo "Usage: make changed-archive FROM=<tag-or-commit>"; exit 1; }
	@files=$$(git diff --name-only --diff-filter=ACMR $(FROM) HEAD) || exit 1; \
	test -n "$$files" || { echo "No changed files between $(FROM) and HEAD"; exit 1; }
	@tmp=$$(mktemp -d); trap 'rm -rf "$$tmp"' EXIT; \
	git diff -z --name-only --diff-filter=ACMR $(FROM) HEAD | \
		git --work-tree="$$tmp" checkout-index -f -z --stdin || exit 1; \
	if [ "$(ARCHIVE_FORMAT)" = "zip" ]; then \
		git diff --name-only --diff-filter=ACMR $(FROM) HEAD | \
			(cd "$$tmp" && zip -q "$(CURDIR)/$(ARCHIVE_OUT)" -@); \
	else \
		git diff -z --name-only --diff-filter=ACMR $(FROM) HEAD | \
			tar -czf "$(ARCHIVE_OUT)" -C "$$tmp" --null -T -; \
	fi
	@echo "Created $(ARCHIVE_OUT)"

css-minify:
	npm run build:css

css-minify-dev:
	npm run build:css:dev

js-minify:
	npm run build:js

minify:
	npm run build

phpstan-analyse:
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon

phpstan-update:
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon --generate-baseline=.phpstan/phpstan-baseline.neon

stacklit-update:
	$(STACKLIT) generate
	$(STACKLIT) derive --inject claude

docker-up:
	docker compose up -d

docker-down:
	docker compose down

docker-build:
	docker compose build

docker-logs:
	docker compose logs -f

docker-shell:
	docker compose exec web bash
