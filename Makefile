SHELL = /bin/bash
.DEFAULT_GOAL := help
HERE := $(dir $(realpath $(firstword $(MAKEFILE_LIST))))
TEST_SERVER_REDIRECT ?=

# https://mwop.net/blog/2023-12-11-advent-makefile.html
##@ Help
help:  ## Display this help
	@awk 'BEGIN {FS = ":.*##"; printf "\nUsage:\n  make \033[36m<target>\033[0m\n"} /^[0-9a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

.PHONY: all
all: style lint analyze tests ## Do everything

# ### #

.PHONY: style
style: ## Fix any style issues
	@echo
	@echo "--> Style: php-cs-fixer"
	vendor/bin/php-cs-fixer fix -v
	@echo

.PHONY: lint
lint: ## Check if the code is valid
	@echo
	@echo "--> Lint"
	php -l src/jeph/frame.php

	php -l tests/*.php

	php -l demo/index.php
	php -l demo/routes/name.php
	@echo

.PHONY: analyze
analyze: ## Static analysis
	@echo
	@echo "--> PHPStan"
	vendor/bin/phpstan analyse
	@echo

.PHONY: tests
tests: TEST_SERVER_REDIRECT=>/dev/null 2>&1
tests: test-server-start ## Run tests against local PHP built-in server
	@echo
	@echo "--> Tests: Pest"
	@echo
	@./vendor/bin/pest; \
	status=$$?; \
	$(MAKE) test-server-stop; \
	exit $$status

# ### #

.PHONY: test-server-start
test-server-start: ## PHP server for tests
	@echo
	@echo "--> Test Server: start"
	@echo
	php -S 127.0.0.1:9191 -t demo/ $(TEST_SERVER_REDIRECT) &

.PHONY: test-server-stop
test-server-stop: ## PHP server for tests
	@echo
	@echo "--> Test Server: stop"
	@echo
	kill $(shell pgrep -f 'php -S 127.0.0.1:9191 -t demo/')
