if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

docker-compose down
docker-compose up -d
docker exec -i ${APP}-php-fpm php bin/laminas-swoole.php
