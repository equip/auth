<?php
namespace Spark\Auth\Exception;

/**
 * Exception that occurs when a user specifies an authentication token that is
 * invalid.
 */
class InvalidException extends AuthException
{
    const CODE_TOKEN_EXPIRED = 1;
    const CODE_TOKEN_INVALID = 2;
    const CODE_CREDENTIALS_INVALID = 3;

    public function __construct(
        $message = 'The token being used is invalid',
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return 403;
    }
}
