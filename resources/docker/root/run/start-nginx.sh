#!/bin/sh

if [ -n "$ENABLE_HTTPS" ] &&  [ "$ENABLE_HTTPS" == "true" ]
then
	echo "---------------"
	echo 'STARTING NGINX'
	echo "---------------"
	echo 'SSL ENABLED'
	echo "---------------"
	nginx -c /etc/nginx/nginx-ssl.conf -g 'daemon off;'
else
	echo "---------------"
	echo 'STARTING NGINX'
	echo "---------------"
	echo 'SSL DISABLED'
	echo "---------------"
	nginx -c /etc/nginx/nginx.conf -g 'daemon off;'
fi