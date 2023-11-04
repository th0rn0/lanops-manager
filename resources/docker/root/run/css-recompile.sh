#!/bin/sh

# APPEAR_DISABLE_CUSTOM_CSS_LINKING
if [ "$APPEAR_DISABLE_CUSTOM_CSS_LINKING" = 'false' ]; then

	echo "---------------"
    echo "css recompilation ..."
	php artisan UpdateDatabaseCssVariablesFromFile
	php artisan RecompileCss
fi