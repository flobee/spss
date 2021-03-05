# SPSS / PSPP

A PHP library for reading and writing SPSS / PSPP .sav data files.

VERSION 3.* (see [upgrade section](#upgrade-to-version-3) for details)

[![Stable Build Status](https://travis-ci.com/flobee/spss.svg?branch=stable)](https://travis-ci.com/flobee/spss/branches?stable)
[![Unstable Build Status](https://travis-ci.com/flobee/spss.svg?branch=unstable)](https://travis-ci.com/flobee/spss/branches?unstable)



<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of contents**

+ [Requirements](#requirements)
+ [Installation](#installation)
+ [Usage](#usage)
+ [Demo/ playground](#demo-playground)
+ [Tests / Developers](#tests--developers)
  + [Install using shell](#install-using-shell)
  + [Update existing code](#update-existing-code)
  + [Running tests](#running-tests)
  + [Upgrade to version 3.*](#upgrade-to-version-3)
+ [Changelog](#changelog)
+ [License](#license)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->



## Requirements

PHP 8.0 and up.

+ php8.0-cli
+ php8.0-mdstring
+ php8.0-bcmath


## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/)

Either run

    composer require flobee/spss

to the require section of your `composer.json` file [see here](https://packagist.org/packages/flobee/spss)
or download from [here](https://github.com/flobee/spss/releases).


## Usage

Reader example:

    $reader = \SPSS\Sav\Reader::fromFile('path/to/file.sav');

or

    $reader = \SPSS\Sav\Reader::fromString(file_get_contents('path/to/file.sav'));


Writer example:

    $writer = new \SPSS\Sav\Writer([
        'header' => [
                'prodName'     => '@(#) SPSS DATA FILE test',
                'layoutCode'   => 2,
                'compression'  => 1,
                'weightIndex'  => 0,
                'bias'         => 100,
                'creationDate' => '13 Feb 89',
                'creationTime' => '23:58:59',
        ],
        'variables' => [
            [
                    'name'     => 'VAR1',
                    'width'    => 0,
                    'decimals' => 0
                    'format'   => 5,
                    'columns'  => 50,
                    'align'    => 1,
                    'measure'  => 1,
                    'data'     => [
                        1, 2, 3
                    ],
            ],
            ...
        ]
    ]);



## Demo/ playground

    cd tests

    php readerDemo.php | less
    # or to update tmp file to check for changes (using a diff):
    php readerDemo.php > data/pspp.sav.printr.txt



## Tests / Developers

### Install using shell

    git clone https://github.com/flobee/spss.git
    cd spss/
    git submodule init # once
    git submodule update --recursive # upgrading (after a `git pull`)

Initialy or for upgrades to get development dependencies:

    composer install

then (base demo data at tests/data/pspp.sav will be used):

    VERSION 4 (Improved additions for CI systems)

    # shows you the options
    ./phing -l

    # executes the tests
    ./phing test

    # executes the tests and create the code coverage
    ./phing coverage

    # run all tasks (CS checks, coverage, tests)
    ./phing all

    ...

    cd tests
    sh ./runTests.sh

    php readerDemo.php | less
    # or to update tmp file to check for changes:
    php readerDemo.php > data/pspp.sav.printr.txt


### Update existing code

    git pull
    git submodule update
    composer install


### Running tests

    cd tests
    sh ./runTests.sh

    # have a look at ../docs/CodeCoverage (use a browser after execution)
    sh ./runTestsCoverageCreate.sh



### Upgrade to version 3.*

Update your composer.json

    {
        ...
        "require": {
            "flobee/spss": "3.*",
        },
        ...
    }

Next:

    composer update flobee/spss

Dependency Errors?

If you get dependency errors, you may upgrade other spss dependencies too.
If so try the following:

    composer update flobee/spss --with-dependencies



## Changelog

Please have a look in [docs/CHANGELOG.md](docs/CHANGELOG.md)



## License

Copyright (c) 2017 Vladislav K

Licensed under the [MIT license](http://opensource.org/licenses/MIT).
