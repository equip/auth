<?php
namespace SparkTests\Auth\Jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256 as Signer;
use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\Jwt\Configuration;
use Spark\Auth\Jwt\LcobucciGenerator;
use Spark\Auth\Jwt\LcobucciSignerFactory;

class LcobucciGeneratorTest extends TestCase
{
    /**
     * @var ServerRequestInterface
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
     * @var Configuration
     */
    protected $config;

    protected function setUp()
    {
        $this->request = Phake::mock(ServerRequestInterface::class);
        Phake::when($this->request)->getUri()->thenReturn('uri');

        $this->builder = new Builder;
        $this->signer = new Signer;

        $timestamp = 1443719236;
        $key = '8122175af707e5946e40d06484ed9584';
        $ttl = 3600;
        $this->config = Phake::mock(Configuration::class);
        Phake::when($this->config)->getPublicKey()->thenReturn($key);
        Phake::when($this->config)->getPrivateKey()->thenReturn($key);
        Phake::when($this->config)->getTtl()->thenReturn($ttl);
        Phake::when($this->config)->getTimestamp()->thenReturn($timestamp);
    }

    public function testGetToken()
    {
        $generator = new LcobucciGenerator(
            $this->request,
            $this->builder,
            $this->signer,
            $this->config
        );
        
        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1cmkiLCJzdWIiOiJ1c2VyLTEiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNn0.LvaP-PZkD1SZ10K7TPUWeXiAIoTsMaacAyMKkROJWhg',
            $generator->getToken('user-1')
        );
    }
}
