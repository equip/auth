<?php
namespace Spark\Auth\Exception;

/**
 * Exception that occurs when a user does not provide authentication
 * credentials.
 */
class UnauthorizedException extends AuthException
{
    public function __construct(
        $message = 'No authentication token was specified',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return 401;
    }
}
