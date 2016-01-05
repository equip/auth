<?php
namespace Equip\Auth\Jwt;

/**
 * Interface for a parser of JWT authentication token strings.
 */
interface ParserInterface
{
    /**
     * @param string $token
     * @return \Equip\Auth\Token
     * @throws \Equip\Auth\Exception\InvalidException if the token is invalid
     *         or expired
     */
    public function parseToken($token);
}
