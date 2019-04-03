FROM lanopsdev/manager-base:latest
MAINTAINER Thornton Phillis (Th0rn0@lanops.co.uk)

# ENV - App Defaults

ENV ENABLE_HTTPS false
ENV LOG_FILES false
ENV ANALYTICS_PROVIDER GoogleAnalytics
ENV DB_MIGRATE false

# Files

COPY resources/docker/root /
WORKDIR $NGINX_DOCUMENT_ROOT
COPY src/ $NGINX_DOCUMENT_ROOT

# Copy Storage for Bind Mounts - Fix for Bind Mounts on Host system

RUN cp -a $NGINX_DOCUMENT_ROOT/storage /tmp/storage

# Volumes

VOLUME $NGINX_DOCUMENT_ROOT/storage

# Default Command

ENTRYPOINT ["/run/docker-entrypoint.sh"]
