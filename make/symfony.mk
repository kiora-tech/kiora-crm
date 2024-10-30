PHP ?= php

##@ Symfony
install_symfony: vendor update_symfony ## install symfony
	@if ls src/DataFixtures/*.php 1> /dev/null 2>&1; then ${PHP} bin/console doctrine:fixtures:load --no-interaction; fi

update_symfony: up ## update symfony
	${PHP} bin/console doctrine:database:create --if-not-exists
	@if ls migrations/*.php 1> /dev/null 2>&1; then ${PHP} bin/console doctrine:migrations:migrate --no-interaction; fi

reset-db:
	$(PHP) bin/console doctrine:database:drop --force
	$(PHP) bin/console doctrine:database:create

load-external-db: reset-db backup.sql restore-db ## Load database from external server

ready: up vendor update_symfony
	${PHP} rm -rf public/assets/
	${PHP} bin/console sass:build
	${PHP} bin/console asset-map:compile


vendor: composer.lock
	${PHP} composer install

composer.lock:
	${PHP} composer update

.PHONY: install_symfony load-external-db
