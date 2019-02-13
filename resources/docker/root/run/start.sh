#!/bin/sh
echo "WAITING FOR $DB_HOST:$DB_PORT..."
/run/wait-for.sh $DB_HOST:$DB_PORT --timeout=30 --strict -- echo "$DB_HOST:$DB_PORT IS LIVE!"

### ---- Do NOT edit below this line ---- ###

# Only migrate database if DB_MIGRATE env variable is set to true
if [ -n "$DB_MIGRATE" ]
then
	if [ "$DB_MIGRATE" = "true" ]
	then
		php artisan migrate
	fi
fi

# Add the symlinks for logs to allow NGINX & set Laravel to log to file instead of to stdout
if [ -n "$LOG_FILES" ]
then
	if [ "$LOG_FILES" = "true" ]
	then
		rm /var/log/nginx/access.log
		rm /var/log/nginx/error.log
 		ln -sf $NGINX_DOCUMENT_ROOT/storage/logs/access.log /var/log/nginx/access.log
		ln -sf $NGINX_DOCUMENT_ROOT/storage/logs/error.log /var/log/nginx/error.log
		export APP_LOG="single" 
	fi
fi

# Supervisor Default
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf