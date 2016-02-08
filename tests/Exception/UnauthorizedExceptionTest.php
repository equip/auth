<?php
namespace EquipTests\Auth\Exception;

use Equip\Auth\Exception\UnauthorizedException;
use Exception;

class UnauthorizedExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testNoToken()
    {
        $previous = new Exception('test');
        $exception = UnauthorizedException::noToken($previous);
        $this->assertSame('No authentication token was specified', $exception->getMessage());
        $this->assertSame(UnauthorizedException::CODE, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}
