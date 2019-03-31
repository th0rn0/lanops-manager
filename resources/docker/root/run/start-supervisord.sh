#!/bin/sh

echo "$DB_HOST:$DB_PORT IS LIVE!"

### ---- Do NOT edit below this line ---- ###

# Generate the App Key if it doesn't Exist
if [ ! -n "$APP_KEY" ]
then
	echo "Cannot Find APP_KEY. Generating......"
	export APP_KEY=$(php artisan key:generate | cut -d "[" -f2 | cut -d "]" -f1)
	echo "-----------------------------------------------------------------------------------------------------------------------------------"
	echo "YOUR APP KEY IS - ${APP_KEY} - SAVE IT FOR LATER USE. REFER TO README"
	echo "-----------------------------------------------------------------------------------------------------------------------------------"
	sleep 15
fi

# Add the symlinks for logs to allow NGINX & set Laravel to log to file instead of to stdout
if [ -n "$LOG_FILES" ]
then
	if [ "$LOG_FILES" = "true" ]
	then
		echo "LOG_FILES set to true. Writing logs to disk......"
		rm /var/log/nginx/access.log
		rm /var/log/nginx/error.log
 		ln -sf $NGINX_DOCUMENT_ROOT/storage/logs/access.log /var/log/nginx/access.log
		ln -sf $NGINX_DOCUMENT_ROOT/storage/logs/error.log /var/log/nginx/error.log
		echo "-----------------------------------------------------------------------------------------------------------------------------------"
		echo "YOUR LOGS CAN BE FOUND IN $NGINX_DOCUMENT_ROOT/storage/logs/ WITHIN THE CONTAINER"
		echo "-----------------------------------------------------------------------------------------------------------------------------------"
		export APP_LOG="single" 
	fi
fi

# Clear the config cache and load in new Variables
php artisan config:cache

# Only migrate database if DB_MIGRATE env variable is set to true
if [ -n "$DB_MIGRATE" ]
then
	if [ "$DB_MIGRATE" = "true" ]
	then
		php artisan migrate
	fi
fi

# Supervisor Default
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
