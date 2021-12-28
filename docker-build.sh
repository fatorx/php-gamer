cp config/autoload/local.php.dist config/autoload/local.php
cp .env.dist .env

if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

docker-compose down
docker-compose --build
docker-compose up -d
docker exec -i ${APP}-php-fpm composer install --ignore-platform-reqs
docker exec -i ${APP}-php-fpm composer update  --ignore-platform-reqs
