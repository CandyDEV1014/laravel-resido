<?php

namespace Botble\Media\Storage\BunnyCDN\Exceptions;

use Exception;

/**
 * An exception thrown by BunnyCDNStorage
 */
class BunnyCDNStorageException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ': ' . $this->message . "\n";
    }
}
