<?php
namespace Equip\Auth\Jwt;

use Equip\Auth\Exception\InvalidException;
use Equip\Auth\Token;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer;
use Lcobucci\JWT\Token as ParsedToken;
use Lcobucci\JWT\ValidationData;

/**
 * Parser for JWT authentication token strings that uses the lcobucci/jwt
 * library.
 */
class LcobucciParser implements ParserInterface
{
    /**
     * @var Parser $parser
     */
    protected $parser;

    /**
     * @var Signer
     */
    protected $signer;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @var ValidationData
     */
    protected $validation;

    /**
     * @param Parser $parser
     * @param Signer $signer
     * @param Configuration $config
     * @param ValidationData $validation
     */
    public function __construct(
        Parser $parser,
        Signer $signer,
        Configuration $config,
        ValidationData $validation
    )
    {
        $this->parser = $parser;
        $this->signer = $signer;
        $this->config = $config;
        $this->validation = $validation;
    }

    /**
     * @inheritDoc
     */
    public function parseToken($token)
    {
        $parsed = $this->getParsedToken($token);
        $this->verifyParsedToken($parsed);
        $this->validateParsedToken($parsed);
        $metadata = $this->getTokenMetadata($parsed);
        return new Token($token, $metadata);
    }

    /**
     * @param string $token
     * @return ParsedToken
     * @throws InvalidException if token can't be parsed
     */
    protected function getParsedToken($token)
    {
        try {
            return $this->parser->parse((string) $token);
        } catch (\InvalidArgumentException $e) {
            throw InvalidException::tokenUnparseable($token, $e);
        }
    }

    /**
     * @param ParsedToken $parsed
     * @throws InvalidException if token validation fails
     */
    protected function verifyParsedToken(ParsedToken $parsed)
    {
        if ($parsed->verify($this->signer, $this->config->getPublicKey())) {
            return;
        }
        throw InvalidException::invalidSignature((string) $parsed);
    }

    /**
     * @param ParsedToken $parsed
     * @throws InvalidException if token validation fails
     */
    protected function validateParsedToken(ParsedToken $parsed)
    {
        if ($parsed->validate($this->validation)) {
            return;
        }
        throw InvalidException::invalidToken((string) $parsed);
    }

    /**
     * @param ParsedToken $parsed
     * @return array
     */
    protected function getTokenMetadata(ParsedToken $parsed)
    {
        $metadata = [];
        foreach ($parsed->getClaims() as $name => $claim) {
            $metadata[$name] = $claim->getValue();
        }
        return $metadata;
    }
}
