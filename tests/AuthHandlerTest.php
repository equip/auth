<?php
namespace SparkTests\Auth;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\AdapterInterface;
use Spark\Auth\AuthHandler;
use Spark\Auth\Credentials;
use Spark\Auth\Credentials\ExtractorInterface as CredentialsExtractor;
use Spark\Auth\RequestFilterInterface;
use Spark\Auth\Token;
use Spark\Auth\Token\ExtractorInterface as TokenExtractor;
use Spark\Auth\Exception\AuthException;
use Spark\Auth\Exception\InvalidException;
use Spark\Auth\Exception\UnauthorizedException;

class AuthHandlerTest extends TestCase
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
     * @var \Spark\Auth\Adapter\AdapterInterface
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
     * @var \Spark\Auth\AuthHandler
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
            ->thenReturn($token);

        $response = $this->handler->__invoke($this->request, $this->response, [$this, 'next']);

        $this->assertTrue($this->next_called);
        $this->assertSame($this->response, $response);
        Phake::verify($this->adapter)->validateToken($token);
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
        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

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

        $credentials = Phake::mock(Credentials::class);
        Phake::when($this->credentials)
            ->getCredentials($this->request)
            ->thenReturn($credentials);

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
