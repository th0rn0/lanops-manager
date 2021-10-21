FROM th0rn0/php-nginx-base:v1.2
#FROM lan2play/php-nginx-base:latest
MAINTAINER Thornton Phillis (Th0rn0@lanops.co.uk)

# ENV - App Defaults

ENV UUID 100
ENV GUID 101
ENV ENABLE_HTTPS false
ENV LOG_FILES false
ENV ANALYTICS_PROVIDER GoogleAnalytics
ENV DB_MIGRATE false
ENV TIMEZONE UTC

# Files

COPY resources/docker/root /
WORKDIR $NGINX_DOCUMENT_ROOT
COPY --chown=${UUID}:${GUID} src/ $NGINX_DOCUMENT_ROOT

#RUN chown -R ${UUID}:${GUID} $NGINX_DOCUMENT_ROOT
RUN chgrp -R ${GUID} $NGINX_DOCUMENT_ROOT/storage $NGINX_DOCUMENT_ROOT/bootstrap/cache
RUN chmod -R ug+rwx $NGINX_DOCUMENT_ROOT/storage $NGINX_DOCUMENT_ROOT/bootstrap/cache

# Copy Storage for Bind Mounts - Fix for Bind Mounts on Host system

RUN cp -a $NGINX_DOCUMENT_ROOT/storage /tmp/storage

# PHP Fixes

RUN sed -i 's/;clear_env/clear_env/' /etc/php7/php-fpm.d/www.conf
RUN sed -i 's/memory_limit = 128M/memory_limit = 512M/' /etc/php7/php.ini
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 512M/' /etc/php7/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 512M/' /etc/php7/php.ini

RUN sed -i 's/user = nobody/user = ${UUID}/' /etc/php7/php-fpm.d/www.conf
RUN sed -i 's/group = nobody/group = ${GUID}/' /etc/php7/php-fpm.d/www.conf

# Default Command

ENTRYPOINT ["/run/docker-entrypoint.sh"]
