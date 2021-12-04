FROM phpdockerio/php:8.1-fpm
WORKDIR "/api"

# Fix debconf warnings upon build
ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update; \
    apt-get -y --no-install-recommends install \
        git \
        python3-pip \
        inotify-tools \
        php8.1-curl \
        php8.1-bcmath \
        php8.1-redis \
        php8.1-swoole; \
    apt-get clean;

RUN curl -sS https://getcomposer.org/installer | php -- \ --install-dir=/usr/bin --filename=composer
RUN    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

CMD ["/usr/bin/php","bin/server_ws.php"]
