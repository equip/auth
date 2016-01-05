<?php
namespace Equip\Auth\Jwt;

class Configuration
{
    /**
     * @var string
     */
    private $publicKey;

    /**
     * @var integer
     */
    private $ttl;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @var integer
     */
    private $timestamp;

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @param string $publicKey Public key used to sign the token
     * @param integer $ttl Time-to-live for the token, in seconds
     * @param string $algorithm
     * @param integer $timestamp UNIX timestamp used for token issuance and expiration
     * @param string $privateKey Private key used to sign the token
     */
    public function __construct($publicKey, $ttl, $algorithm, $timestamp = null, $privateKey = null)
    {
        $this->publicKey = (string) $publicKey;
        $this->ttl = (int) $ttl;
        $this->algorithm = (string) $algorithm;
        $this->timestamp = $timestamp ? (int) $timestamp : time();
        $this->privateKey = (string) $privateKey;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->algorithm;
    }

    /**
     * @return integer
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @return string|null
     */
    public function getPrivateKey()
    {
        return $this->privateKey;
    }
}
