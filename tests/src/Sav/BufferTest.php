<?php

namespace SPSS\Tests;

use SPSS\Buffer;
use SPSS\Sav\Reader;
use SPSS\Sav\Record\Info\MachineFloatingPoint;
use SPSS\Sav\Variable;
use SPSS\Sav\Writer;


class BufferTest
    extends \PHPUnit_Framework_TestCase
{

    public function doubleProvider()
    {
        return [
            [1],
            [-1.7976931348623158E+308],
            [1.7976931348623158E+308],
        ];
    }

    /**
     * @dataProvider doubleProvider
     * @param $value
     */
    public function testDouble($value)
    {
        $buffer = Buffer::factory('', ['memory' => true]);
        $buffer->writeDouble($value);
        $buffer->rewind();
        $this->assertEquals($value, $buffer->readDouble(), '', 0.0001);
    }

    public function intProvider()
    {
        return [
            [0],
            [1],
            [3],
            [2^32 - 1],
            [-1 * 2^32 - 1],
        ];
    }

    /**
     * @dataProvider intProvider
     * @param $value
     */
    public function testInt($value)
    {
        $buffer = Buffer::factory('', ['memory' => true]);
        $buffer->writeInt($value);
        $buffer->rewind();
        $this->assertEquals($value, $buffer->readInt());
    }
}