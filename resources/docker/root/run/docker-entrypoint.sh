#!/bin/bash
set -eo pipefail
shopt -s nullglob

# Translate Variables and Files to Environment
file_env() {
	local var="$1"
	echo -n "Setting $var ... "
	local fileVar="${var}_FILE"
	local def="${2:-}"
	if [ "${!var:-}" ] && [ "${!fileVar:-}" ]; then
		echo >&2 "error: both $var and $fileVar are set (but are exclusive)"
		exit 1
	fi
	local val="$def"
	if [ "${!var:-}" ]; then
		val="${!var}"
	elif [ "${!fileVar:-}" ]; then
		val="$(< "${!fileVar}")"
	fi
	export "$var"="$val"
	unset "$fileVar"
}

# Check Variables Exist & Translate from file
# Required Env Variables
echo 'Required Env Variable Check:'
file_env 'APP_URL'
if [ -z "$APP_URL" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Event Manager App is uninitialized because APP_URL is not specified '
	echo >&2 'You need to specify APP_URL'
	exit 1
else
	echo 'OK'
fi
file_env 'APP_EMAIL'
if [ -z "$APP_EMAIL" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Event Manager App is uninitialized because APP_EMAIL is not specified '
	echo >&2 'You need to specify APP_EMAIL'
	exit 1
else
	echo 'OK'
fi
file_env 'DB_PORT'
if [ -z "$DB_PORT" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Event Manager App is uninitialized because DB_PORT is not specified '
	echo >&2 'You need to specify DB_PORT'
	exit 1
else
	echo 'OK'
fi

file_env 'DB_HOST'
if [ -z "$DB_HOST" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Event Manager App is uninitialized because DB_HOST is not specified '
	echo >&2 'You need to specify DB_HOST'
	exit 1
else
	echo 'OK'
fi

file_env 'DB_PASSWORD'
if [ -z "$DB_PASSWORD" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Event Manager App is uninitialized because DB_PASSWORD is not specified '
	echo >&2 'You need to specify DB_PASSWORD'
	exit 1
else
	echo 'OK'
fi


# Optional Env Variables
echo 'Option Env Variable Check:'
file_env 'MAIL_HOST'
if [ -z "$MAIL_HOST" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi
file_env 'MAIL_USERNAME'
if [ -z "$MAIL_USERNAME" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi
file_env 'MAIL_PASSWORD'
if [ -z "$MAIL_PASSWORD" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi
file_env 'STEAM_API_KEY'
if [ -z "$STEAM_API_KEY" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'CHALLONGE_API_KEY'
if [ -z "$CHALLONGE_API_KEY" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'APP_KEY'
if [ -z "$APP_KEY" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'CHALLONGE_API_KEY'
if [ -z "$CHALLONGE_API_KEY" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'FACEBOOK_APP_ID'
if [ -z "$FACEBOOK_APP_ID" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'FACEBOOK_APP_SECRET'
if [ -z "$FACEBOOK_APP_SECRET" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi
file_env 'STRIPE_PUBLIC_KEY'
if [ -z "$STRIPE_PUBLIC_KEY" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'STRIPE_SECRET_KEY'
if [ -z "$STRIPE_SECRET_KEY" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'PAYPAL_USERNAME'
if [ -z "$PAYPAL_USERNAME" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'PAYPAL_PASSWORD'
if [ -z "$PAYPAL_PASSWORD" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'PAYPAL_SIGNATURE'
if [ -z "$PAYPAL_SIGNATURE" ]; 
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'ANALYTICS_TRACKING_ID'
if [ -z "$ANALYTICS_TRACKING_ID" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'ANALYTICS_TRACKING_ID'
if [ -z "$ANALYTICS_TRACKING_ID" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'ANALYTICS_TRACKING_ID'
if [ -z "$ANALYTICS_TRACKING_ID" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

file_env 'ANALYTICS_TRACKING_ID'
if [ -z "$ANALYTICS_TRACKING_ID" ];
then
	echo 'NOT SET'
else
	echo 'OK'
fi

if [ -n "$ENV_OVERRIDE" ] && [ "$ENV_OVERRIDE" = 'true' ];
then
	echo 'WARNING!'
	echo 'ENV OVERRIDE IS SET!'
	echo 'ALL API KEYS WILL BE OVERWRITTEN BY THE ENV VARIABLES'
	echo 'DATABASE API KEYS WILL NOT BE USED'
else
	echo 'ENV OVERRIDE IS NOT SET!'
	echo 'API Keys from the Database will be used'
fi

# Populate Storage Volume if Bind mount - Fix for Bind Mounts on Host system
if [ -z "$(ls -A $NGINX_DOCUMENT_ROOT/storage)" ]; then
	echo "---------------"
    echo "Storage on Bind mount is empty. Copying sample data ..."
 	cp -a /tmp/storage $NGINX_DOCUMENT_ROOT
fi

if [[ $(stat -c "%u" $NGINX_DOCUMENT_ROOT/storage) != $UUID ]]; then
	echo "---------------"
    echo "Changing ownership of $NGINX_DOCUMENT_ROOT/storage to $UUID ..."
    chown -R $UUID:$GUID $NGINX_DOCUMENT_ROOT/storage
fi

# Make Symlink for images if it doesn't already exist
if [ ! -L "$NGINX_DOCUMENT_ROOT/public/storage" ]; then
	php artisan storage:link
fi

# Database Wait check
echo "---------------"
echo "WAITING FOR $DB_HOST:$DB_PORT..."
/run/wait-for.sh $DB_HOST:$DB_PORT --timeout=30 --strict -- /run/start-supervisord.sh
