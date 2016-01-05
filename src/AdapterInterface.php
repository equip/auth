<?php
namespace Equip\Auth;

use Equip\Auth\Credentials;

interface AdapterInterface
{
    /**
     * Validates a specified authentication token.
     *
     * - If the specified token is invalid, an InvalidException instance is
     *   thrown.
     * - If a valid token is present, a corresponding Token instance is
     *   returned.
     * - If for some reason the token cannot be validated, an AuthException
     *   instance is thrown.
     *
     * @param string $token
     * @return \Equip\Auth\Token
     * @throws \Equip\Auth\Exception\InvalidException if an invalid auth token
     *         is specified
     * @throws \Equip\Auth\Exception\AuthException if another error occurs
     *         during authentication
     */
    public function validateToken($token);

    /**
     * Validates a set of user credentials.
     *
     * - If the user credentials are valid, a new authentication token is
     *   created and a corresponding Token instance is returned.
     * - If the user credentials are invalid, an InvalidException instance is
     *   thrown.
     * - If for some reason the user credentials cannot be validated, an
     *   AuthException instance is thrown.
     *
     * @param \Equip\Auth\Credentials $credentials
     * @return \Equip\Auth\Token
     * @throws \Equip\Auth\Exception\InvalidException if an invalid auth token
     *         is specified
     * @throws \Equip\Auth\Exception\AuthException if another error occurs
     *         during authentication
     */
    public function validateCredentials(Credentials $credentials);
}
