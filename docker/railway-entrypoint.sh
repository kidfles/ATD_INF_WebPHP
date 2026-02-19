#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-80}"

# Update Apache to listen on the platform-provided port
if [[ -f /etc/apache2/ports.conf ]]; then
  sed -ri "s/^\s*Listen\s+[0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
fi

if [[ -f /etc/apache2/sites-available/000-default.conf ]]; then
  sed -ri "s/<VirtualHost\s+\*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

exec docker-php-entrypoint apache2-foreground

