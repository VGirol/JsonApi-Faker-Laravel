#!/bin/bash

PHPCS_PATH="./build/scripts/phpcs.sh"
PHPMD_PATH="./build/scripts/phpmd.sh"
PHPCPD_PATH="./build/scripts/phpcpd.sh"
PHPMND_PATH="./build/scripts/phpmnd.sh"
PHPSTAN_PATH="./build/scripts/phpstan.sh"
PHAN_PATH="./build/scripts/phan.sh"

. "$PHPCS_PATH"
. "$PHPMD_PATH"
. "$PHPCPD_PATH"
. "$PHPMND_PATH"
. "$PHPSTAN_PATH"
. "$PHAN_PATH"
