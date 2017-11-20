# SPSS / PSPP

A PHP library for reading and writing SPSS / PSPP .sav data files.

## Requirements

PHP 5.3.*, 7.* and up.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

    composer require flobee/spss

to the require section of your `composer.json` file.


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


## Tests / Demo / Developers

Using shell:

Initialy to get dependencies:

    composer install

then (base demo data at tests/data/pspp.sav):

    cd tests

    php readerDemo.php | less
    # or to update tmp file to check for changes:
    php readerDemo.php > data/pspp.sav.printr.txt

#### running tests:
    
    cd tests
    phpunit
    # have a look at ../doc/CodeCoverage (use a browser)


## License
Licensed under the [MIT license](http://opensource.org/licenses/MIT).
