<?php
namespace EquipTests\Auth\Jwt;

use PHPUnit_Framework_TestCase as TestCase;
use Equip\Auth\Jwt\Configuration;

class ConfigurationTestCase extends TestCase
{
    public function testAccessors()
    {
        $publicKey = 'publicKey';
        $ttl = 3600;
        $algorithm = 'HS256';
        $timestamp = 1444244487;
        $privateKey = 'privateKey';
        $config = new Configuration($publicKey, $ttl, $algorithm, $timestamp, $privateKey);
        $this->assertSame($publicKey, $config->getPublicKey());
        $this->assertSame($ttl, $config->getTtl());
        $this->assertSame($timestamp, $config->getTimestamp());
        $this->assertSame($algorithm, $config->getAlgorithm());
        $this->assertSame($privateKey, $config->getPrivateKey());
    }
}
