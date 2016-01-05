<?php
namespace EquipTests\Auth\Exception;

use Equip\Auth\Exception\AuthException;

class AuthExceptionTest extends ExceptionTestCase
{
    protected function getExceptionClass()
    {
        return AuthException::class;
    }

    protected function getDefaultMessage()
    {
        return 'There was an error authenticating';
    }

    protected function getStatusCode()
    {
        return 500;
    }
}
