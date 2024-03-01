#!/usr/bin/env bash
set -Eeuo pipefail

if [ $WITHOUT_CRON != "true" ] ; then
  # Create a copy of the environment variable file with each env as an export
  export -p | sed  's/declare -x /export /g' > /opt/env_exports

  # Set permissions to the env_exports file so this file can be accessed by any user on the instance.
  chmod 644 /opt/env_exports

  cron
fi ;

exec docker-php-entrypoint "$@"
