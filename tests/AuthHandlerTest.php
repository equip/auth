<?php
namespace SparkTests\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use Spark\Auth\AuthHandler;
use SparkTests\Auth\Fake\FakeAuthenticator;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class AuthHandlerTest extends TestCase
{

    const TOKEN = 'GVfYlzygb2v3x5YLBwxGU';

    public function testInvoke()
    {
        $authenticator = new FakeAuthenticator();
        $authenticator->fakeToken = static::TOKEN;

        $handler = new AuthHandler($authenticator);

        $request = ServerRequestFactory::fromGlobals()->withHeader('Auth', static::TOKEN);
        $response = new Response();

        $return = $handler($request, $response, function ($req, $resp) {
            return $resp;
        });

        $this->assertEquals($response, $return);
        $this->assertEquals(static::TOKEN, $authenticator->getToken());
    }
}