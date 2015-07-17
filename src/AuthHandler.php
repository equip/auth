<?php
namespace Spark\Auth;

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class AuthHandler
{

    protected $authenticator;

    public function __construct(AbstractAuthenticator $authenticator)
    {
        $this->authenticator = $authenticator;
    }

    public function __invoke(Request $request, Response $response, callable $next)
    {

        $this->authenticator->parseRequest($request);

        // Throw an error if the token is invalid.
        // Doesn't throw an error if the token is not set.
        $this->authenticator->ensureValid();

        return $next($request, $response);
    }
}