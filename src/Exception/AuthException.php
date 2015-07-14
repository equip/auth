<?php
namespace Spark\Auth\Exception;

class AuthException extends \Exception
{
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        if (!$message) {
            $message = 'There was an error authenticating.';
        }
        parent::__construct($message, $code, $previous);
    }
}