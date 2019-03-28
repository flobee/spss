#!/bin/sh

_DIR=$(dirname "$0");
# source PHP_BIN for custom versions
. $_DIR/phpbin.sh

$PHP_BIN $_DIR/../vendor/bin/phpunit --colors --configuration phpunit-coverage.xml --bootstrap ./bootstrap.php $*
