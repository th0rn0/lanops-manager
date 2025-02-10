build: composer-install npm-install npm-run-production 

build-init: docker-build docker-pull docker-build-init-up composer-install npm-install npm-run-development artisan-key-generate artisan-db-migrate artisan-storage-link docker-build-init-down

docker-pull:
	docker compose pull

docker-build:
	docker compose build

docker-build-init-up:
	docker compose up -d db
	sleep 30

docker-build-init-down:
	docker compose down

composer-install:
	docker compose run --rm composer install

npm-install:
	docker compose run --rm npm install

npm-run-development:
	docker compose run --rm npm run development

npm-run-production:
	docker compose run --rm npm run production

artisan-key-generate:
	docker compose run --rm artisan key:generate

artisan-db-migrate:
	docker compose run --rm artisan migrate

artisan-storage-link:
	docker compose run --rm artisan storage:link

artisan-db-fresh:
	docker compose run --rm artisan migrate:fresh

artisan-db-seed:
	docker compose run --rm artisan db:seed
