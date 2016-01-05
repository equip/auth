<?php
namespace Equip\Auth\Jwt;

use DateTime;
use Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface;

/**
 * Generator for JWT authentication token strings that uses the
 * firebase/php-jwt library.
 */
class FirebaseGenerator implements GeneratorInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @param RequestInterface $request
     * @param Configuration $config
     */
    public function __construct(
        RequestInterface $request,
        Configuration $config
    ) {
        $this->request = $request;
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
        $key = $this->config->getPublicKey();
        $algorithm = $this->config->getAlgorithm();
        $claims += [
            'iss' => $issuer,
            'iat' => $issued_at,
            'exp' => $expiration,
        ];
        return JWT::encode($claims, $key, $algorithm);
    }
}

