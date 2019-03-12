FROM lanopsdev/manager-base:latest
MAINTAINER Thornton Phillis (Th0rn0@lanops.co.uk)

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

