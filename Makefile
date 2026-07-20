# Makefile for phpwcms-v2.0 development tasks

.PHONY: phpstan baseline stacklit help

# Default PHP executable (check for php8 in PATH, fallback to MAMP php8.2.31)
PHP ?= $(shell which php8 2>/dev/null || echo /Applications/MAMP/bin/php/php8.2.31/bin/php)
PHPSTAN = $(PHP) -d memory_limit=1G include/vendor/bin/phpstan
STACKLIT ?= stacklit

# Default target
all: help

help:
	@echo "Available commands:"
	@echo "  make phpstan-analyse - Run phpstan analysis"
	@echo "  make phpstan-update  - Update phpstan baseline file"
	@echo "  make stacklit-update - Update stacklit index and CLAUDE.md map"

phpstan-analyse:
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon

phpstan-update:
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon --generate-baseline=.phpstan/phpstan-baseline.neon

stacklit-update:
	$(STACKLIT) generate
	$(STACKLIT) derive --inject claude
