# Makefile for phpwcms-v2.0 development tasks

.PHONY: phpstan baseline stacklit help sync minify-sync

# Default PHP executable (check for php8 in PATH, fallback to MAMP php8.2.32)
PHP ?= $(shell which php8 2>/dev/null || echo /Applications/MAMP/bin/php/php8.2.32/bin/php)
PHPSTAN = $(PHP) -d memory_limit=1G include/vendor/bin/phpstan
STACKLIT ?= stacklit
SYNC_TARGET ?= /Users/slackero/Sites/dev-phpwcms

# Default target
all: help

help:
	@echo "Available commands:"
	@echo "  make sync            - Sync repository changes to local test web server"
	@echo "  make minify-sync     - Minify all CSS/JS assets and sync to local test web server"
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
