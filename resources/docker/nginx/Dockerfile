FROM nginx:latest
LABEL maintainer="Thornton Phillis (dev@th0rn0.co.uk)"

COPY src/ /var/www/html
COPY resources/docker/nginx/default.conf /etc/nginx/conf.d/default.

# RUN usermod -u 33 -o nginx && groupmod -g 33 -o nginx
RUN addgroup www-data xfs