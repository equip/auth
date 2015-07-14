<?php
namespace SparkTests\Auth\Exception;

use PHPUnit_Framework_TestCase as TestCase;
use Spark\Auth\Exception\InvalidException;

class InvalidExceptionTest extends TestCase
{

    public function testConstruct()
    {
        $exception = new InvalidException();
        $this->assertEquals('The token being used is invalid.', $exception->getMessage());

        $newException = new InvalidException('Custom message.', 50);
        $this->assertEquals('Custom message.', $newException->getMessage());

    }
}