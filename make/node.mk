JS_DEP ?= yarn

##@ Node
.PHONY: install
install_node: node_modules ## Install dependencies

.PHONY: watch_assets
watch_assets: ## Watch assets
	$(JS_DEP) watch

node_modules: yarn.lock
	$(JS_DEP) install

yarn.lock: package.json
	$(JS_DEP) install

build: public/build ## Build assets

public/build: node_modules assets/* assets/*/*
	$(JS_DEP) build

.PHONY: node_dep
node_dep: ## add node dependencies DEV='--dev' make node_dep PKG=file-loader@^6.0.0
ifndef PKG
	$(error "PKG is undefined. Usage: make node_dep PKG=<package_name>@<version>")
endif
	$(JS_DEP) add $(PKG) $(DEV)
