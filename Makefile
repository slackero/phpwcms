# Makefile for cmsgo-v2.0 development tasks

.PHONY: phpstan baseline stacklit help

# Default PHP executable from MAMP (can be overridden, e.g., make phpstan PHP=php)
PHP ?= php8
PHPSTAN = $(PHP) include/vendor/bin/phpstan
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
