<?php
namespace SparkTests\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use Spark\Auth\Credentials;

class CredentialsTest extends TestCase
{
    public function testAccessors()
    {
        $identifier = 'foo';
        $password = 'bar';
        $credentials = new Credentials($identifier, $password);
        $this->assertSame($identifier, $credentials->getIdentifier());
        $this->assertSame($password, $credentials->getPassword());
    }
}
