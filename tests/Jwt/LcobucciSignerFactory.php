<?php
namespace EquipTests\Auth\Jwt;

use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Signer\Hmac\Sha256 as Hs256;
use Lcobucci\JWT\Signer\Hmac\Sha384 as Hs384;
use Lcobucci\JWT\Signer\Hmac\Sha512 as Hs512;
use Lcobucci\JWT\Signer\Rsa\Sha256 as Rs256;
use PHPUnit_Framework_TestCase as TestCase;
use Equip\Auth\Jwt\LcobucciSignerFactory;

class LcobucciSignerFactoryTest extends TestCase
{
    /**
     * @var LcobucciSignerFactory
     */
    protected $factory;

    protected function setUp()
    {
        $this->factory = new LcobucciSignerFactory;
    }

    /**
     * @param string $algorithm
     * @param string $class
     * @dataProvider dataProviderSupportedAlgorithms
     */
    public function testGetWithSupportedAlgorithms($algorithm, $class)
    {
        $signer = $this->factory->get($algorithm);
        $this->assertInstanceOf($class, $signer);
    }

    /**
     * @return array
     */
    public function dataProviderSupportedAlgorithms()
    {
        return [
            ['HS256', Hs256::class],
            ['HS384', Hs384::class],
            ['HS512', Hs512::class],
            ['RS256', Rs256::class],
        ];
    }

    public function testGetWithUnsupportedAlgorithm()
    {
        try {
            $this->factory->get('foo');
            $this->fail('Expected exception not thrown');
        } catch (\DomainException $e) {
            $this->assertSame('Unsupported algorithm: foo', $e->getMessage());
        }
    }

    public function testAddWithInvalidClass()
    {
        try {
            $this->factory->add('foo', 'DateTime');
        } catch (\DomainException $e) {
            $this->assertSame('Invalid signer class: DateTime', $e->getMessage());
        }
    }

    public function testAddWithValidClass()
    {
        $signer = Phake::mock(Signer::class);
        $class = get_class($signer);
        $this->factory->add('foo', $class);
        $this->assertInstanceOf($class, $this->factory->get('foo'));
    }
}
