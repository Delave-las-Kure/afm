FROM wordpress:6.5.5-php8.1-apache
# Install packages under Debian
RUN apt-get update && \
    apt-get -y install git
# Install XDebug from source as described here:
# https://xdebug.org/docs/install
# Available branches of XDebug could be seen here:
# https://github.com/xdebug/xdebug/branches
RUN cd /tmp && \
    git clone https://github.com/xdebug/xdebug.git && \
    cd xdebug && \
    git checkout xdebug_3_2 && \
    phpize && \
    ./configure --enable-xdebug && \
    make && \
    make install && \
    rm -rf /tmp/xdebug


RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Since this Dockerfile extends the official Docker image `wordpress`,
# and since `wordpress`, in turn, extends the official Docker image `php`,
# the helper script docker-php-ext-enable (defined for image `php`)
# works here, and we can use it to enable xdebug:
RUN docker-php-ext-enable xdebug

# Copy your custom CA certificates to the container
COPY ./apisix/https-portal-data/assistant.local/local/signed.crt /usr/local/share/ca-certificates/assistant.local.crt

# Update CA certificates
RUN update-ca-certificates

RUN curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar && \
    chmod +x wp-cli.phar && \
    mv wp-cli.phar /usr/local/bin/wp &&\
    wp package install wp-cli/profile-command --allow-root

RUN mkdir /tmp/xdebug
RUN chmod 777 /tmp/xdebug