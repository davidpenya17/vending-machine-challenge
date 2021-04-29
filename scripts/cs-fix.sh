#!/usr/bin/env bash

BASEDIR=$(dirname $0)
WORKING_DIR=$BASEDIR/..

$WORKING_DIR/vendor/bin/php-cs-fixer fix --config=$WORKING_DIR/.php_cs.dist -q --path-mode=intersection .

