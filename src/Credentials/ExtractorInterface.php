<?php
namespace Spark\Auth\Credentials;

use Psr\Http\Message\ServerRequestInterface;

interface ExtractorInterface
{
    /**
     * Attempts to extract user credentials from a request.
     *
     * - If user credentials are present, a corresponding Credentials instance
     *   is returned.
     * - If no credentials are present, null is returned.
     *
     * @param ServerRequestInterface $request
     * @return Credentials|null
     */
    public function getCredentials(ServerRequestInterface $request);
}
