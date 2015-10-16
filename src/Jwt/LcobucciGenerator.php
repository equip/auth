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
     * @param array $claims
     * @return string
     */
    public function getToken(array $claims = [])
    {
        $issuer = (string) $this->request->getUri();
        $issued_at = $this->config->getTimestamp();
        $expiration = $issued_at + $this->config->getTtl();
        $key = $this->config->getPrivateKey();
        foreach ($claims as $name => $value) {
            $this->builder->set($name, $value);
        }
        $token = $this->builder
            ->setIssuer($issuer)
            ->setIssuedAt($issued_at)
            ->setExpiration($expiration)
            ->sign($this->signer, $key)
            ->getToken();
        return (string) $token;
    }
}
