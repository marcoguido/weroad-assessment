########################################################################################################################
### Loading main ENV file
########################################################################################################################
include .env
export $(shell sed 's/=.*//' .env)

########################################################################################################################
### Makefile tasks
########################################################################################################################

build:
	@docker run --rm \
           -u "$(id -u):$(id -g)" \
           -v .:/var/www/html \
           -w /var/www/html \
           serversideup/php:8.2-cli \
           composer install --ignore-platform-reqs
	@docker compose \
    		-f docker/docker-compose.yml \
    		-f docker/docker-compose.$(APP_ENV).yml \
    		build --no-cache

run-fresh-migrations:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan migrate:fresh'

run-migrations:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan migrate'

run-tinker:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan tinker'

do-composer-update:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'composer update'

api-docs:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'composer generate-api-docs'

up-d:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		up -d

up:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		up

down:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		down

tty:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash

reset-db:
	@echo "Turning down your running environment (if any).."
	@make down
	@echo "Removing database files.."
	@cd docker/database/mariadb/volume/ \
		&& find . -not \( -name '.' -or -name '.gitkeep' \) -exec rm -rf {} + \
		&& cd -
	@echo "Database successfully removed!"
