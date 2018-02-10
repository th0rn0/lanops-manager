live: purge-containers
	docker-compose up -d --build 

interative: purge-containers
	docker-compose up --build

stop:
	docker-compose stop

app-install: folder-structure composer-install npm-install

app-install-dev: folder-structure composer-install-dev npm-install

# DANGER ZONE
clean-all: stop purge-containers
	echo 'This is dangerous!'
	echo 'This will totally remove all data and information stored in your app!'
	echo 'do you want to continue? (Y/N)'
	
	sudo rm -rf storage/app/public/images/gallery/*
	sudo rm -rf storage/app/public/images/events/*
	sudo rm -rf vendor/*
	sudo rm -rf node_modules/
	sudo rm -rf public/css/*
	sudo rm -rf storage/framework/cache/*
	sudo rm -rf storage/framework/views/*
	sudo rm -rf storage/framework/sessions/*
	sudo rm -rf bootstrap/cache/*

	docker rm lan_manager_server
	docker rm lan_manager_app
	docker rm lan_manager_database
	docker rmi manager_server
	docker rmi manager_app
	docker rmi manager_database
	docker volume rm lan_manager_db

#########
#HELPERS#
#########

composer-install:
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts

composer-install-dev:
	## Composer Install
	docker run --rm --interactive --tty \
    --volume $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/app \
    --user $(id -u):$(id -g) \
    composer install --ignore-platform-reqs --no-scripts --dev

npm-install:
	docker run \
      --rm \
      -it \
      -v $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))):/src:rw \
      mkenney/npm:node-6.9-debian npm install && gulp

folder-structure:
	chmod 777 bootstrap/cache/
	chmod 777 storage/
	chmod -R o+w storage/

ssh-keygen:
	sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout certs/nginx.key -out certs/nginx.crt

purge-containers:
	docker-compose -p lan_manager stop
	docker-compose -p lan_manager rm -vf