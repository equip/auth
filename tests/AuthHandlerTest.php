<?php
namespace EquipTests\Auth;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Equip\Auth\AdapterInterface;
use Equip\Auth\AuthHandler;
use Equip\Auth\Credentials;
use Equip\Auth\Credentials\ExtractorInterface as CredentialsExtractor;
use Equip\Auth\RequestFilterInterface;
use Equip\Auth\Token;
use Equip\Auth\Token\ExtractorInterface as TokenExtractor;
use Equip\Auth\Exception\AuthException;
use Equip\Auth\Exception\InvalidException;
use Equip\Auth\Exception\UnauthorizedException;

class AuthHandlerTest extends TestCase
{

    const AUTH_TOKEN_STRING = 'token';

    /**
     * @var \Equip\Auth\Token\ExtractorInterface
     */
    protected $token;

    /**
     * @var \Equip\Auth\Credentials\ExtractorInterface
     */
    protected $credentials;

    /**
     * @var \Equip\Auth\Adapter\AdapterInterface
     */
    protected $adapter;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var \Equip\Auth\AuthHandler
     */
    protected $handler;

    /**
     * @var boolean
     */
    protected $next_called;

    protected function setUp()
    {
        $this->next_called = false;
        $this->token = Phake::mock(TokenExtractor::class);
        $this->credentials = Phake::mock(CredentialsExtractor::class);
        $this->adapter = Phake::mock(AdapterInterface::class);
        $this->request = Phake::mock(ServerRequestInterface::class);
        $this->response = Phake::mock(ResponseInterface::class);
        $this->handler = new AuthHandler(
            $this->token,
            $this->credentials,
            $this->adapter
        );
    }

    public function testTokenWithoutException()
    {
        $token = Phake::mock(Token::class);
        Phake::when($this->token)
            ->getToken($this->request)
            ->thenReturn(static::AUTH_TOKEN_STRING);

        Phake::when($this->adapter)
            ->validateToken(static::AUTH_TOKEN_STRING)
            ->thenReturn($token);

        Phake::when($this->request)
            ->withAttribute(AuthHandler::TOKEN_ATTRIBUTE, $token)
            ->thenReturn($this->request);

        $response = $this->handler->__invoke($this->request, $this->response, [$this, 'next']);

        $this->assertTrue($this->next_called);
        $this->assertSame($this->response, $response);
        Phake::verify($this->adapter)->validateToken(static::AUTH_TOKEN_STRING);
        Phake::verify($this->request)->withAttribute(AuthHandler::TOKEN_ATTRIBUTE, $token);

        Phake::verifyNoInteraction($this->credentials);
    }

    /**
     * @return array
     */
    public function dataProviderExceptions()
    {
        return [
            [new InvalidException],
            [new AuthException],
        ];
    }

    /**
     * @param AuthException $exception
     * @dataProvider dataProviderExceptions
     */
    public function testTokenWithException(AuthException $exception)
    {
        $token = Phake::mock(Token::class);
        Phake::when($this->token)
            ->getToken($this->request)
            ->thenReturn($token);

        Phake::when($this->adapter)
            ->validateToken($token)
            ->thenThrow($exception);

        try {
            $this->handler->__invoke($this->request, $this->response, [$this, 'next']);
            $this->fail('Expected exception was not thrown');
        } catch (AuthException $e) {
            $this->assertSame($exception, $e);
        }

        $this->assertFalse($this->next_called);
        Phake::verify($this->adapter)->validateToken($token);
        Phake::verifyNoInteraction($this->credentials);
    }

    public function testCredentialsWithoutException()
    {
        $token = Phake::mock(Token::class);
        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

        Phake::when($this->adapter)
            ->validateCredentials($credentials)
            ->thenReturn($token);

        Phake::when($this->request)
            ->withAttribute(AuthHandler::TOKEN_ATTRIBUTE, $token)
            ->thenReturn($this->request);

        $response = $this->handler->__invoke($this->request, $this->response, [$this, 'next']);

        $this->assertTrue($this->next_called);
        $this->assertSame($this->response, $response);
        Phake::verify($this->adapter)->validateCredentials($credentials);
    }

    /**
     * @param AuthException $exception
     * @dataProvider dataProviderExceptions
     */
    public function testCredentialsWithException(AuthException $exception)
    {
        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

        Phake::when($this->adapter)
            ->validateCredentials($credentials)
            ->thenThrow($exception);

        try {
            $this->handler->__invoke($this->request, $this->response, [$this, 'next']);
            $this->fail('Expected exception was not thrown');
        } catch (AuthException $e) {
            $this->assertSame($exception, $e);
        }

        $this->assertFalse($this->next_called);
        Phake::verify($this->adapter)->validateCredentials($credentials);
    }

    public function testUnauthorizedException()
    {
        try {
            $this->handler->__invoke($this->request, $this->response, [$this, 'next']);
            $this->fail('Expected exception was not thrown');
        } catch (UnauthorizedException $e) {
            // noop
        }

        $this->assertFalse($this->next_called);
        Phake::verify($this->token)->getToken($this->request);
        Phake::verify($this->credentials)->getCredentials($this->request);
    }

    public function testFilterFailure()
    {
        $filter = Phake::mock(RequestFilterInterface::class);
        Phake::when($filter)->__invoke($this->request)->thenReturn(false);

        $this->handler = new AuthHandler(
            $this->token,
            $this->credentials,
            $this->adapter,
            $filter
        );

        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

        $response = $this->handler->__invoke($this->request, $this->response, [$this, 'next']);

        $this->assertTrue($this->next_called);
        $this->assertSame($this->response, $response);
        Phake::verifyNoInteraction($this->token);
        Phake::verifyNoInteraction($this->credentials);
        Phake::verifyNoInteraction($this->adapter);
    }

    public function testFilterSuccess()
    {
        $filter = Phake::mock(RequestFilterInterface::class);
        Phake::when($filter)->__invoke($this->request)->thenReturn(true);

        $this->handler = new AuthHandler(
            $this->token,
            $this->credentials,
            $this->adapter,
            $filter
        );

        $token = Phake::mock(Token::class);
        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

        Phake::when($this->adapter)
            ->validateCredentials($credentials)
            ->thenReturn($token);

        Phake::when($this->request)
            ->withAttribute(AuthHandler::TOKEN_ATTRIBUTE, $token)
            ->thenReturn($this->request);

        $response = $this->handler->__invoke($this->request, $this->response, [$this, 'next']);

        $this->assertTrue($this->next_called);
        $this->assertSame($this->response, $response);
        Phake::verify($this->token)->getToken($this->request);
        Phake::verify($this->credentials)->getCredentials($this->request);
        Phake::verify($this->adapter)->validateCredentials($credentials);
    }

    public function next(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->assertSame($this->request, $request);
        $this->assertSame($this->response, $response);
        $this->next_called = true;
        return $this->response;
    }
}
