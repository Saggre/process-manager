<?php

namespace Saggre\ProcessManager\Exception;

use Exception;
use Saggre\ProcessManager\Model\ProcessResult;

class ProcessRunException extends Exception
{
    public function __construct(
        public ?ProcessResult $result = null,
        string                $message = '',
        int                   $code = 0,
        Exception             $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
