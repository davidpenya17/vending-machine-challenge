#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

WORKING_DIR=$DIR/..

$WORKING_DIR/bin/phpunit tests

execResult=$?

if [[ $execResult -ne 0 ]]; then
  exit 1
fi
exit 0