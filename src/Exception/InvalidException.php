<?php
namespace Spark\Auth\Exception;

class InvalidException extends \DomainException
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if (!$message) {
            $message = 'The token being used is invalid.';
        }
        parent::__construct($message, $code, $previous);
    }
}