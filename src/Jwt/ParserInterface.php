<?php
namespace Spark\Auth\Jwt;

/**
 * Interface for a parser of JWT authentication token strings.
 */
interface ParserInterface
{
    /**
     * @param string $token
     * @return \Spark\Auth\Token
     * @throws \Spark\Auth\Exception\InvalidException if the token is invalid
     *         or expired
     */
    public function parseToken($token);
}
