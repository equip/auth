<?php
namespace SparkTests\Auth\Exception;

use PHPUnit_Framework_TestCase as TestCase;

abstract class ExceptionTestCase extends TestCase
{
    /**
     * @return string
     */
    abstract protected function getExceptionClass();

    /**
     * @return string
     */
    abstract protected function getDefaultMessage();

    /**
     * @return integer
     */
    abstract protected function getStatusCode();

    public function testConstruct()
    {
        $class = $this->getExceptionClass();
        $exception = new $class;
        $this->assertEquals($this->getDefaultMessage(), $exception->getMessage());

        $message = 'Custom message.';
        $exception = new $class($message);
        $this->assertEquals($message, $exception->getMessage());
    }

    public function testGetStatusCode()
    {
        $class = $this->getExceptionClass();
        $exception = new $class;
        $this->assertEquals($this->getStatusCode(), $exception->getStatusCode());
    }
}
