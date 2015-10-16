<?php
namespace SparkTests\Auth\Jwt;

use DateTime;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256 as Signer;
use Lcobucci\JWT\ValidationData;
use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Spark\Auth\Exception\InvalidException;
use Spark\Auth\Jwt\Configuration;
use Spark\Auth\Jwt\LcobucciGenerator;
use Spark\Auth\Jwt\LcobucciParser;
use Spark\Auth\Token;

class LcobucciParserTest extends TestCase
{
    /**
     * @var string
     */
    protected $subject;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * @var integer
     */
    protected $ttl = 3600;

    protected function setUp()
    {
        $this->subject = 'user-1';

        $this->request = Phake::mock(Request::class);
        Phake::when($this->request)->getUri()->thenReturn('uri');

        $this->builder = new Builder;

        $this->signer = new Signer;
    }

    public function testParseTokenWithValidToken()
    {
        $timestamp = 1443719236;
        $config = $this->getMockConfiguration($timestamp);
        $generator = $this->getGenerator($config);
        $parser = $this->getParser($config, $timestamp);

        $token = $generator->getToken(['sub' => $this->subject]);
        $parsed = $parser->parseToken($token);
        $this->assertInstanceOf(Token::class, $parsed);
        $this->assertSame($token, $parsed->getToken());
        $this->assertSame($this->getParsed($timestamp), $parsed->getMetadata());
    }

    public function testParseWithExpiredToken()
    {
        $pastTimestamp = 1443719236;
        $pastConfig = $this->getMockConfiguration($pastTimestamp);
        $generator = $this->getGenerator($pastConfig);

        $currentTimestamp = time();
        $currentConfig = $this->getMockConfiguration($currentTimestamp);
        $parser = $this->getParser($currentConfig, $currentTimestamp);

        $token = $generator->getToken(['sub' => $this->subject]);

        try {
            $parsed = $parser->parseToken($token);
            $this->fail('Expected exception was not thrown');
        } catch (InvalidException $e) {
            $this->assertSame(InvalidException::CODE_TOKEN_EXPIRED, $e->getCode());
        }
    }

    /**
     * @param integer $timestamp
     * @return Configuration
     */
    protected function getMockConfiguration($timestamp)
    {
        $key = '8122175af707e5946e40d06484ed9584';
        $config = Phake::mock(Configuration::class);
        Phake::when($config)->getPublicKey()->thenReturn($key);
        Phake::when($config)->getPrivateKey()->thenReturn($key);
        Phake::when($config)->getTtl()->thenReturn($this->ttl);
        Phake::when($config)->getTimestamp()->thenReturn($timestamp);
        return $config;
    }

    /**
     * @param Configuration $config
     * @param integer $timestamp
     * @return LcobucciParser
     */
    protected function getParser(Configuration $config, $timestamp)
    {
        return new LcobucciParser(
            new Parser,
            $this->signer,
            $config,
            new ValidationData($timestamp)
        );
    }

    /**
     * @param Configuration $config
     * @return LcobucciGenerator
     */
    protected function getGenerator(Configuration $config)
    {
        return new LcobucciGenerator(
            $this->request,
            $this->builder,
            $this->signer,
            $config
        );
    }

    /**
     * @param integer $timestamp
     * @return array
     */
    protected function getParsed($timestamp)
    {
        return [
            'sub' => $this->subject,
            'iss' => 'uri',
            'iat' => $timestamp,
            'exp' => $timestamp + $this->ttl,
        ];
    }
}
