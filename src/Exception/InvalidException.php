<?php
namespace Spark\Auth\Exception;

class InvalidException extends \Exception
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if (!$message) {
            $message = 'The token being used is invalid.';
        }
        parent::__construct($message, $code, $previous);
    }
}