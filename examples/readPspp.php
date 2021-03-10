<?php

require_once __DIR__ . '/../vendor/autoload.php';

$reader = \SPSS\Sav\Reader::fromString(
    // dont change this pspp file. It is also used in tests!
    file_get_contents( './../tests/data/pspp.sav' )
)->read();

print_r( $reader );
