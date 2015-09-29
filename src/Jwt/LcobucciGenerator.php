<?php
namespace Spark\Auth\Jwt;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer;
use Psr\Http\Message\RequestInterface;

/**
 * Generator for JWT authentication token strings that uses the lcobucci/jwt
 * library.
 */
class LcobucciGenerator implements GeneratorInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /** 
     * @var Signer
     */
    protected $signer;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @param RequestInterface $request
     * @param Builder $builder
     * @param Signer $signer
     * @param Configuration $config
     */
    public function __construct(
        RequestInterface $request,
        Builder $builder,
        Signer $signer,
        Configuration $config
    ) {
        $this->request = $request;
        $this->builder = $builder;
        $this->signer = $signer;
        $this->config = $config;
    }

    /**
     * @param string $subject
     * @return string
     */
    public function getToken($subject)
    {
        $issuer = (string) $this->request->getUri();
        $issued_at = $this->config->getTimestamp();
        $expiration = $issued_at + $this->config->getTtl();
        $key = $this->config->getPrivateKey();
        $token = $this->builder
            ->setIssuer($issuer)
            ->setSubject($subject)
            ->setIssuedAt($issued_at)
            ->setExpiration($expiration)
            ->sign($this->signer, $key)
            ->getToken();
        return (string) $token;
    }
}
