include make/*.mk
.DEFAULT_GOAL:=help

DOCKER_IMAGE_PREFIX=registry.kiora.tech/kiora/kiora-crm_

update: init vendor update_symfony build test-unit

build_app: ## build the app pour prod (sans modifier votre environnement local) - make build_app TAG=ton_tag
ifndef TAG
	$(error Vous devez spécifier une version avec 'make build_app TAG=ton_tag')
endif
	@echo "Lancement du script de build pour la production..."
	@chmod +x ./build.sh
	./build.sh $(TAG)