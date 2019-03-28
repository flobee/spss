#!/bin/sh

echo "----------------------------------------";
echo "usage: $0 [phpcs options] <path or file>";
echo "----------------------------------------";

_DIR=$(dirname "$0");
# source PHP_BIN for custom versions
. $_DIR/phpbin.sh

$PHP_BIN $_DIR/../vendor/bin/phpcs --standard=$_DIR/../misc/coding/Mumsys $* 

# php ./../vendor/bin/phpcs -n --standard=./../misc/coding/Mumsys ./../src ./../tests
