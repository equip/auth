<?php
namespace Equip\Auth\Exception;

use Exception;

/**
 * Exception that occurs when a user specifies an authentication token that is
 * invalid.
 */
class InvalidException extends AuthException
{
    const CODE = 403;

    /**
     * @param string $token
     * @param Exception $previous
     */
    public static function tokenExpired($token, Exception $previous = null)
    {
        return new static(
            sprintf('Provided auth token `%s` is expired', $token),
            static::CODE,
            $previous
        );
    }

    /**
     * @param string $token
     * @param Exception $previous
     */
    public static function tokenUnparseable($token, Exception $previous = null)
    {
        return new static(
            sprintf('Provided auth token `%s` could not be parsed', $token),
            static::CODE,
            $previous
        );
    }

    /**
     * @param string $token
     * @param Exception $previous
     */
    public static function invalidSignature($token, Exception $previous = null)
    {
        return new static(
            sprintf('Signature of provided auth token `%s` is not valid', $token),
            static::CODE,
            $previous
        );
    }

    /**
     * @param string $token
     * @param Exception $previous
     */
    public static function invalidToken($token, Exception $previous = null)
    {
        return new static(
            sprintf('Provided auth token `%s` is expired or otherwise invalid', $token),
            static::CODE,
            $previous
        );
    }
}
