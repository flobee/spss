<?php

namespace SPSS\Tests;

use SPSS\Sav\Reader;
use SPSS\Sav\Record;
use SPSS\Sav\Variable;
use SPSS\Sav\Writer;

class ReaderDemoTest extends TestCase
{
    /**
     * @var Reader
     */
    private $_object;

    /**
     * Location to tests root path.
     * @var sting
     */
    private $_testsDir;

    /**
     * Location to test file to read from
     * @var string
     */
    private $_psppFileSrc;

    /**
     * Location to comparision file
     * @var string
     */
    private $_psppFileToCompare;


    public function beforeTest()
    {
        $this->_testsDir = realpath( __DIR__ . '/../../' );
        $this->_psppFileSrc = $this->_testsDir . '/data/pspp.sav';
        $this->_psppFileToCompare = $this->_testsDir . '/data/pspp.sav.printr.4.0.0-beta.txt';
        $this->_object = Reader::fromFile( $this->_psppFileSrc );
    }


    public function afterTest()
    {
        $this->_object = null;
    }


    /**
     * @todo Fix issue 4 php8
     *
     * Reverse test over the time
     */
    public function testReadPsppCheckOutput()
    {
        $this->markTestIncomplete( 'todo: since php8.0' );

        $this->beforeTest();

        $reader = $this->_object->read();

        ob_start();
        print_r( $reader );
        $actualRaw = ob_get_clean();
        $actual = preg_replace( '/(Resource id #\d{1,4})/', 'RESSOURCE_ID_REPLACED', $actualRaw );

        $expectedRaw = file_get_contents( $this->_psppFileToCompare );
        $expected = preg_replace( '/(Resource id #\d{1,4})/', 'RESSOURCE_ID_REPLACED', $expectedRaw );

        $this->assertEquals( $expected, $actual );

        $this->afterTest();
    }


    /**
     * copy from examples of version 2.2.2/ 4.0.0-beta to check
     */
    public function testReadPspp2CheckOutput()
    {
        $mesg = 'Missing features. Check these files and the output of the reader:' . PHP_EOL
            . 'ls -al test/data/data/pspp1.2.0-phpspss4.0.0*';
        $this->markTestIncomplete( $mesg );

        $file = $this->_testsDir . '/data/pspp1.2.0-phpspss4.0.0.sav';
        $reader = \SPSS\Sav\Reader::fromFile( $file )->read();

//        $actual = array(
//            'header' => $reader->header,
//            'documents' => $reader->documents,
//            'variables' => $reader->variables,
//            'valueLabels' => $reader->valueLabels,
//            'info' => $reader->info,
//        );
//
//        $expected = array(
//            'header' => 'header',
//            'documents' => 'documents',
//            'variables' => 'variables',
//            'valueLabels' => 'valueLabels',
//            'info' => 'info',
//        );

    }



}
