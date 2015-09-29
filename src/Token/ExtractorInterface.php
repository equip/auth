<?php
namespace Spark\Auth\Token;

use Psr\Http\Message\ServerRequestInterface;

interface ExtractorInterface
{
    /**
     * Attempts to extract an authentication token from a request.
     *
     * - If a token is present, it is returned.
     * - If no token is present, null is returned.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return string|null
     */
    public function getToken(ServerRequestInterface $request);
}
