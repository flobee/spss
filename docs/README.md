# SPSS / PSPP


A PHP library for reading and writing SPSS / PSPP .sav data files.

VERSION 4.* (see [upgrade section](#upgrade-to-version-3) for details)

[![Stable Build Status](https://travis-ci.com/flobee/spss.svg?branch=stable)](https://travis-ci.com/flobee/spss/branches?stable)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/flobee/spss.svg?style=flat-square)](https://packagist.org/packages/flobee/spss)
[![Total Downloads](https://img.shields.io/packagist/dt/flobee/spss.svg?style=flat-square)](https://packagist.org/packages/flobee/spss)


Fork of tiamo/spss. Mostly same code base. Introdusing a more complete test enviroment
which now exists over there. So, one day i switch back to it.


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

+ php-cli
+ php-mdstring
+ php-bcmath


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



## Demo/ Examples/ playground

    cd tests

    php readerDemo.php | less
    # or to update tmp file to check for changes (using a git diff):
    php readerDemo.php > data/pspp.sav.printr.txt

In folder [examples/](/examples/) you will find some more examples.



## Tests / Developers

### Install using shell

    git clone https://github.com/flobee/spss.git
    cd spss/
    git submodule init # once
    git submodule update --recursive # upgrading (after a `git pull`)

Initialy or for upgrades to get development dependencies:

    composer install

For more dev-tools you may install phpstan/phpstan `composer require -dev phpstan/phpstan`
which shows a lot of stucture problems of the application and it should getting more
attention (beginning with level=1).


### Update existing code

    git pull
    git submodule update
    composer install


### Running tests

    VERSION 4:
    Improved additions for CI systems (e.g: jenkins) where `phing` is the
    prefered build tool. `composer` the prefered package manager.

    # shows you the options
    ./phing -l

    # executes the tests
    ./phing test

    # executes the tests and create the code coverage
    ./phing coverage

    # run all tasks (CS checks, coverage, tests, sca...)
    ./phing all

    ...

    cd tests
    sh ./runTests.sh

    # have a look at ../docs/CodeCoverage (use a browser after execution)
    sh ./runTestsCoverageCreate.sh

    # playground:
    php readerDemo.php | less
    # or to update tmp file to check for changes:
    php readerDemo.php > data/pspp.sav.printr.txt



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

Copyright (c) 2017 Vladyslav Korniienko

[License text](LICENSE.md)

Licensed under the [MIT license](http://opensource.org/licenses/MIT)
