<?php

namespace SPSS\Tests;

use SPSS\Sav\Reader;
use SPSS\Sav\Record;
use SPSS\Sav\Variable;
use SPSS\Sav\Writer;

class SavRandomReadWriteTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private static $file;

    public static function setUpBeforeClass()
    {
        self::$file = tempnam(sys_get_temp_dir(), 'spss');
    }

    public static function tearDownAfterClass()
    {
        unlink(self::$file );
    }

    /**
     * @return array
     */
    public function testWrite()
    {
        $data = $this->dataProvider();
        $writer = new Writer( $data );
        $writer->save(self::$file);
        $this->assertFileExists(self::$file);
        return $data;
    }


    /**
     * @depends testWrite
     * @param array $data
     */
    public function testRead( array $data )
    {
        $reader = Reader::fromFile(self::$file);
        foreach ( $data['header'] as $key => $value ) {
            $this->assertEquals( $reader->header->{$key}, $value, $key);
        }
        $index = 0;
        foreach ( $data['variables'] as $var ) {
            /** @var Record\Variable $_var */
            $_var = $reader->variables[$index];
            $this->assertEquals( $var['name'], $_var->name );
            $this->assertEquals( $var['label'], $_var->label );
            $this->assertEquals( $var['decimals'], $_var->print[0] );
            $this->assertEquals( $var['format'], $_var->print[2] );
            foreach ( $var['data'] as $case => $value ) {
                $this->assertEquals(rtrim($value), $reader->data[$case][$index]);
            }
            $index += $var['width'] > 0 ? Record\Variable::widthToOcts( $var['width'] ) : 1;
        }
    }


    /**
     * @return array
     */
    public function dataProvider()
    {
        $data = [
            'header' => [
                'recType' => '$FL2',
                'prodName' => '@(#) SPSS DATA FILE test',
                'layoutCode' => 2,
                'nominalCaseSize' => 0,
                'casesCount' => mt_rand( 2, 10 ),
                'compression' => 1,
                'weightIndex' => 0,
                'bias' => 100,
                'creationDate' => '13 Feb 89',
                'creationTime' => '13:13:13',
                'fileLabel' => 'test file',
            ],
            'variables' => []
        ];
        $count = mt_rand( 1, 50);
        for ( $i = 0; $i < $count; $i++ ) {
            $isNumeric = rand( 0, 1 );
            $var = [
                'name' => 'VAR' . $i,
                'label' => 'Label - ' . $i,
                'width' => 0,
                'format' => Variable::FORMAT_TYPE_A,
                'columns' => mt_rand( 0, 100 ),
                'align' => mt_rand( 0, 2 ),
                'measure' => mt_rand( 1, 3 ),
                'data' => [],
            ];
            if ( $isNumeric ) {
                $var['decimals'] = mt_rand( 0, 2 );
                $var['format'] = Variable::FORMAT_TYPE_F;
                for ( $c = 0; $c < $data['header']['casesCount']; $c++ ) {
                    $var['data'][$c] = mt_rand( 1, 99999 ) . '.' . mt_rand( 1, 99999 );
                }
            } else {
                $var['width'] = mt_rand( 2, 1500 );
                $var['decimals'] = 0;
                for ( $c = 0; $c < $data['header']['casesCount']; $c++ ) {
                    $var['data'][$c] = $this->generateRandomString( mt_rand( 0,
                            $var['width'] ) );
                }
            }
            $data['header']['nominalCaseSize'] += Record\Variable::widthToOcts( $var['width'] );
            $data['variables'][] = $var;
        }
        return $data;
    }


    /**
     * @param int $length
     * @return string
     */
    private function generateRandomString( $length = 10 )
    {
        $characters = ' 0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen( $characters );
        $randomString = '';
        for ( $i = 0; $i < $length; $i++ ) {
            $randomString .= $characters[rand( 0, $charactersLength - 1 )];
        }
        return $randomString;
    }

}