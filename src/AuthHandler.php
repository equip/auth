<?php
namespace Spark\Auth;

use Auryn\Injector;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthHandler
{

    protected $authenticator;
    protected $injector;

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