<?php
namespace EquipTests\Auth\Jwt;

use Equip\Auth\Exception\InvalidException;
use Equip\Auth\Jwt\Configuration;
use Equip\Auth\Jwt\FirebaseGenerator;
use Equip\Auth\Jwt\FirebaseParser;
use Equip\Auth\Token;
use PHPUnit_Framework_TestCase as TestCase;
use Phake;
use Psr\Http\Message\ServerRequestInterface;

class FirebaseParserTest extends TestCase
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var array
     */
    protected $parsed;

    protected function setUp()
    {
        $uri = 'uri';
        $this->request = Phake::mock(ServerRequestInterface::class);
        Phake::when($this->request)->getUri()->thenReturn($uri);

        $timestamp = time();
        $key = '8122175af707e5946e40d06484ed9584';
        $ttl = 3600;
        $algorithm = 'HS256';
        $this->config = Phake::mock(Configuration::class);
        Phake::when($this->config)->getPublicKey()->thenReturn($key);
        Phake::when($this->config)->getTtl()->thenReturn($ttl);
        Phake::when($this->config)->getTimestamp()->thenReturn($timestamp);
        Phake::when($this->config)->getAlgorithm()->thenReturn($algorithm);

        $this->parsed = [
            'sub' => 'user-1',
            'iss' => $uri,
            'iat' => $timestamp,
            'exp' => $timestamp + $ttl,
        ];
    }

    public function testParseTokenWithExpiredToken()
    {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1cmkiLCJpYXQiOiIxNDQzNzE5MjM2IiwiZXhwIjoxNDQzNzIyODM2LCJzdWIiOiJ1c2VyLTEifQ.YquvWRtH72muTv1hCCKH4HBkBhhCm1wTG0MpnyC-pvQ';

        $this->setExpectedException(
            InvalidException::class,
            '',
            InvalidException::CODE
        );

        $parser = new FirebaseParser($this->config);
        $parser->parseToken($token);
    }

    public function testParseTokenWithDefaultAlgorithm()
    {
        $this->parseToken(
            new FirebaseGenerator(
                $this->request,
                $this->config
            ),
            new FirebaseParser($this->config)
        );
    }

    public function testParseTokenWithCustomAlgorithm()
    {
        $this->parseToken(
            new FirebaseGenerator(
                $this->request,
                $this->config,
                'HS512'
            ),
            new FirebaseParser($this->config, 'HS512')
        );
    }

    protected function parseToken(FirebaseGenerator $generator, FirebaseParser $parser)
    {
        $token_string = $generator->getToken(['sub' => $this->parsed['sub']]);
        $token = $parser->parseToken($token_string);

        $this->assertInstanceOf(Token::class, $token);
        $this->assertSame($token_string, $token->getToken());
        $this->assertSame($this->parsed, $token->getMetadata());
    }
}
