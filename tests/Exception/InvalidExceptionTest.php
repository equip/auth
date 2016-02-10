<?php
namespace EquipTests\Auth\Exception;

use Equip\Auth\Exception\InvalidException;
use Exception;

class InvalidExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $method
     * @param string $message
     * @dataProvider dataProviderMethods
     */
    public function testMethods($method, $message)
    {
        $previous = new Exception('test');
        $exception = InvalidException::$method('token', $previous);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(InvalidException::CODE, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * @inheritDoc
     */
    public function dataProviderMethods()
    {
        return [
            [
                'tokenExpired',
                'Provided auth token `token` is expired',
            ],
            [
                'tokenUnparseable',
                'Provided auth token `token` could not be parsed',
            ],
            [
                'invalidSignature',
                'Signature of provided auth token `token` is not valid',
            ],
            [
                'invalidToken',
                'Provided auth token `token` is expired or otherwise invalid',
            ],
        ];
    }
}
