<?php
namespace Spark\Auth\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Spark\Auth\Jwt\Configuration;
use Spark\Auth\Exception\InvalidException;
use Spark\Auth\Token;

/**
 * Parser for JWT authentication token strings that uses the firebase/php-jwt
 * library.
 */
class FirebaseParser implements ParserInterface
{
    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @param Configuration $config
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function parseToken($token)
    {
        try {
            $metadata = (array) JWT::decode(
                (string) $token,
                $this->config->getPublicKey(),
                [$this->config->getAlgorithm()]
            );
        } catch (ExpiredException $e) {
            throw new InvalidException(
                'Token has expired: ' . $token,
                InvalidException::CODE_TOKEN_EXPIRED
            );
        }
        return new Token($token, $metadata);
    }
}
