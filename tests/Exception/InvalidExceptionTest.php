<?php
namespace EquipTests\Auth\Exception;

use Equip\Auth\Exception\InvalidException;
use Exception;

class InvalidExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $method
     * @param string $message
     * @dataProvider dataProviderTokenMethods
     */
    public function testTokenMethods($method, $message)
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
    public function dataProviderTokenMethods()
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

    /**
     * @param string $method
     * @param string $message
     * @dataProvider dataProviderIdentifierMethods
     */
    public function testIdentifierMethods($method, $message)
    {
        $previous = new Exception('test');
        $exception = InvalidException::$method('username', $previous);
        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(InvalidException::CODE, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * @inheritDoc
     */
    public function dataProviderIdentifierMethods()
    {
        return [
            [
                'unknownIdentifier',
                'Specified identifier `username` is not recognized',
            ],
            [
                'incorrectPassword',
                'Incorrect password specified for identifier `username`',
            ],
        ];
    }
}
