<?php
namespace Spark\Auth\Jwt;

/**
 * Interface for a generator of JWT authentication token strings.
 */
interface GeneratorInterface
{
    /**
     * @param array $claims
     * @return string
     */
    public function getToken(array $claims = []);
}
