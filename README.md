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