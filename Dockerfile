FROM mobtitude/php-xdebug:8.1-cli

ARG HOST_UID
ARG HOST_GID

# Install required system dependencies and Composer
RUN apt-get update && apt-get install -y git zip unzip && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Install Xdebug extension
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Set PHP configurations for Xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get install -y librabbitmq-dev && \
    pecl install amqp && \
    docker-php-ext-enable amqp

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

# Add a user able to write to modified images directory
RUN groupadd -g "${HOST_GID}" group \
  && useradd --create-home --no-log-init -u "${HOST_UID}" -g "${HOST_GID}" user


WORKDIR /var/www

CMD ["php", "-v"]