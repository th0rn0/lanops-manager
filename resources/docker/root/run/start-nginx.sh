#!/bin/sh

if [ -n "$ENABLE_HTTPS" ] &&  [ "$ENABLE_HTTPS" == "true" ]
then
	echo 'STARTING NGINX'
	echo 'SSL ENABLED'
	nginx -c /etc/nginx/nginx-ssl.conf -g 'daemon off;'
else 
	echo 'STARTING NGINX'
	echo 'SSL DISABLED'
	nginx -c /etc/nginx/nginx.conf -g 'daemon off;' 
fi