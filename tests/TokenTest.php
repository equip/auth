<?php
namespace SparkTests\Auth;

use PHPUnit_Framework_TestCase as TestCase;
use Spark\Auth\Token;

class TokenTest extends TestCase
{
    public function testAccessors()
    {
        $string = 'foo';
        $metadata = ['bar' => 'baz'];
        $token = new Token($string, $metadata);
        $this->assertSame($string, $token->getToken());
        $this->assertSame($metadata['bar'], $token->getMetadata('bar'));
        $this->assertNull($token->getMetadata('baz'));
    }
}
