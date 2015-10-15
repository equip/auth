<?php
namespace SparkTests\Auth\Credentials;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Spark\Auth\Credentials;
use Spark\Auth\Credentials\JsonExtractor;

class JsonExtractorTest extends TestCase
{
    /**
     * @var JsonExtractor
     */
    protected $extractor;

    protected function setUp()
    {
        $this->extractor = new JsonExtractor;
    }

    /**
     * @return array
     */
    public function dataProviderInvalidCredentials()
    {
        $data = [];

        // Not a JSON object
        $data[] = ['foo'];

        // No identifier
        $data[] = [['password' => 'foo']];

        // No password
        $data[] = [['identifier' => 'foo']];

        return $data;
    }

    /**
     * @param mixed $body
     * @dataProvider dataProviderInvalidCredentials
     */
    public function testInvalidCredentials($body)
    {
        $request = $this->getRequest($body);
        $this->assertNull($this->extractor->getCredentials($request));
    }

    public function testValidCredentials()
    {
        $identifier = 'foo';
        $password = 'bar';
        $request = $this->getRequest(['username' => $identifier, 'password' => $password]);
        $credentials = $this->extractor->getCredentials($request);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertSame($identifier, $credentials->getIdentifier());
        $this->assertSame($password, $credentials->getPassword());
    }

    public function testCustomFields()
    {
        $identifier = 'foo';
        $password = 'bar';
        $extractor = new JsonExtractor('user', 'pass');

        $request = $this->getRequest(['identifier' => $identifier, 'password' => $password]);
        $this->assertNull($extractor->getCredentials($request));

        $request = $this->getRequest(['user' => $identifier, 'pass' => $password]);
        $credentials = $extractor->getCredentials($request);
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertSame($identifier, $credentials->getIdentifier());
        $this->assertSame($password, $credentials->getPassword());
    }

    /**
     * @param mixed $body
     * @return ServerRequestInterface
     */
    protected function getRequest($body)
    {
        $stream = Phake::mock(StreamInterface::class);
        $request = Phake::mock(ServerRequestInterface::class);

        Phake::when($request)
            ->getBody()
            ->thenReturn($stream);
        
        Phake::when($stream)
            ->getContents()
            ->thenReturn(json_encode($body));

        return $request;
    }
}
