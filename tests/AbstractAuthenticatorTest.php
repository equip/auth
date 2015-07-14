<?php
namespace SparkTests\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use SparkTests\Auth\Fake\FakeAuthenticator;
use SparkTests\Auth\Fake\FakePermissions;

class AbstractAuthenticatorTest extends TestCase
{

    /**
     * @var FakeAuthenticator
     */
    protected $authenticator;

    public function setUp()
    {
        $this->authenticator = new FakeAuthenticator();
    }

    /**
     * @expectedException \Spark\Auth\Exception\InvalidException
     */
    public function testInvalidException()
    {
        $this->authenticator->setToken('wrong_token');
        $this->authenticator->ensureValid();
    }

    /**
     * @expectedException \Exception
     * @expectedExceptionCode 60
     */
    public function testGetPermissionsException()
    {

        $this->authenticator->getPermissions();

    }

    public function testGetPermissions()
    {
        $permissions = new FakePermissions();
        $this->authenticator->setPermissions($permissions);

        $this->assertEquals($permissions, $this->authenticator->getPermissions());
    }

}