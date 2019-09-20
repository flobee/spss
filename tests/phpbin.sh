#!/bin/sh

PHP_BIN=php7.3

$PHP_BIN -v $PHP_BIN >/dev/null 2>&1 || { PHP_BIN=php && echo >&2 "$PHP_BIN not found. Using default PHP binary"; php --version; }
