build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

bash_server:
	docker exec -it client_server-php-fpm-server-1 bash

bash_client:
	docker exec -it client_server-php-fpm-client-1 bash
