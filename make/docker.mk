DOCKER_CMD = HOST_USER_ID=$$(id -u) HOST_GROUP_ID=$$(id -g) docker
PHP = docker compose exec -it php
DOCKER_COMPOSE_CMD = ${DOCKER_CMD} compose --env-file .env.local
JS_DEP = docker compose run --rm node yarn

DOCKERFILES_FOLDER ?= docker
DOCKERFILES ?= $(wildcard $(DOCKERFILES_FOLDER)/*/Dockerfile)
DOCKER_SENTINELS=$(addprefix make/., $(addsuffix .sentinel, $(notdir $(patsubst docker/%/Dockerfile,%,$(DOCKERFILES)))))
##@ Docker
init: ${DOCKER_SENTINELS} compose.override.yaml ## init docker
	${DOCKER_CMD} compose up -d

compose.override.yaml: compose.override.yaml.dist
	cp compose.override.yaml.dist compose.override.yaml

$(DOCKER_SENTINELS): make/.%.sentinel : $(DOCKERFILES_FOLDER)/%/Dockerfile
	${DOCKER_CMD} compose build
	touch $@

up: init ## run docker

php: up ## run php container
	${DOCKER_CMD} compose exec php bash

node: up ## run node container
	${DOCKER_CMD} compose run --rm node bash

.PHONY: init up php

build: .env.local ## build docker
	@rm -f $(DOCKER_SENTINELS)
	${DOCKER_COMPOSE_CMD} build
	@touch $(DOCKER_SENTINELS)

docker_publish: ## Publish a Docker image (usage: make docker_publish IMAGE=nom_image TAG=version)
ifndef IMAGE
	$(error Vous devez spécifier une image avec 'make docker_publish IMAGE=ton_image [TAG=ton_tag]')
endif
	@if [ ! -d "docker/$(IMAGE)" ]; then \
		echo "Le répertoire docker/$(IMAGE) n'existe pas. Opération annulée."; \
		exit 1; \
	fi
	@echo "Construction de l'image Docker $(IMAGE) avec le tag $(TAG)..."
	${DOCKER_CMD} buildx build --target build --platform linux/arm/v7,linux/arm64,linux/amd64 -t ${DOCKER_IMAGE_PREFIX}$(IMAGE)_base:$(TAG) -f docker/$(IMAGE)/Dockerfile --push .
	@echo "Image Docker $(IMAGE) publiée avec succès."
