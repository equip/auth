<?php
namespace Equip\Auth\Exception;

use Exception;

/**
 * Exception that occurs when a user does not provide authentication
 * credentials.
 */
class UnauthorizedException extends AuthException
{
    const CODE = 401;

    public static function noToken(Exception $previous = null)
    {
        return new static(
            'No authentication token was specified',
            static::CODE,
            $previous
        );
    }
}
