if [ -f .env ]; then
  export $(echo $(cat .env | sed 's/#.*//g'| xargs) | envsubst)
fi

docker exec -i ${APP}-php-fpm php bin/laminas-cli.php $1 $2
