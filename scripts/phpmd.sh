#!/bin/bash

echo "Running phpmd..."
phpmd ./src text ./build/phpmd/phpmd.xml --reportfile ./build/phpmd/src.txt --ignore-violations-on-exit
phpmd ./tests text ./build/phpmd/phpmd-tests.xml --reportfile ./build/phpmd/tests.txt --ignore-violations-on-exit
