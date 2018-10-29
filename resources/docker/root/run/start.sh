#!/bin/sh

### ---- Do NOT edit below this line ---- ###

# Only migrate database if DB_MIGRATE env variable is set to true
if [ -n "$DB_MIGRATE" ]
then
	if [ "$DB_MIGRATE" = "true" ]
	then
		php artisan migrate
	fi
fi

# Remove the symlinks for logs to allow logging to file instead of to stdout
if [ -n "$LOG_FILES" ]
then
	if [ "$LOG_FILES" = "true" ]
	then
		rm /var/log/nginx/access.log
		rm /var/log/nginx/error.log
 		ln -sf /web/html/storage/logs/access.log /var/log/nginx/access.log
		ln -sf /web/html/storage/logs/error.log /var/log/nginx/error.log
	fi
fi

# Supervisor Default
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf