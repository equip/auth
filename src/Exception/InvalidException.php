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
    const CODE_TOKEN_MALFORMED = 4;

    public static function tokenExpired(\Exception $e = null)
    {
        return new static(
            'The token being used is expired',
            static::CODE_TOKEN_EXPIRED,
            $e
        );
    }

    public static function tokenInvalid(\Exception $e = null)
    {
        return new static(
            'The token being used is invalid',
            static::CODE_TOKEN_INVALID,
            $e
        );
    }

    public static function credentialsInvalid(\Exception $e = null)
    {
        return new static(
            'The credentials being used are invalid',
            static::CODE_CREDENTIALS_INVALID,
            $e
        );
    }

    public static function tokenMalformed(\Exception $e = null)
    {
        return new static(
            'The token being used is malformed',
            static::CODE_TOKEN_MALFORMED,
            $e
        );
    }

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
