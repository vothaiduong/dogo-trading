# Dogo Corporation — local dev workflow.
# Usage: `make help`

SHELL := /bin/bash
COMPOSE ?= docker compose
WPCLI = $(COMPOSE) run --rm wpcli

# Load .env if present so commands pick up overrides.
ifneq (,$(wildcard ./.env))
include .env
export
endif

WP_URL ?= http://localhost:8080
WP_TITLE ?= Dogo Corporation
WP_ADMIN_USER ?= admin
WP_ADMIN_PASSWORD ?= admin
WP_ADMIN_EMAIL ?= admin@example.com

.PHONY: help up down restart logs install reset wp shell mysql package deploy deploy-files deploy-images

help: ## Show available targets
	@grep -E '^[a-zA-Z_-]+:.*?## ' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS=":.*?## "}; {printf "  \033[36m%-18s\033[0m %s\n", $$1, $$2}'

up: ## Start the local stack (WP on $(WP_URL))
	@cp -n .env.example .env 2>/dev/null || true
	$(COMPOSE) up -d
	@echo ""
	@echo "  WordPress  -> $(WP_URL)"
	@echo "  phpMyAdmin -> http://localhost:$${PMA_PORT:-8081}"
	@echo ""
	@echo "  Next:  make install"

down: ## Stop and remove containers (volumes preserved)
	$(COMPOSE) down

restart: ## Restart WP container
	$(COMPOSE) restart wordpress

logs: ## Tail WordPress + DB logs
	$(COMPOSE) logs -f wordpress db

install: ## Bootstrap WordPress: install core, activate theme, install Polylang, seed pages
	@echo "==> Installing WordPress core…"
	$(WPCLI) core install \
		--url="$(WP_URL)" \
		--title="$(WP_TITLE)" \
		--admin_user="$(WP_ADMIN_USER)" \
		--admin_password="$(WP_ADMIN_PASSWORD)" \
		--admin_email="$(WP_ADMIN_EMAIL)" \
		--skip-email
	@echo "==> Activating dogo-corporation theme…"
	$(WPCLI) theme activate dogo-corporation
	@echo "==> Setting permalinks to /%postname%/…"
	$(WPCLI) rewrite structure '/%postname%/'
	$(WPCLI) rewrite flush
	@echo "==> Installing language packs (vi, ja)…"
	-$(WPCLI) language core install vi
	-$(WPCLI) language core install ja
	@echo "==> Installing Polylang for multilingual content…"
	-$(WPCLI) plugin install polylang --activate
	@echo "==> Creating front page…"
	-$(WPCLI) post create --post_type=page --post_title='Home' --post_status=publish --porcelain > /tmp/home_id 2>/dev/null || true
	@HOME_ID=$$($(WPCLI) post list --post_type=page --name=home --field=ID 2>/dev/null | tr -d '\r'); \
	if [ -n "$$HOME_ID" ]; then \
		$(WPCLI) option update show_on_front 'page'; \
		$(WPCLI) option update page_on_front "$$HOME_ID"; \
		echo "  Set Home page (ID $$HOME_ID) as front page."; \
	fi
	@echo ""
	@echo "  Done. Open: $(WP_URL)"
	@echo "  Admin:     $(WP_URL)/wp-admin  ($(WP_ADMIN_USER) / $(WP_ADMIN_PASSWORD))"

reset: ## DESTROY local DB + WP files (keeps theme source)
	@read -p "This will wipe local DB + WP files. Type 'yes' to confirm: " ans; \
	if [ "$$ans" = "yes" ]; then \
		$(COMPOSE) down -v; \
		echo "  Volumes removed. Run: make up && make install"; \
	else \
		echo "  Aborted."; \
	fi

wp: ## Run an arbitrary wp-cli command:  make wp ARGS="plugin list"
	$(WPCLI) $(ARGS)

shell: ## Open a bash shell in the WordPress container
	$(COMPOSE) exec wordpress bash

mysql: ## MySQL prompt as root
	$(COMPOSE) exec db mariadb -uroot -p$${DB_ROOT_PASSWORD:-rootpw} $${DB_NAME:-dogo}

package: ## Build a deployable theme zip in dist/
	@mkdir -p dist
	@rm -f dist/dogo-corporation.zip
	@cd wp-content/themes && zip -qr "../../dist/dogo-corporation.zip" dogo-corporation \
		-x 'dogo-corporation/.git*' 'dogo-corporation/node_modules/*'
	@echo "  -> dist/dogo-corporation.zip"

deploy: ## Provision (first time) + deploy theme to PROD_HOST
	bash deploy/deploy.sh

deploy-files: ## Rsync theme files only to PROD_HOST (skip provisioning)
	bash deploy/deploy.sh --files-only

deploy-images: ## Sync local uploads/ to production
	bash deploy/deploy.sh --uploads-only
