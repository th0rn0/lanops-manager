# FROM th0rn0/php-nginx-base:latest
FROM lan2play/docker-php-nginx-base:latest
LABEL org.opencontainers.image.authors="Thornton Phillis (Th0rn0@lanops.co.uk), Alexader Volz (Alexander@volzit.de)"

# ENV - App Defaults

ENV UUID 82
ENV GUID 82
ENV ENABLE_HTTPS false
ENV LOG_FILES false
ENV ANALYTICS_PROVIDER GoogleAnalytics
ENV DB_MIGRATE false
ENV TIMEZONE UTC

#versioning
ARG BUILDNUMBER
ENV BUILDNUMBER=$BUILDNUMBER
ARG BUILDID
ENV BUILDID=$BUILDID
ARG SOURCE_COMMIT
ENV SOURCE_COMMIT=$SOURCE_COMMIT
ARG BUILDNODE
ENV BUILDNODE=$BUILDNODE
ARG SOURCE_REF
ENV SOURCE_REF=$SOURCE_REF

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

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
RUN sed -i 's/;clear_env/clear_env/' /usr/local/etc/php-fpm.d/www.conf
RUN sed -i 's/memory_limit = 128M/memory_limit = 512M/' /usr/local/etc/php/php.ini
RUN sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 512M/' /usr/local/etc/php/php.ini
RUN sed -i 's/post_max_size = 8M/post_max_size = 512M/' /usr/local/etc/php/php.ini
RUN sed -i 's/max_execution_time = 30/max_execution_time = 240/' /usr/local/etc/php/php.ini



# Default Command

ENTRYPOINT ["/run/docker-entrypoint.sh"]
