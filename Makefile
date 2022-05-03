test:
	docker exec -it baserepo composer install && docker exec -it baserepo vendor/bin/phpunit