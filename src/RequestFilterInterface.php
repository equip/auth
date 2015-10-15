<?php
namespace Spark\Auth;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Determines whether a request should require authentication.
 */
interface RequestFilterInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return boolean TRUE if the request should require authentication,
     *         FALSE otherwise
     */
    public function __invoke(ServerRequestInterface $request);
}
