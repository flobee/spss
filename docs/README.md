# SPSS / PSPP

A PHP library for reading and writing SPSS / PSPP .sav data files.

VERSION 3.* (see [upgrade section](#upgrade-to-version-3) for details)


## Requirements

PHP 7.* and up.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/)

Either run

    composer require flobee/spss

to the require section of your `composer.json` file [see here](https://packagist.org/packages/flobee/spss) 
or download from [here](https://github.com/flobee/spss/releases).


## Usage

Reader example:

    $reader = \SPSS\Reader::fromFile('path/to/file.sav');

or

    $reader = \SPSS\Reader::fromString(file_get_contents('path/to/file.sav'));


Writer example:

    $writer = new \SPSS\Writer([
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

Using shell:

    git clone https://github.com/flobee/spss.git
    cd spss/
    git submodule init # once
    git submodule update --recursive # upgrading (after `git pull`)

Initialy or for upgrades to get development dependencies:
    
    composer install

then (base demo data at tests/data/pspp.sav will be used):

    VERSION 3.*

    # shows you the options
    ./phing 
    # executes the tests
    ./phing test
    # executes the tests and create the code coverage
    ./phing coverage
    ...

    VERSION 2.*

    cd tests 
    sh ./runTests.sh

    php readerDemo.php | less
    # or to update tmp file to check for changes:
    php readerDemo.php > data/pspp.sav.printr.txt


#### running tests:

    cd tests
    sh ./runTests.sh
    
    # have a look at ../docs/CodeCoverage (use a browser after execution)
    sh ./runCoverage.sh


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

Please have a look in `docs/CHANGELOG.md`

- VERSION 3.*

    2018-05
    - Update Reader interface/ construction

      Adds optional flag to disable reading the data/contents to improve the 
      reader performance when just analyse the stucture of an spss/pspp file
      @ thanks to stephanw for the hint

    - Updates Testing/ developer enviroment

      Adds phing as default tool for tests, code coverage and additional task
      you may need for you production/ deployment/ development.

      php 5* = OFF (Maybe it works. Not tested)

      VERSION/TAG 2.0.2 Created by accident (if you got it)

      Beginning with php7.0 and already depricated: php7.2++ first and future...
      not in at all :)
 

## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT).
