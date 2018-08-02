<?php

namespace SPSS\Tests;

use SPSS\Buffer;
use SPSS\Sav\Reader;
use SPSS\Sav\Record\Info\MachineFloatingPoint;
use SPSS\Sav\Variable;
use SPSS\Sav\Writer;
use PHPUnit;

class BufferTest
    extends PHPUnit\Framework\TestCase
{
    public function doubleProvider()
    {
        return array(
            array(1),
            // -1.7976931348623E+308
            array(-1.7976931348623158E+308),
        );
    }


    /**
     * @dataProvider doubleProvider
     * @param $value
     */
    public function testDouble( $value )
    {
        $buffer = Buffer::factory( '', array('memory' => true) );
        $buffer->writeDouble( $value );
        $buffer->rewind();
        $this->assertEquals( $value, $buffer->readDouble(), '', 0.0001 );
    }


    public function intProvider()
    {
        return array(
            array(0),
            array(1),
            array(3),
            array(2 ^ 32 - 1),
            array(-1 * 2 ^ 32 - 1),
        );
    }


    /**
     * @dataProvider intProvider
     * @param $value
     */
    public function testInt( $value )
    {
        $buffer = Buffer::factory( '', array('memory' => true) );
        $buffer->writeInt( $value );
        $buffer->rewind();
        $this->assertEquals( $value, $buffer->readInt() );
    }

}
