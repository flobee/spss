<?php

namespace SPSS\Sav\Record;

use SPSS\Buffer;
use SPSS\Sav\Record;


class Document
    extends Record
{
    const TYPE = 6;

    /**
     * @var array
     */
    public $lines = array();


    /**
     * Read from buffer.
     *
     * @param Buffer $buffer
     */
    public function read( Buffer $buffer )
    {
        $count = $buffer->readInt();
        for ( $i = 0; $i < $count; $i++ ) {
            $this->lines[] = $buffer->readString( 80 );
        }
    }


    /**
     * Write to buffer.
     *
     * @param Buffer $buffer
     */
    public function write( Buffer $buffer )
    {
        $buffer->writeInt( self::TYPE );
        $buffer->writeInt( count( $this->lines ) );
        foreach ( $this->lines as $line ) {
            $buffer->writeString( strval( $line ), 80 );
        }
    }

}
