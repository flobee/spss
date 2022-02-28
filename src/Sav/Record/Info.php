<?php

namespace SPSS\Sav\Record;

use SPSS\Buffer;
use SPSS\Exception;
use SPSS\Sav\Record;

class Info extends Record implements \ArrayAccess
{
    const TYPE    = 7;
    const SUBTYPE = 0;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var int Size of each piece of data in the data part, in bytes
     */
    protected $dataSize = 1;

    /**
     * Number of pieces of data in the data part.
     *
     * @var int
     */
    protected $dataCount = 0;


    public function read( Buffer $buffer )
    {
        $this->dataSize  = $buffer->readInt();
        $this->dataCount = $buffer->readInt();
    }


    public function write( Buffer $buffer )
    {
        $buffer->writeInt( self::TYPE );
        $buffer->writeInt( static::SUBTYPE );
        $buffer->writeInt( $this->dataSize );
        $buffer->writeInt( $this->dataCount );
    }


    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }


    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists( $offset ): bool
    {
        return isset( $this->data[$offset] );
    }


    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet( $offset ): mixed
    {
        return $this->data[$offset];
    }


    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet( $offset, $value ): void
    {
        if ( null === $offset ) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }


    /**
     * @param mixed $offset
     */
    public function offsetUnset( $offset ): void
    {
        unset( $this->data[$offset] );
    }
}
