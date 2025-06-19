<?php

namespace SumUp\Exceptions;

/**
 * Class SumUpReaderException
 *
 * @package SumUp\Exceptions
 */
class SumUpReaderException extends SumUpSDKException
{
    /**
     * Type of error that occurred.
     *
     * @var array
     */
    protected $typeError;

    /**
     * Error types.
     */

    const READER_NOT_CONNECTED = 'READER_OFFLINE';
    const READER_BUSY = 'READER_BUSY';

    /**
     * SumUpReaderException constructor.
     *
     * @param array $fields
     * @param int   $code
     * @param null  $previous
     */

    public function __construct($message, $HttpCode = 0, $type = null, $previous = null)
    {
        $this->typeError = $type;
        parent::__construct($message, $HttpCode, $previous);
    }

    /**
     * Returns the type of error that occurred.
     *
     * @return array
     */
    public function getTypeError()
    {
        return $this->typeError;
    }
}
