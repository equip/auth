<?php
namespace SparkTests\Auth\Token;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\Token\QueryExtractor;

class QueryExtractorTest extends TestCase
{
    /**
     * @var \Spark\Auth\Token\QueryExtractor
     */
    protected $extractor;

    protected function setUp()
    {
        $this->extractor = new QueryExtractor('al');
    }

    public function testNoParameters()
    {
        $request = $this->getRequest([]);
        $this->assertNull($this->extractor->getToken($request));
    }

    public function testWrongParameter()
    {
        $request = $this->getRequest(['foo' => 'bar']);
        $this->assertNull($this->extractor->getToken($request));
    }

    public function testCorrectParameter()
    {
        $token = 'foo';
        $request = $this->getRequest(['al' => $token]);
        $this->assertSame($token, $this->extractor->getToken($request));
    }

    protected function getRequest(array $query)
    {
        $request = Phake::mock(ServerRequestInterface::class);

        Phake::when($request)
            ->getQueryParams()
            ->thenReturn($query);

        return $request;
    }
}
