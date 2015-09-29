<?php
namespace Spark\Auth\Jwt;

/**
 * Interface for a generator of JWT authentication token strings.
 */
interface GeneratorInterface
{
    /**
     * @param string $subject
     * @return string
     */
    public function getToken($subject);
}
