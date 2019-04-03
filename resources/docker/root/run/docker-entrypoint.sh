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
	if [ ! -z "$val" ];
	then
		echo 'OK'
	fi

}

# Check Variables Exist & Translate from file
file_env 'DB_PORT'
if [ -z "$DB_PORT" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because DB_PORT is not specified '
	echo >&2 'You need to specify DB_PORT'
	exit 1
fi

file_env 'DB_PORT'
if [ -z "$DB_PORT" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because DB_PORT is not specified '
	echo >&2 'You need to specify DB_PORT'
	exit 1
fi

file_env 'DB_HOST'
if [ -z "$DB_HOST" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because DB_HOST is not specified '
	echo >&2 'You need to specify DB_HOST'
	exit 1
fi

file_env 'DB_PASSWORD'
if [ -z "$DB_PASSWORD" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because DB_PASSWORD is not specified '
	echo >&2 'You need to specify DB_PASSWORD'
	exit 1
fi

file_env 'PAYPAL_USERNAME'
if [ -z "$PAYPAL_USERNAME" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because PAYPAL_USERNAME is not specified '
	echo >&2 'You need to specify PAYPAL_USERNAME'
	exit 1
fi

file_env 'PAYPAL_PASSWORD'
if [ -z "$PAYPAL_PASSWORD" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because PAYPAL_PASSWORD is not specified '
	echo >&2 'You need to specify PAYPAL_PASSWORD'
	exit 1
fi

file_env 'PAYPAL_SIGNATURE'
if [ -z "$PAYPAL_SIGNATURE" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because PAYPAL_SIGNATURE is not specified '
	echo >&2 'You need to specify PAYPAL_SIGNATURE'
	exit 1
fi

file_env 'STEAM_API_KEY'
if [ -z "$STEAM_API_KEY" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because STEAM_API_KEY is not specified '
	echo >&2 'You need to specify STEAM_API_KEY'
	exit 1
fi

file_env 'CHALLONGE_API_KEY'
if [ -z "$CHALLONGE_API_KEY" ]; 
then
	echo >&2 'ERROR'
	echo >&2 'Lan Manager App is uninitialized because CHALLONGE_API_KEY is not specified '
	echo >&2 'You need to specify CHALLONGE_API_KEY'
	exit 1
fi

# Optional Env Variables
if [ -n "$FACEBOOK_APP_ID" ] && [ ! -z "$FACEBOOK_APP_ID" ];
then
	file_env 'FACEBOOK_APP_ID'
fi

if [ -n "$FACEBOOK_APP_SECRET" ] && [ ! -z "$FACEBOOK_APP_SECRET" ];
then
	file_env 'FACEBOOK_APP_SECRET'
fi

if [ -n "$ANALYTICS_TRACKING_ID" ] && [ ! -z "$ANALYTICS_TRACKING_ID" ];
then
	file_env 'ANALYTICS_TRACKING_ID'
fi

# Database Wait check
echo "----------------------------------------------------------"
echo "WAITING FOR $DB_HOST:$DB_PORT..."
/run/wait-for.sh $DB_HOST:$DB_PORT --timeout=30 --strict -- /run/start-supervisord.sh
