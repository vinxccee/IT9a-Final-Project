#!/bin/sh
set -e

if [ "${SKIP_MIGRATIONS:-false}" != "true" ]; then
    php artisan migrate --force
fi

exec "$@"
