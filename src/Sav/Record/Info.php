<?php

namespace SPSS\Sav\Record;

use SPSS\Buffer;
use SPSS\Exception;
use SPSS\Sav\Record;


abstract class Info
    extends Record
{
    const TYPE = 7;
    const SUBTYPE = 0;

    /**
     * Size of each piece of data in the data part, in bytes.
     *
     * @var int
     */
    protected $dataSize = 1;

    /**
     * Number of pieces of data in the data part.
     *
     * @var int
     */
    protected $dataCount = 0;


    /**
     * Read from buffer.
     *
     * @param Buffer $buffer
     */
    public function read( Buffer $buffer )
    {
//        $type = $buffer->readInt();
//        if (self::TYPE !== $type) {
//            throw new Exception(strtr("Found unexpected type {found}, expected {expected}", [
//                '{found}' => $type,
//                '{expected}' => self::TYPE
//            ]));
//        }
//
//        $subtype = $buffer->readInt();
//        if (static::SUBTYPE !== $subtype) {
//            throw new Exception(strtr("Found unexpected subtype {found}, expected {expected}", [
//                '{found}' => $subtype,
//                '{expected}' => static::SUBTYPE
//            ]));
//        }
        $this->dataSize = $buffer->readInt();
        $this->dataCount = $buffer->readInt();
    }


    /**
     * Write to buffer.
     *
     * @param Buffer $buffer
     */
    public function write( Buffer $buffer )
    {
        $buffer->writeInt( self::TYPE );
        $buffer->writeInt( static::SUBTYPE );
        $buffer->writeInt( $this->dataSize );
        $buffer->writeInt( $this->dataCount );
    }

}
