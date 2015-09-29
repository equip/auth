<?php

namespace Spark\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spark\Adr\MiddlewareInterface;
use Spark\Auth\AdapterInterface;
use Spark\Auth\Credentials\ExtractorInterface as CredentialsExtractor;
use Spark\Auth\Token\ExtractorInterface as TokenExtractor;
use Spark\Auth\Exception\UnauthorizedException;

class AuthHandler implements MiddlewareInterface
{
    /**
     * @var \Spark\Auth\Token\ExtractorInterface
     */
    protected $token;

    /**
     * @var \Spark\Auth\Credentials\ExtractorInterface
     */
    protected $credentials;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var RequestFilterInterface
     */
    protected $filter;

    /**
     * @param \Spark\Auth\Token\ExtractorInterface $token
     * @param \Spark\Auth\Credentials\ExtractorInterface $credentials
     * @param AdapterInterface $adapter
     * @param RequestFilterInterface $filter
     */
    public function __construct(
        TokenExtractor $token,
        CredentialsExtractor $credentials,
        AdapterInterface $adapter,
        RequestFilterInterface $filter = null
    ) {
        $this->token = $token;
        $this->credentials = $credentials;
        $this->adapter = $adapter;
        $this->filter = $filter;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return array
     */
    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        callable $next
    ) {
        if ($this->shouldFilter($request)) {
            if ($token = $this->token->getToken($request)) {
                $this->adapter->validateToken($token);
            } elseif ($credentials = $this->credentials->getCredentials($request)) {
                $this->adapter->validateCredentials($credentials);
            } else {
                throw new UnauthorizedException;
            }
        }

        return $next($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @return boolean
     */
    protected function shouldFilter(ServerRequestInterface $request)
    {
        if (!$this->filter) {
            return true;
        }
        return call_user_func($this->filter, $request);
    }
}
