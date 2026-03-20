# VERSION defines the version for the docker containers.
# To build a specific set of containers with a version,
# you can use the VERSION as an arg of the docker build command (e.g make docker VERSION=0.0.2)
VERSION ?= latest

# REGISTRY defines the registry where we store our images.
# To push to a specific registry,
# you can use the REGISTRY as an arg of the docker build command (e.g make docker REGISTRY=my_registry.com/username)
# You may also change the default value if you are using a different registry as a default
REGISTRY ?= registry.rickokkersen.nl/dtcdashboard

# PLATFORMS defines the target platforms for multi-platform builds
PLATFORMS ?= linux/amd64,linux/arm64/v8

# Define the no-cache flag variable
NO_CACHE_FLAG=

# Check if the no-cache flag is set
ifeq ($(no-cache), true)
    NO_CACHE_FLAG=--no-cache
endif


dep: docker deploy

# Commands
deploy: 
	kubectl rollout restart deployment dtcdashboard --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-php-fpm --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-nginx --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-workers --namespace=laravel-applications

deploy-test: 
	kubectl rollout restart deployment dtcdashboard-test --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-test-php-fpm --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-test-nginx --namespace=laravel-applications
	kubectl rollout restart deployment dtcdashboard-test-workers --namespace=laravel-applications

# Setup buildx builder for multi-platform builds
docker-buildx-setup:
	@echo "Setting up Docker buildx for multi-platform builds..."
	docker buildx create --name dtc-builder --driver docker-container 2>nul || echo Builder exists
	docker buildx use dtc-builder
	@echo "Docker buildx ready for platforms: ${PLATFORMS}"

# Multi-platform build and push (default make docker command)
docker: docker-buildx-setup
	@echo "=========================================="
	@echo "Building multi-platform Docker images..."
	@echo "=========================================="
	@echo Registry: ${REGISTRY}
	@echo Version: ${VERSION}
	@echo Platforms: ${PLATFORMS}
	docker buildx build . $(NO_CACHE_FLAG) --platform ${PLATFORMS} --target cli --push -t ${REGISTRY}/cli:${VERSION}
	docker buildx build . $(NO_CACHE_FLAG) --platform ${PLATFORMS} --target fpm_server --push -t ${REGISTRY}/fpm_server:${VERSION}
	docker buildx build . $(NO_CACHE_FLAG) --platform ${PLATFORMS} --target web_server --push -t ${REGISTRY}/web_server:${VERSION}
	@echo "=========================================="
	@echo "Multi-platform build completed successfully!"
	@echo "=========================================="

# Single-platform build for local testing (native arch only, no push)
docker-local: docker-buildx-setup
	@echo "Building single-platform images for local testing..."
	@echo "(Native platform only, not pushed to registry)"
	docker buildx build . $(NO_CACHE_FLAG) --target cli --load -t ${REGISTRY}/cli:${VERSION}-local
	docker buildx build . $(NO_CACHE_FLAG) --target fpm_server --load -t ${REGISTRY}/fpm_server:${VERSION}-local
	docker buildx build . $(NO_CACHE_FLAG) --target web_server --load -t ${REGISTRY}/web_server:${VERSION}-local
	@echo "Local images built (not pushed):"
	@echo "  - ${REGISTRY}/cli:${VERSION}-local"
	@echo "  - ${REGISTRY}/fpm_server:${VERSION}-local"
	@echo "  - ${REGISTRY}/web_server:${VERSION}-local"