<?php
namespace Equip\Auth\Exception;

/**
 * Parent class for authentication-related exceptions.
 */
class AuthException extends \Exception
{
    public function __construct(
        $message = 'There was an error authenticating',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return 500;
    }
}
