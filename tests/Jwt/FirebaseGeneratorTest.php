<?php
namespace SparkTests\Auth\Jwt;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\Jwt\Configuration;
use Spark\Auth\Jwt\FirebaseGenerator;

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
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNiwic3ViIjoidXNlci0xIn0.GOXGA2n7CvKt9_1UoyGx7abV4HYKD6F8A2zk-wGwTw8',
            $generator->getToken('user-1')
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNiwic3ViIjoidXNlci0yIn0.cmqTqaQyiTJ0lI_PfkFKgCrGMwWkbXqp15UZuMO-rHU',
            $generator->getToken('user-2')
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
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNiwic3ViIjoidXNlci0xIn0.GnFhK7JaIxNqkFTRXFp8WpMre40tehHPY-B6KQhEjK7LGn6f8PO61y158llOpKLd6PqT_RDK9hHNx9Ka6rIGxA',
            $generator->getToken('user-1')
        );

        $this->assertSame(
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJpc3MiOiJ1cmkiLCJpYXQiOjE0NDM3MTkyMzYsImV4cCI6MTQ0MzcyMjgzNiwic3ViIjoidXNlci0yIn0.oEJ78pKdj5VR2bCb8lEWf8IqGcFruU2ypZwm3Cb_YIZXdNu5LXuWEd4S14VLzxaKPu785v8_mmwmMGjNOFwoxQ',
            $generator->getToken('user-2')
        );
    }
}
