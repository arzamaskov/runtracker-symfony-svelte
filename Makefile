# Makefile for RUNTRACKER
.PHONY: help build up down restart logs shell composer pnpm console migrate test lint stan

# Output colors
GREEN  := \033[0;32m
YELLOW := \033[0;33m
RED    := \033[0;31m
NC     := \033[0m

# User for executing commands inside the container
USER_ID ?= 1000
GROUP_ID ?= 1000
EXEC_USER := -u $(USER_ID):$(GROUP_ID)

# Docker Compose commands
DC := docker compose
DC_EXEC := $(DC) exec $(EXEC_USER)
DC_EXEC_ROOT := $(DC) exec -u root

help: ## Show this help
	@echo "$(GREEN)Available commands:$(NC)"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  $(YELLOW)%-20s$(NC) %s\n", $$1, $$2}'

# ============================================
# Docker commands
# ============================================
build: ## Build Docker images
	$(DC) build --no-cache

up: ## Run all containers
	$(DC) up -d

down: ## Stop all containers
	$(DC) down

restart: ## Restart all containers
	$(DC) restart

ps: ## List containers
	$(DC) ps

logs: ## Show logs of all containers
	$(DC) logs -f

stats: ## Show resource usage statistics
	docker stats

# ============================================
# Shell
# ============================================

shell: ## Attach to the PHP-FPM container
	$(DC_EXEC) php-fpm sh

shell-root: ## Attach to the PHP-FPM container as root
	$(DC_EXEC_ROOT) php-fpm sh

shell-frontend: ## Attach to the frontend container
	$(DC) exec frontend sh

# ============================================
# Database (PostgreSQL)
# ============================================

psql: ## Connect to the PostgreSQL console
	$(DC) exec postgres psql -U app -d runtracker

db-dump: ## Create a database dump
	@mkdir -p ./backups
	@echo "$(GREEN)Creating database dump...$(NC)"
	$(DC) exec -T postgres pg_dump -U app runtracker | gzip > ./backups/backup_$(shell date +%Y%m%d_%H%M%S).sql.gz
	@echo "$(GREEN)✅ Dump created in ./backups/$(NC)"

db-restore: ## Restore the database from a dump (make db-restore FILE=backup.sql.gz)
	@if [ -z "$(FILE)" ]; then \
		echo "$(YELLOW)⚠️  Specify the file: make db-restore FILE=backup.sql.gz$(NC)"; \
		exit 1; \
	fi
	@echo "$(YELLOW)⚠️  WARNING: Current database data will be replaced!$(NC)"
	@echo "$(YELLOW)Press Ctrl+C to cancel or Enter to continue...$(NC)"
	@read confirm
	@echo "$(GREEN)Restoring database from $(FILE)...$(NC)"
	@gunzip < $(FILE) | $(DC) exec -T postgres psql -U app -d runtracker
	@echo "$(GREEN)✅ Database restored$(NC)"

db-reset: ## ⚠️  Recreate the database (DELETES ALL DATA!)
	@echo "$(RED)⚠️  WARNING: This will delete ALL data in the database!$(NC)"
	@echo "$(YELLOW)Press Ctrl+C to cancel or Enter to continue...$(NC)"
	@read confirm
	$(DC) exec postgres psql -U app -d postgres -c "DROP DATABASE IF EXISTS runtracker;"
	$(DC) exec postgres psql -U app -d postgres -c "CREATE DATABASE runtracker;"
	@echo "$(GREEN)✅ Database recreated$(NC)"

# ============================================
# Redis
# ============================================

redis-cli: ## Connect to the Redis console
	$(DC) exec redis redis-cli

redis-flush: ## Flush all Redis data
	$(DC) exec redis redis-cli FLUSHALL

redis-info: ## Show Redis information
	$(DC) exec redis redis-cli INFO

# ============================================
# PHP/Composer
# ============================================

composer: ## Run a Composer command (make composer cmd="install")
	$(DC_EXEC) php-fpm composer $(cmd)

composer-require: ## Require a package (make composer-require pkg="vendor/package")
	$(DC_EXEC) php-fpm composer require $(pkg)

# ============================================
# Symfony Console
# ============================================

console: ## Run a Symfony command (make console cmd="about")
	$(DC_EXEC) php-fpm php bin/console $(cmd)

cc: ## Clear Symfony cache
	$(DC_EXEC) php-fpm php bin/console cache:clear

cache-warmup: ## Warm up Symfony cache
	$(DC_EXEC) php-fpm php bin/console cache:warmup

debug-router: ## Show the list of routes
	$(DC_EXEC) php-fpm php bin/console debug:router

debug-container: ## Show the list of services
	$(DC_EXEC) php-fpm php bin/console debug:container

# ============================================
# Doctrine / Migrations
# ============================================

migrate: ## Run database Migrations
	$(DC_EXEC) php-fpm php bin/console doctrine:migrations:migrate --no-interaction

migrate-diff: ## Generate a new migration from schema changes
	$(DC_EXEC) php-fpm php bin/console doctrine:migrations:diff

migrate-status: ## Show Migrations status
	$(DC_EXEC) php-fpm php bin/console doctrine:migrations:status

migrate-rollback: ## Roll back the last migration
	$(DC_EXEC) php-fpm php bin/console doctrine:migrations:migrate prev --no-interaction

schema-update: ## Update the database schema (dev only!)
	$(DC_EXEC) php-fpm php bin/console doctrine:schema:update --force

schema-validate: ## Validate the database schema
	$(DC_EXEC) php-fpm php bin/console doctrine:schema:validate

fixtures: ## Load fixtures (test data)
	$(DC_EXEC) php-fpm php bin/console doctrine:fixtures:load --no-interaction

# ============================================
# Frontend (pnpm)
# ============================================

pnpm: ## Run a pnpm command (make pnpm CMD="install")
	$(DC) exec frontend pnpm $(CMD)

# ============================================
# Testing
# ============================================

test: ## Run all tests
	$(DC_EXEC) php-fpm php bin/phpunit

test-coverage: ## Run tests with coverage
	$(DC_EXEC) php-fpm php bin/phpunit --coverage-html coverage

test-filter: ## Run a specific test (make test-filter filter="TestName")
	$(DC_EXEC) php-fpm php bin/phpunit --filter=$(filter)

# ============================================
# Code Quality
# ============================================

lint: ## Check code style (PHP CS Fixer, dry-run)
	$(DC_EXEC) php-fpm ./vendor/bin/php-cs-fixer fix --dry-run --diff --verbose

lint-fix: ## Automatically fix code style issues (PHP CS Fixer)
	$(DC_EXEC) php-fpm ./vendor/bin/php-cs-fixer fix

lint-js: ## Check frontend code style (ESLint)
	$(DC) exec -T frontend pnpm run lint

deptrac: ## Analyze architectural dependencies (Deptrac)
	$(DC_EXEC) php-fpm ./vendor/bin/deptrac --config-file=deptrac.yml

stan: ## Run static code analysis (PHPStan)
	$(DC_EXEC) php-fpm ./vendor/bin/phpstan analyse --memory-limit=2G

ci: lint lint-js deptrac stan test ## Local pre-push check combo

# ============================================
# Information
# ============================================

volumes: ## Show volumes information
	@echo "$(GREEN)Project Docker volumes:$(NC)"
	@docker volume ls | grep runtracker || echo "No volumes found"

info: ## Show project information
	@echo "$(GREEN)╔════════════════════════════════════════╗$(NC)"
	@echo "$(GREEN)║       RUNTRACKER - Information         ║$(NC)"
	@echo "$(GREEN)╚════════════════════════════════════════╝$(NC)"
	@echo ""
	@echo "$(GREEN)User:$(NC)"
	@echo "  USER_ID:  $(USER_ID)"
	@echo "  GROUP_ID: $(GROUP_ID)"
	@echo ""
	@echo "$(GREEN)Versions:$(NC)"
	@$(DC_EXEC) php-fpm php -v | head -n 1 || echo "PHP not available"
	@$(DC_EXEC) php-fpm composer --version 2>/dev/null || echo "Composer not available"
	@$(DC) exec frontend node -v 2>/dev/null || echo "Node not available"
	@$(DC) exec frontend pnpm -v 2>/dev/null || echo "pnpm not available"
	@echo ""
	@echo "$(GREEN)Symfony:$(NC)"
	@$(DC_EXEC) php-fpm php bin/console --version 2>/dev/null || echo "Symfony not installed"
	@echo ""
	@echo "$(GREEN)Access:$(NC)"
	@echo "  Frontend:   http://localhost (nginx) / http://localhost:5173 (vite)"
	@echo "  Mailpit:    http://localhost:8025"
	@echo "  PostgreSQL: localhost:5432 (user: app, db: runtracker)"
	@echo "  Redis:      localhost:6379"
	@echo ""
	@echo "$(YELLOW)Use 'make help' to list available commands$(NC)"
