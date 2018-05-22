#!/bin/sh

php ./../vendor/bin/phpunit --colors --configuration phpunit-coverage.xml --bootstrap ./bootstrap.php $*
