FROM nginx:1.10

RUN rm /etc/nginx/conf.d/default.conf
COPY vhost.conf /etc/nginx/conf.d/default.conf
RUN sed -i 's/REPLACEMENT_SERVER_NAME/localhost/g' /etc/nginx/conf.d/default.conf
