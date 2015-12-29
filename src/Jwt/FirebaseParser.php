<?php
namespace Spark\Auth\Jwt;

use Firebase\JWT\BeforeValidException;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
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
        } catch (\Exception $e) {
            if ($e instanceof ExpiredException) {
                throw InvalidException::tokenExpired($e);
            } elseif (
                $e instanceof SignatureInvalidException ||
                $e instanceof BeforeValidException
            ) {
                throw InvalidException::tokenInvalid($e);
            } elseif (
                $e instanceof \DomainException ||
                $e instanceof \InvalidArgumentException ||
                $e instanceof \UnexpectedValueException
            ) {
                throw InvalidException::tokenMalformed($e);
            } else {
                throw new InvalidException(
                    'Unknown exception: ' . $e->getMessage(),
                    0,
                    $e
                );
            }
        }
        return new Token($token, $metadata);
    }
}
