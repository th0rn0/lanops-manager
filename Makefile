live: purge-containers
	docker-compose up -d --build 

# Debug
interative: purge-containers
	docker-compose up --build

# Stop all Containers
stop:
	docker-compose stop

# Install from clean
app-install-clean: app-install layout-images live symlink layout-images wait database-migrate database-seed generate-key stop

# Install Dependencies 
app-install: folder-structure composer-install npm-install

# Install Dev Dependencies
app-install-dev: folder-structure composer-install-dev npm-install-dev ssh-keygen

###########
# HELPERS #
###########

# Move default images to Storage
layout-images:
	cp -r resources/assets/images/* storage/app/public/images/main/

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
	# if [ ! -d "storage/app/public/images/gallery/" ]; then
		mkdir -p storage/app/public/images/gallery/
	# fi
	# if [ ! -d "storage/app/public/images/events/" ]; then
		mkdir -p storage/app/public/images/events/
	# fi
	# if [ ! -d "storage/app/public/images/venues/" ]; then
		mkdir -p storage/app/public/images/venues/
	# fi
	# if [ ! -d "storage/app/public/images/main/" ]; then
		mkdir -p storage/app/public/images/main/
	# fi
	chmod 775 bootstrap/cache/
	chmod -R 777 storage/framework
	chmod -R 777 storage/logs
	chmod -R 777 storage/debugbar
	chmod -R 777 storage/app/public/images

# Create SSL Keypair for Development
ssh-keygen:
	sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout certs/nginx.key -out certs/nginx.crt

# Install PHP Dependencies via Composer
composer-install:
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts

# Install Dev PHP Dependencies via Composer
composer-install-dev:
	docker run --rm --interactive --tty \
    -v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts --dev

# Install JS Dependencies via NPM
npm-install:
	docker run -it --rm --name js-maintainence \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && npm install --global gulp && gulp --production"

# Install Dev JS Dependencies via NPM
npm-install-dev:
	docker run -it --rm --name js-maintainence-dev \
	-v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/usr/src/app \
	-w /usr/src/app \
	node:8 /bin/bash -ci "npm install && npm install --global gulp && gulp"
# Purge Containers
purge-containers:
	docker-compose -p lan_manager stop
	docker-compose -p lan_manager rm -vf

# Purge Caches
purge-cache:
	sudo rm -rf storage/framework/cache/*
	sudo rm -rf storage/framework/views/*
	sudo rm -rf storage/framework/sessions/*
	sudo rm -rf bootstrap/cache/*
	sudo rm -rf storage/debugbar/*

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
	
	sudo rm -rf vendor/
	sudo rm -rf node_modules/
	sudo rm -rf public/css/*
	sudo rm -rf storage/app/public/images/gallery
	sudo rm -rf storage/app/public/images/events
	sudo rm -rf storage/app/public/images/venues
	sudo rm -rf storage/app/public/images/main
	sudo rm -rf storage/logs/*
	sudo rm public/storage

	docker rm lan_manager_server
	docker rm lan_manager_app
	docker rm lan_manager_database
	docker volume rm lan_manager_db

.ONESHELL: