FROM php:7.2.5-cli

MAINTAINER Martin Fris <rasta@lj.sk>

ENV REFRESHED_AT 2018-04-07

RUN apt-get update && apt-get install -y git-core

# Install xdebug
RUN yes | pecl install xdebug && \
    echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_host=docker-host.dev" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo 'xdebug.file_link_format="phpstorm://open?url=file://%%f&line=%%l"' >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_autostart=1" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_port=9090" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.max_nesting_level=10000" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_handler=dbgp" >> /usr/local/etc/php/conf.d/xdebug.ini && \
    echo "xdebug.remote_mode=req" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install APCU
RUN yes | pecl install apcu && \
    echo "extension=$(find /usr/local/lib/php/extensions/ -name apcu.so)" > /usr/local/etc/php/conf.d/apcu.ini

RUN rm -rf /tmp/pear

COPY ./php.ini /usr/local/etc/php/conf.d

WORKDIR /opt/project

CMD ["php"]
