<?php
namespace EquipTests\Auth\Jwt;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Equip\Auth\Jwt\Configuration;
use Equip\Auth\Jwt\FirebaseGenerator;

class FirebaseGeneratorTest extends TestCase
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /** 
     * @var Configuration
     */
    protected $config;

    protected function setUp()
    {
        $this->request = Phake::mock(ServerRequestInterface::class);
        Phake::when($this->request)->getUri()->thenReturn('uri');
        $timestamp = 1443719236;
        $key = '8122175af707e5946e40d06484ed9584';
        $ttl = 3600;
        $this->config = Phake::mock(Configuration::class);
        Phake::when($this->config)->getPublicKey()->thenReturn($key);
        Phake::when($this->config)->getTtl()->thenReturn($ttl);
        Phake::when($this->config)->getTimestamp()->thenReturn($timestamp);
    }

    public function testGetTokenWithDefaultAlgorithm()
    {
        Phake::when($this->config)->getAlgorithm()->thenReturn('HS256');

        $generator = new FirebaseGenerator(
            $this->request,
            $this->config
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1c2VyLTEiLCJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNn0.GDHf_4g2EYwkBAr5RQvkmiMHXvNkbjgmQoe1wLc4pfM',
            $generator->getToken(['sub' => 'user-1'])
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiJ1c2VyLTIiLCJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNn0.YBibGrGQPAOFyAe-NbMQhogvJsRPHR45zdlUolk-68I',
            $generator->getToken(['sub' => 'user-2'])
        );
    }

    public function testGetTokenWithCustomAlgorithm()
    {
        Phake::when($this->config)->getAlgorithm()->thenReturn('HS512');

        $generator = new FirebaseGenerator(
            $this->request,
            $this->config
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ1c2VyLTEiLCJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNn0.dUp_wNsYyAPE8HVyvpMgGwBR-jpZsdn2D0fEutV8Rmlq3Jpsgcbh1TX3ImK8KJm2qxMRxu1JY4hxepO-HkaxxA',
            $generator->getToken(['sub' => 'user-1'])
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJ1c2VyLTIiLCJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNn0.1Q8JBWJIum6EQoxl6cDQeoruqrjY8_kfA5q1ezZhyt5Z-oXsgtMv0KBa2qTQLjJMNk00OAl1WGLSWaEFD103hQ',
            $generator->getToken(['sub' => 'user-2'])
        );
    }
}
