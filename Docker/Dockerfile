FROM php:7.4-fpm

ARG HOST_USER_ID

RUN apt-get update && apt-get install -y --no-install-recommends \
		libfreetype6-dev \
		libjpeg-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
		libzip-dev \
		libssl-dev \
        libicu-dev \
		unzip \
		zip

RUN docker-php-ext-configure gd --with-freetype --with-jpeg; \
	docker-php-ext-configure zip; \
    docker-php-ext-configure intl; \
	docker-php-ext-install gd; \
	docker-php-ext-install pdo_mysql; \
	docker-php-ext-install opcache; \
	docker-php-ext-install intl; \
	docker-php-ext-install zip; \
	docker-php-ext-install exif; \
	apt-get purge -y --auto-remove -o APT::AutoRemove::RecommendsImportant=false; \
	rm -rf /var/lib/apt/lists/*;

RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN { \
		echo 'date.timezone = "Europe/Amsterdam"'; \
	} > /usr/local/etc/php/conf.d/php-custon.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY php/php.ini /usr/local/etc/php/php.ini

COPY php/php-fpm.conf /usr/local/etc/php-fpm.d/zzz-docker.conf

WORKDIR /var/www

RUN addgroup --gid 1000 user
RUN adduser --disabled-password --gecos '' --uid $HOST_USER_ID --gid 1000 user
USER user
