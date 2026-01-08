install:
	docker compose up -d --build
	docker compose exec php composer install
	docker compose exec php composer dump-autoload

start:
	docker compose up -d

stop:
	docker compose down

test:
	docker compose exec php vendor/bin/phpunit tests/

code-check:
	docker compose exec php vendor/bin/psalm
	docker compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff

code-fix:
	docker compose exec php vendor/bin/php-cs-fixer fix
	docker compose exec php vendor/bin/psalm --clear-cache
