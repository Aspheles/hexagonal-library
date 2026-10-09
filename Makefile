export UID := $(shell id -u)
export GID := $(shell id -g)

COMPOSE = docker compose
PHP     = $(COMPOSE) exec php
CONSOLE = $(PHP) bin/console

.DEFAULT_GOAL := help

help: ## List the available commands
	@grep -E '^[a-z-]+:.*## ' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*## "}; {printf "  %-12s %s\n", $$1, $$2}'

up: ## Build and start the containers
	$(COMPOSE) up -d --build

down: ## Stop the containers
	$(COMPOSE) down

logs: ## Follow the container logs
	$(COMPOSE) logs -f

sh: ## Open a shell in the PHP container
	$(PHP) sh

install: ## Install Composer dependencies
	$(PHP) composer install

db: ## Create the database and run migrations
	$(CONSOLE) doctrine:database:create --if-not-exists
	$(CONSOLE) doctrine:migrations:migrate --no-interaction --allow-no-migration

test: ## Run the test suite
	$(PHP) bin/phpunit

cc: ## Clear the Symfony cache
	$(CONSOLE) cache:clear
