<?php
namespace EquipTests\Auth\Token;

use Phake;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Equip\Auth\Token\HeaderExtractor;

class HeaderExtractorTest extends TestCase
{
    /**
     * @var \Equip\Auth\Token\HeaderExtractor
     */
    protected $extractor;

    protected function setUp()
    {
        $this->extractor = new HeaderExtractor('W-Token');
    }

    public function dataProviderNoToken()
    {
        $data = [];

        // No token
        $data[] = ['W-Token', []];

        // Empty token
        $data[] = ['W-Token', ['']];

        // Wrong header
        $data[] = ['W-Key', ['foo']];

        return $data;
    }

    /**
     * @param string $header
     * @param array $values
     * @dataProvider dataProviderNoToken
     */
    public function testNoToken($header, $values)
    {
        $request = $this->getRequest($header, $values);
        $this->assertNull($this->extractor->getToken($request));
    }

    public function testValidToken()
    {
        $token = 'foo';
        $request = $this->getRequest('W-Token', [$token]);
        $this->assertSame($token, $this->extractor->getToken($request));
    }

    protected function getRequest($header, array $values)
    {
        $request = Phake::mock(ServerRequestInterface::class);

        Phake::when($request)
            ->getHeader
            ->thenReturnCallback(
                function($other_header) use ($header, $values) {
                    return $other_header === $header ? $values : [];
                }
            );

        return $request;
    }
}
