<?php

namespace SPSS\Sav\Record\Info;

use SPSS\Buffer;
use SPSS\Sav\Record\Info;


class ExtendedNumberOfCases
    extends Info
{
    const SUBTYPE = 16;

    /**
     * @var double
     */
    public $ncases = 0;

    /**
     * @var int
     */
    protected $dataSize = 8;

    /**
     * @var int
     */
    protected $dataCount = 2;


    /**
     * @param Buffer $buffer
     */
    public function read( Buffer $buffer )
    {
        parent::read( $buffer );
        $buffer->readDouble();
        $this->ncases = $buffer->readDouble();
    }


    /**
     * @param Buffer $buffer
     */
    public function write( Buffer $buffer )
    {
        parent::write( $buffer );
        $buffer->writeDouble( 1 );
        $buffer->writeDouble( $this->ncases );
    }

}
