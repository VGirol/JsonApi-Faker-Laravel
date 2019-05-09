#!/bin/bash

PHPUNIT_PATH="./build/scripts/phpunit.sh"
INFECTION_PATH="./build/scripts/infection.sh"
PHPMETRICS_PATH="./build/scripts/phpmetrics.sh"

. "$PHPUNIT_PATH"
. "$INFECTION_PATH"
. "$PHPMETRICS_PATH"
