#!/bin/sh

PHP_BIN=php7.3

$PHP_BIN -v foo >/dev/null 2>&1 || { PHP_BIN=php && echo >&2 "Using default php binary"; php -version; }
