# Makefile for phpwcms-v2.0 development tasks

.PHONY: phpstan baseline stacklit help

# Default PHP executable (check for php8 in PATH, fallback to MAMP php8.2.32)
PHP ?= $(shell which php8 2>/dev/null || echo /Applications/MAMP/bin/php/php8.2.32/bin/php)
PHPSTAN = $(PHP) -d memory_limit=1G include/vendor/bin/phpstan
STACKLIT ?= stacklit
CSSO ?= $(shell which csso 2>/dev/null || echo npx csso)
TERSER ?= $(shell which terser 2>/dev/null || echo npx terser)

# Default target
all: help

help:
	@echo "Available commands:"
	@echo "  make css-minify      - Build and minify backend.min.css"
	@echo "  make js-minify       - Minify phpwcms.js to phpwcms.min.js"
	@echo "  make minify          - Build all minified CSS and JS assets"
	@echo "  make phpstan-analyse - Run phpstan analysis"
	@echo "  make phpstan-update  - Update phpstan baseline file"
	@echo "  make stacklit-update - Update stacklit index and CLAUDE.md map"

css-minify:
	npm run build:css

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
