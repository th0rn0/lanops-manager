build: docker-build docker-build-init-up composer-install npm-install artisan-key-generate artisan-db-migrate docker-build-init-down

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

artisan-key-generate:
	docker compose run --rm artisan key:generate

artisan-db-migrate:
	docker compose run --rm artisan migrate

# artisan-db-seed:
# 	docker compose run --rm artisan db:seed