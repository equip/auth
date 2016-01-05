<?php
namespace EquipTests\Auth\Exception;

use Equip\Auth\Exception\InvalidException;

class InvalidExceptionTest extends ExceptionTestCase
{
    protected function getExceptionClass()
    {
        return InvalidException::class;
    }

    protected function getDefaultMessage()
    {
        return 'The token being used is invalid';
    }

    protected function getStatusCode()
    {
        return 403;
    }
}
