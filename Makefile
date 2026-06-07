# Makefile for phpwcms-dev development tasks

.PHONY: phpstan-analyse phpstan-update stacklit-update check-php help

# Default PHP executable (local php is the default)
# You can override this on the command line if your local php version is incompatible,
# e.g., make phpstan-analyse PHP=/path/to/php8/bin/php
PHP ?= php
PHPSTAN = $(PHP) include/vendor/bin/phpstan
STACKLIT ?= stacklit

# Check PHP version compatibility (PHP >= 8.2 required)
PHP_VERSION_CHECK := $(shell $(PHP) -r "exit(PHP_VERSION_ID >= 80200 ? 0 : 1);" 2>/dev/null && echo "OK" || echo "FAIL")

# Default target
all: help

help:
	@echo "Available commands:"
	@echo "  make phpstan-analyse - Run phpstan analysis"
	@echo "  make phpstan-update  - Update phpstan baseline file"
	@echo "  make stacklit-update - Update stacklit index and CLAUDE.md map"

check-php:
ifeq ($(PHP_VERSION_CHECK),FAIL)
	@echo "Error: PHP >= 8.2 is required to run PHPStan."
	@echo "The current PHP executable ($(PHP)) is incompatible."
	@echo "Please override it on the command line, for example:"
	@echo "  make phpstan-analyse PHP=/path/to/php8/bin/php"
	@exit 1
endif

phpstan-analyse: check-php
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon --memory-limit=2G

phpstan-update: check-php
	$(PHPSTAN) analyse -c .phpstan/phpstan.neon --generate-baseline=.phpstan/phpstan-baseline.neon --memory-limit=2G

stacklit-update:
	$(STACKLIT) generate
	$(STACKLIT) derive --inject claude
