<?php
namespace SparkTests\Auth\Credentials;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Spark\Auth\Credentials;
use Spark\Auth\Credentials\BodyExtractor;

class BodyExtractorTest extends TestCase
{
    /**
     * @var BodyExtractor
     */
    private $extractor;

    protected function setUp()
    {
        $this->extractor = new BodyExtractor;
    }

    /**
     * @return array
     */
    public function dataProviderInvalidCredentials()
    {
        $data = [];

        // Not an array
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
        $this->assertCredentials($credentials, $identifier, $password);
    }

    public function testCustomFields()
    {
        $identifier = 'foo';
        $password = 'bar';
        $extractor = new BodyExtractor('user', 'pass');

        $request = $this->getRequest(['identifier' => $identifier, 'password' => $password]);
        $this->assertNull($extractor->getCredentials($request));

        $request = $this->getRequest(['user' => $identifier, 'pass' => $password]);
        $credentials = $extractor->getCredentials($request);
        $this->assertCredentials($credentials, $identifier, $password);
    }

    /**
     * @param mixed $body
     * @return ServerRequestInterface
     */
    private function getRequest($body)
    {
        $stream = Phake::mock(StreamInterface::class);
        $request = Phake::mock(ServerRequestInterface::class);

        Phake::when($request)
            ->getParsedBody()
            ->thenReturn($body);

        return $request;
    }

    /**
     * @param Credentials $credentials
     * @param string $identifier
     * @param string $password
     *
     * @return void
     */
    private function assertCredentials($credentials, $identifier, $password)
    {
        $this->assertInstanceOf(Credentials::class, $credentials);
        $this->assertSame($identifier, $credentials->getIdentifier());
        $this->assertSame($password, $credentials->getPassword());
    }
}
