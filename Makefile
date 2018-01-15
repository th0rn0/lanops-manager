live: purge-containers prep-fixtures
	docker-compose -p lanops up -d --build 

interative: purge-containers prep-fixtures
	docker-compose -p lanops up --build

stop:
	docker-compose -p lanops stop

prep-fixtures:

purge-containers:
	docker-compose -p lanops stop
	docker-compose -p lanops rm -vf

app-install:
	# We need to give write permission on the app directory for the webserver.
	# For dev, let's just give write permission to all since changing the owner of this directory will also affect the
	# local machine. We should probably avoid this as could affect our own access to this directory.
	# apt-get install -y php git docker docker-compose
	# apt-get install -y php-mbstring php-xml php-zip php-bz2 php-dom php-curl php-bcmath php-gd
	rm -rf bootstrap/cache/*
	chmod 777 -R bootstrap/cache/
	chmod 777 -R storage/
	chmod -R o+w storage/
	composer dump-auto
	composer install --no-scripts
	## Composer Install
	# docker run --rm --interactive --tty \
 	#    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
 	#    --user $(id -u):$(id -g) \
 	#    composer install --ignore-platform-reqs --no-scripts
	# Installation of the app needs to come after the docker services are already up 
	# and running. This is because both database and memcached containers are needed 
	# for migrated the db

# Helper to just clear all docker containers and images
clear-docker-images:
	docker rm $$(docker ps -a -q) -f
	docker rmi $$(docker images -q) -f

composer-install:
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install

composer-install-dev:
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --dev
