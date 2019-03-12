FROM lanopsdev/manager-base:latest
MAINTAINER Thornton Phillis (Th0rn0@lanops.co.uk)

# # ENV - Config

# ENV UUID 1000
# ENV GUID 1000
# ENV NGINX_VERSION 1.12.2
# ENV PHP_VERSION 7.1.16-r1
# ENV SUPERVISOR_LOG_ROOT /var/log/supervisor
# ENV NGINX_DOCUMENT_ROOT /web/html

# ENV - App Defaults

ENV APP_URL localhost
ENV APP_DEBUG false
ENV APP_ENV production
ENV ENABLE_HTTPS false
ENV LOG_FILES false
ENV ANALYTICS_PROVIDER GoogleAnalytics
ENV DB_CONNECTION mysql
ENV DB_MIGRATE false

# Files

COPY resources/docker/root /
WORKDIR $NGINX_DOCUMENT_ROOT
COPY src/ $NGINX_DOCUMENT_ROOT

RUN chown -R $UUID:$GUID $NGINX_DOCUMENT_ROOT/storage
RUN chmod -R 777 $NGINX_DOCUMENT_ROOT/storage

# Default Command

ENTRYPOINT ["/run/start.sh"]

