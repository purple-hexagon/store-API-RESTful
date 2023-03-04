# Efraín Store API RESTful

## Info

The Laravel project together with its DB are deployed in docker, the necessary docker-compose.yml file is included in the project.

## Requirements

- Docker

## Init

1º Build docker and start php laravel proyect.

(By first locating ourselves in the directory where the file docker-compose.yml is located) Keep terminal open to keep the project up and running.
```bash
docker-compose up
```

2º Run to execute Migrations
```bash
docker exec -it purple-hexagon_store-API-RESTful_php php artisan migrate
```

3º Run to execute Seeders
```bash
docker exec -it purple-hexagon_store-API-RESTful_php php artisan db:seed
```

## Util

Reset database
```bash
docker exec -it purple-hexagon_store-API-RESTful_php php artisan migrate:fresh --seed
```

## Start

Start php laravel proyect (If it was stopped).
```bash
docker exec -it purple-hexagon_store-API-RESTful_php php artisan serve
```

## Docker containers

1º Access mariadb container.
```bash
docker exec -it purple-hexagon_store-API-RESTful_mariadb bash
```

2º Access php laravel container.
```bash
docker exec -it purple-hexagon_store-API-RESTful_php bash
```

## License
[MIT](https://choosealicense.com/licenses/mit/)