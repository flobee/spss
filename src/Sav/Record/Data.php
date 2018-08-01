<?php

namespace SPSS\Sav\Record;

use SPSS\Buffer;
use SPSS\Exception;
use SPSS\Sav\Record;


class Data
    extends Record
{
    const TYPE = 999;

    /**
     * No-operation. This is simply ignored.
     */
    const OPCODE_NOP = 0;

    /**
     * End-of-file.
     */
    const OPCODE_EOF = 252;

    /**
     * Verbatim raw data. Read an 8-byte segment of raw data.
     */
    const OPCODE_RAW_DATA = 253;

    /**
     * Compressed whitespaces. Expand to an 8-byte segment of whitespaces.
     */
    const OPCODE_WHITESPACES = 254;

    /**
     * Compressed sysmiss value. Expand to an 8-byte segment of SYSMISS value.
     */
    const OPCODE_SYSMISS = 255;

    /**
     * @var array [case_index][var_index]
     */
    public $matrix = array();

    /**
     * @var array Latest opcodes data
     */
    private $opcodes = array();

    /**
     * @var int Current opcode index
     */
    private $opcodeIndex = 0;


    /**
     * Read from buffer.
     *
     * @param Buffer $buffer
     * @throws Exception
     */
    public function read( Buffer $buffer )
    {
        if ( $buffer->readInt() != 0 ) {
            $mesg = 'Error reading data record. Non-zero value found';
            throw new \InvalidArgumentException( $mesg );
        }

        if ( !isset( $buffer->context->variables ) ) {
            throw new \InvalidArgumentException( 'Variables required' );
        }

        if ( !isset( $buffer->context->header ) ) {
            throw new \InvalidArgumentException( 'Header required' );
        }

        if ( !isset( $buffer->context->info ) ) {
            throw new \InvalidArgumentException( 'Info required' );
        }

        $compressed = $buffer->context->header->compression;
        $bias = $buffer->context->header->bias;
        $casesCount = $buffer->context->header->casesCount;
        /** @var Variable[] $variables */
        $variables = $buffer->context->variables;

        $veryLongStrings = [];
        $_key = Record\Info\VeryLongString::SUBTYPE;
        if ( isset( $buffer->context->info[$_key] ) ) {
            $veryLongStrings = $buffer->context->info[$_key]->data;
        }

        $_key = Record\Info\MachineFloatingPoint::SUBTYPE;
        if ( isset( $buffer->context->info[$_key] ) ) {
            $sysmis = $buffer->context->info[$_key]->sysmis;
        } else {
            $sysmis = NAN;
        }

        $this->opcodeIndex = 8;

        for ( $case = 0; $case < $casesCount; $case++ ) {
            $parent = -1;
            $octs = 0;
            foreach ( $variables as $index => $var ) {
                if ( $var->width == 0 ) {
                    if ( !$compressed ) {
                        $this->matrix[$case][$index] = $buffer->readDouble();
                    } else {
                        $opcode = $this->readOpcode( $buffer );
                        switch ( $opcode )
                        {
                            case self::OPCODE_NOP;
                                break;

                            case self::OPCODE_EOF;
                                $mesg = 'Error reading data: unexpected end of '
                                    . 'compressed data file (cluster code 252)';
                                throw new Exception( $mesg );
                                break;

                            case self::OPCODE_RAW_DATA;
                                $this->matrix[$case][$index] = $buffer->readDouble();
                                break;

                            case self::OPCODE_SYSMISS;
                                $this->matrix[$case][$index] = $sysmis;
                                break;

                            default:
                                $this->matrix[$case][$index] = $opcode - $bias;
                                break;
                        }
                    }
                } else {
                    $val = '';
                    if ( !$compressed ) {
                        $val = $buffer->readString( 8 );
                    } else {
                        $opcode = $this->readOpcode( $buffer );
                        switch ( $opcode )
                        {
                            case self::OPCODE_NOP;
                                break;

                            case self::OPCODE_EOF;
                                $mesg = 'Error reading data: unexpected end of '
                                    . 'compressed data file (cluster code 252)';
                                throw new Exception( $mesg );
                                break;

                            case self::OPCODE_RAW_DATA;
                                $val = $buffer->readString( 8 );
                                break;

                            case self::OPCODE_WHITESPACES;
                                $val = '        ';
                                break;
                        }
                    }

                    if ( $parent >= 0 ) {
                        $this->matrix[$case][$parent] .= $val;
                        $octs--;
                        if ( $octs <= 0 ) {
                            $this->matrix[$case][$parent] = rtrim( $this->matrix[$case][$parent] );
                            $parent = -1;
                        }
                    } else {
                        if (isset( $veryLongStrings[$var->name] )) {
                            $width = $veryLongStrings[$var->name];
                        } else {
                            $width = $var->width;
                        }

                        if ( $width > 0 ) {
                            // Buffer::roundUp($width, 8) / 8) -1;
                            $octs = Record\Variable::widthToOcts( $width ) - 1;
                            if ( $octs > 0 ) {
                                $parent = $index;
                            } else {
                                $val = rtrim( $val );
                            }

                            $this->matrix[$case][$index] = $val;
                        }
                    }
                }
            }
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
        $buffer->writeInt( 0 );

        if ( !isset( $buffer->context->variables ) ) {
            throw new \InvalidArgumentException( 'Variables required' );
        }
        if ( !isset( $buffer->context->header ) ) {
            throw new \InvalidArgumentException( 'Header required' );
        }
        if ( !isset( $buffer->context->info ) ) {
            throw new \InvalidArgumentException( 'Info required' );
        }

        $compressed = $buffer->context->header->compression;
        $bias = $buffer->context->header->bias;
        $casesCount = $buffer->context->header->casesCount;
        /** @var Variable[] $variables */
        $variables = $buffer->context->variables;

        $_key = Record\Info\MachineFloatingPoint::SUBTYPE;
        if ( isset( $buffer->context->info[$_key] ) ) {
            $sysmis = $buffer->context->info[$_key]->sysmis;
        } else {
            $sysmis = NAN;
        }

        $dataBuffer = Buffer::factory( '', ['memory' => true] );

        for ( $case = 0; $case < $casesCount; $case++ ) {
            foreach ( $variables as $index => $var ) {
                $value = $this->matrix[$case][$index];

                if ( $var->width == 0 ) {
                    if ( !$compressed ) {
                        $buffer->writeDouble( $value );
                    } else {
                        if ( $value == $sysmis ) {
                            $this->writeOpcode(
                                $buffer, $dataBuffer, self::OPCODE_SYSMISS
                            );
                        } elseif ( $value >= 1 - $bias && $value <= 251 - $bias
                            && $value == (int) $value ) {
                            $this->writeOpcode(
                                $buffer, $dataBuffer, $value + $bias
                            );
                        } else {
                            $this->writeOpcode(
                                $buffer, $dataBuffer, self::OPCODE_RAW_DATA
                            );
                            $dataBuffer->writeDouble( $value );
                        }
                    }
                } else {
                    if ( !$compressed ) {
                        $buffer->writeString( $value, Buffer::roundUp( $var->width, 8 ) );
                    } else {
                        $offset = 0;
                        $segmentsCount = Record\Variable::widthToSegments( $var->width );

                        for ( $s = 0; $s < $segmentsCount; $s++ ) {
                            $segWidth = Record\Variable::segmentAllocWidth(
                                $var->width, $s
                            );
                            for ( $i = $segWidth; $i > 0; $i -= 8, $offset += 8 ) {
//                                $chunkSize = min($i, 8);
                                $val = mb_substr( $value, $offset, 8 );
                                if ( empty( $val ) ) {
                                    $this->writeOpcode(
                                        $buffer,
                                        $dataBuffer,
                                        self::OPCODE_WHITESPACES
                                    );
                                } else {
                                    $this->writeOpcode(
                                        $buffer,
                                        $dataBuffer,
                                        self::OPCODE_RAW_DATA
                                    );
                                    $dataBuffer->writeString( $val, 8 );
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->writeOpcode( $buffer, $dataBuffer, self::OPCODE_EOF );
    }


    /**
     * @param Buffer $buffer
     * @return int
     */
    public function readOpcode( Buffer $buffer )
    {
        if ( $this->opcodeIndex >= 8 ) {
            $this->opcodes = $buffer->readBytes( 8 );
            $this->opcodeIndex = 0;
        }

        return 0xFF & $this->opcodes[$this->opcodeIndex++];
    }


    /**
     * @param Buffer $buffer
     * @param Buffer $dataBuffer
     * @param int $opcode
     */
    public function writeOpcode( Buffer $buffer, Buffer $dataBuffer, $opcode )
    {
        if ( $this->opcodeIndex >= 8 || $opcode == self::OPCODE_EOF ) {
            foreach ( $this->opcodes as $opc ) {
                $buffer->write( chr( $opc ) );
            }
            $padding = max( 8 - count( $this->opcodes ), 0 );
            for ( $i = 0; $i < $padding; $i++ ) {
                $buffer->write( chr( self::OPCODE_NOP ) );
            }
            $this->opcodes = [];
            $this->opcodeIndex = 0;
            $dataBuffer->rewind();
            $buffer->writeStream( $dataBuffer->getStream() );
            $dataBuffer->truncate();
        }
        $this->opcodes[$this->opcodeIndex++] = 0xFF & $opcode;
    }

}
