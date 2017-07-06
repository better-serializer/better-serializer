FROM php:7.1.6-cli

MAINTAINER Martin Fris <rasta@lj.sk>

ENV REFRESHED_AT 2017-07-06

# Install Xdebug
RUN pecl install -o -f xdebug && \
    rm -rf /tmp/pear

COPY php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN XDEBUG_FOLDER=$(ls -a /usr/local/lib/php/extensions/ | grep -v '^\.') && \
    sed -i "s/{XDEBUG_FOLDER}/$XDEBUG_FOLDER/" /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /opt/project
