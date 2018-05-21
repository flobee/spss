<?php

namespace SPSS\Tests;

use SPSS\Buffer;
use SPSS\Sav\Reader;
use SPSS\Sav\Record\Info\MachineFloatingPoint;
use SPSS\Sav\Variable;
use SPSS\Sav\Writer;


class MachineFloatingPointTest
    extends \PHPUnit_Framework_TestCase
{


    public function provider()
    {
        return [
//            [[
//                'sysmis' => -1,
//                'highest' => 5,
//                'lowest' => -10
//            ], [
//                'sysmis' => -1,
//                'highest' => 5,
//                'lowest' => -10
//            ]],

            [[], [
                'sysmis' => -1.7976931348623E+308,
                'highest' => 1.7976931348623E+308,
                'lowest' => -1.7976931348623E+308
            ]]
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testWriteRead(array $attributes, array $expected)
    {
        $subject = new MachineFloatingPoint();
        foreach($attributes as $key => $value) {
            $subject->{$key} = $value;
        }
        $buffer = Buffer::factory('', ['memory' => true]);
        $this->assertEquals(0, $buffer->position());
        $subject->write($buffer);
        $buffer->rewind();
        $buffer->skip(8);
        $read = MachineFloatingPoint::fill($buffer);
        $this->assertEquals(40, $buffer->position());
        foreach($expected as $key => $value) {
            $this->assertEquals(0, bcsub($value, $read->{$key}), "Wrong value received for $key, expected $value, got {$read->{$key}}");
        }
    }
}