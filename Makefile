########################################################################################################################
### Loading main ENV file
########################################################################################################################
include .env
export $(shell sed 's/=.*//' .env)

########################################################################################################################
### Makefile tasks
########################################################################################################################

# Main application build entrypoint
build:
	@docker run --rm \
           -u "$(id -u):$(id -g)" \
           -v .:/var/www/html \
           -w /var/www/html \
           serversideup/php:8.2-cli \
           composer install --ignore-platform-reqs \
           && php artisan key:generate
	@docker compose \
    		-f docker/docker-compose.yml \
    		-f docker/docker-compose.$(APP_ENV).yml \
    		build --no-cache

# Resets application database, feeding it with default data
run-fresh-migrations:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan migrate:fresh --seed'

# Runs all new migrations
run-migrations:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan migrate'

# Spawns a new Tinker session
run-tinker:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan tinker'

# Updates all composer dependencies
do-composer-update:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'composer update'

# Generates a new OpenApi specs file
api-docs:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'composer generate-api-docs'

# Generates a new user with administrative privileges
admin-user:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash -ci 'php artisan make:admin-user'

# Boots up the application as daemon
up-d:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		up -d

# Boots up the application
up:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		up

# Shutdown the whole application
down:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		down

# Connects to main container shell in order to run commands
tty:
	@docker compose \
		-f docker/docker-compose.yml \
		-f docker/docker-compose.$(APP_ENV).yml \
		exec php bash

# Destroys database container volume
reset-db:
	@echo "Turning down your running environment (if any).."
	@make down
	@echo "Removing database files.."
	@cd docker/database/mariadb/volume/ \
		&& find . -not \( -name '.' -or -name '.gitkeep' \) -exec rm -rf {} + \
		&& cd -
	@echo "Database successfully removed!"
