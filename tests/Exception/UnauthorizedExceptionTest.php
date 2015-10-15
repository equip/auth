<?php
namespace SparkTests\Auth\Exception;

use Spark\Auth\Exception\UnauthorizedException;

class UnauthorizedExceptionTest extends ExceptionTestCase
{
    protected function getExceptionClass()
    {
        return UnauthorizedException::class;
    }

    protected function getDefaultMessage()
    {
        return 'No authentication token was specified';
    }

    protected function getStatusCode()
    {
        return 401;
    }
}
