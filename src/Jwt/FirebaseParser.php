<?php
namespace Equip\Auth\Jwt;

use Equip\Auth\Exception\InvalidException;
use Equip\Auth\Jwt\Configuration;
use Equip\Auth\Token;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;

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
            throw InvalidException::tokenExpired($token, $e);
        }
        return new Token($token, $metadata);
    }
}
