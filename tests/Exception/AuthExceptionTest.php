<?php
namespace SparkTests\Auth\Exception;

use PHPUnit_Framework_TestCase as TestCase;
use Spark\Auth\Exception\AuthException;

class AuthExceptionTest extends TestCase
{

    public function testConstruct()
    {
        $exception = new AuthException();
        $this->assertEquals('There was an error authenticating.', $exception->getMessage());

        $newException = new AuthException('Custom message.', 50);
        $this->assertEquals('Custom message.', $newException->getMessage());

    }
}