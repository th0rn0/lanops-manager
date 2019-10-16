dev:
	docker-compose -f docker-compose.yml up -d --build 

# Debug
interactive:
	docker-compose -f docker-compose.yml up --build

# Stop all Containers
stop:
	docker-compose -f docker-compose.yml stop

# Build from clean
app-build-clean: folder-structure layout-images dev app-build-dep wait database-migrate database-seed generate-key stop ssh-keygen

# Build Dependencies 
app-build-dep: composer-install npm-install

# Build Dev App & Dependencies
app-build-dep-dev: composer-install-dev npm-install-dev

###########
# HELPERS #
###########

# Make .env
env-file:
	cp .env.example src/.env

# Make blank .env 
env-file-blank:
	touch src/.env
	# echo "APP_KEY=" >> src/.env

# Move default images to Storage
layout-images:
	cp -r src/resources/assets/images/* src/storage/app/public/images/main/
	mv src/storage/app/public/images/main/shop/ src/storage/app/public/images/shop/

# Create Symlink for Storage
symlink:
	docker exec lan_manager_app php artisan storage:link

# Create & Update the Database
database-migrate:
	docker exec lan_manager_app php artisan migrate

# Seed the Database
database-seed:
	docker exec lan_manager_app php artisan db:seed

# Rollback last Database Migration
database-rollback:
	docker exec lan_manager_app php artisan db:rollback

# Generate Application key
generate-key:
	docker exec lan_manager_app php artisan key:generate

# Generate Settings - This will erase your current settings!
generate-settings:
	docker exec lan_manager_app php artisan db:seed --class=SettingsTableSeeder

# Create Default Folder structure
folder-structure:
	ln -s src/storage/app/public src/public/storage
	mkdir -p src/storage/app/public/images/gallery/
	mkdir -p src/storage/app/public/images/events/
	mkdir -p src/storage/app/public/images/venues/
	mkdir -p src/storage/app/public/images/main/
	
# Permissions - Dev
permissions:
	chown -R ${USER}:101 src/
	find src -type f -exec chmod 664 {} \;
	find src -type d -exec chmod 775 {} \;
	chgrp -R 101 src/storage src/bootstrap/cache
	chmod -R ug+rwx src/storage src/bootstrap/cache

# Permissions - Docker
permissions-docker:
	chown -R 100:101 src/
	find src -type f -exec chmod 664 {} \;
	find src -type d -exec chmod 775 {} \;
	chgrp -R 101 src/storage src/bootstrap/cache
	chmod -R ug+rwx src/storage src/bootstrap/cache

# Create SSL Keypair for Development
ssh-keygen:
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout resources/certs/lan_manager.key -out resources/certs/lan_manager.crt

# Install PHP Dependencies via Composer
composer-install:
	docker run --rm --name compose-maintainence --interactive \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts

# Install Dev PHP Dependencies via Composer
composer-install-dev:
	docker run --rm --name compose-maintainence-dev --interactive \
    -v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts --dev

# Update Dev PHP Dependencies via Composer
composer-update:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer update --ignore-platform-reqs --no-scripts

# Update Dev PHP Dependencies via Composer - usage make composer-add-dep module=module/namehere
composer-add-dep:
	docker run --rm --name compose-maintainence-update --interactive \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer require $(module) --ignore-platform-reqs --no-scripts

# Install JS Dependencies via NPM
npm-install:
	docker run --rm --name js-maintainence --interactive \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && node_modules/.bin/gulp --production"

# Install Dev JS Dependencies via NPM
npm-install-dev:
	docker run --rm --name js-maintainence-dev --interactive \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && node_modules/.bin/gulp"

# Gulp Runner
gulp:
	docker run --rm --name js-maintainence-dev --interactive \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "node_modules/.bin/gulp"

# Purge Containers
purge-containers:
	docker-compose -f docker-compose.yml -p lan_manager stop
	docker-compose -f docker-compose.yml -p lan_manager rm -vf
	docker rm lan_manager_app
	docker rm lan_manager_database
	docker volume rm lan_manager_db

# Purge Caches
purge-cache:
	sudo rm -rf src/storage/framework/cache/*
	sudo rm -rf src/storage/framework/views/*
	sudo rm -rf src/storage/framework/sessions/*
	sudo rm -rf src/bootstrap/cache/*
	sudo rm -rf src/storage/debugbar/*

# Wait for containers to initialize
wait:
	sleep 30

###############
# DANGER ZONE #
###############
# Clean ALL! DANGEROUS!
purge-all: stop purge-containers purge-cache
	echo 'This is dangerous!'
	echo 'This will totally remove all data and information stored in your app!'
	echo 'do you want to continue? (Y/N)'
	
	sudo rm -rf src/vendor/
	sudo rm -rf src/node_modules/
	sudo rm -rf src/public/css/*
	sudo rm -rf src/storage/app/public/images/gallery
	sudo rm -rf src/storage/app/public/images/events
	sudo rm -rf src/storage/app/public/images/venues
	sudo rm -rf src/storage/app/public/images/main
	sudo rm -rf src/storage/logs/*
	sudo rm src/public/storage

.ONESHELL: