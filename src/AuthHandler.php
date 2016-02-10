<?php

namespace Equip\Auth;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Equip\Auth\AdapterInterface;
use Equip\Auth\Credentials\ExtractorInterface as CredentialsExtractor;
use Equip\Auth\Token\ExtractorInterface as TokenExtractor;
use Equip\Auth\Exception\UnauthorizedException;

class AuthHandler
{
    const TOKEN_ATTRIBUTE = 'spark/auth:token';

    /**
     * @var \Equip\Auth\Token\ExtractorInterface
     */
    protected $token;

    /**
     * @var \Equip\Auth\Credentials\ExtractorInterface
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
     * @param \Equip\Auth\Token\ExtractorInterface $token
     * @param \Equip\Auth\Credentials\ExtractorInterface $credentials
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
                $authToken = $this->adapter->validateToken($token);
            } elseif ($credentials = $this->credentials->getCredentials($request)) {
                $authToken = $this->adapter->validateCredentials($credentials);
            } else {
                throw UnauthorizedException::noToken();
            }

            $request = $this->handleToken($request, $authToken);
        }

        return $next($request, $response);
    }

    /**
     * Handle the Token and put it in the request
     *
     * @param ServerRequestInterface $request
     * @param Token $token
     * @return ServerRequestInterface
     */
    protected function handleToken(ServerRequestInterface $request, Token $token)
    {
        return $request->withAttribute(static::TOKEN_ATTRIBUTE, $token);
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
