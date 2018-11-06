live:
	docker-compose -f resources/docker/docker-compose.yml up -d --build 

# Debug
interative:
	docker-compose -f resources/docker/docker-compose.yml up --build

# Stop all Containers
stop:
	docker-compose -f resources/docker/docker-compose.yml stop

# Install from clean
app-install-clean: app-install layout-images live symlink wait database-migrate database-seed generate-key stop

# Install Dependencies 
app-install: folder-structure composer-install npm-install

# Install Dev Dependencies
app-install-dev: folder-structure composer-install-dev npm-install-dev ssh-keygen

###########
# HELPERS #
###########

# Move default images to Storage
layout-images:
	cp -r src/resources/assets/images/* src/storage/app/public/images/main/

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

# Create Default Folder structure
folder-structure:
	mkdir -p src/storage/app/public/images/gallery/
	mkdir -p src/storage/app/public/images/events/
	mkdir -p src/storage/app/public/images/venues/
	mkdir -p src/storage/app/public/images/main/
	chmod 775 src/bootstrap/cache/
	chmod -R 777 src/storage/framework
	chmod -R 777 src/storage/logs
	chmod -R 777 src/storage/debugbar
	chmod -R 777 src/storage/app/public/images

# Create SSL Keypair for Development
ssh-keygen:
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout resources/certs/nginx.key -out resources/certs/nginx.crt

# Install PHP Dependencies via Composer
composer-install:
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts

# Install Dev PHP Dependencies via Composer
composer-install-dev:
	docker run --rm --interactive --tty \
    -v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts --dev

# Update Dev PHP Dependencies via Composer
composer-update:
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer require $(module) --ignore-platform-reqs --no-scripts

# Install JS Dependencies via NPM
npm-install:
	docker run -it --rm --name js-maintainence \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && node_modules/.bin/gulp --production"

# Install Dev JS Dependencies via NPM
npm-install-dev:
	docker run -it --rm --name js-maintainence-dev \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && node_modules/.bin/gulp"

# Gulp Runner
gulp:
	docker run -it --rm --name js-maintainence-dev \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST))))/src:/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "node_modules/.bin/gulp"

# Purge Containers
purge-containers:
	docker-compose -f resources/docker/docker-compose.yml -p lan_manager stop
	docker-compose -f resources/docker/docker-compose.yml -p lan_manager rm -vf
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
	sleep 20


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