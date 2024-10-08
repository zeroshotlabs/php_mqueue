#!/bin/bash

PHP_CONFIG=${PHP_CONFIG:-$(which php-config 2>/dev/null)}

if [ -z "$PHP_CONFIG" ]; then
    echo -e "\n === Error: php-config not found - try:"
    echo ' ===   PHP_CONFIG=/path/to/php-config composer run-script post-install-cmd' >&2
    exit 1
fi

make clean all
