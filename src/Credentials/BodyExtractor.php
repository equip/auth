<?php

namespace Spark\Auth\Credentials;

use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\Credentials;

/**
 * Extracts credentials from top-level properties of a request body.
 */
class BodyExtractor implements ExtractorInterface
{
    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $password;

    /**
     * @param string $identifier
     *  Name of the property that identifies the user
     * @param string $password
     *  Name of the property that contains the user password
     */
    public function __construct($identifier = 'username', $password = 'password')
    {
        $this->identifier = $identifier;
        $this->password = $password;
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(ServerRequestInterface $request)
    {
        $body = $request->getParsedBody();

        if (empty($body[$this->identifier]) || empty($body[$this->password])) {
            return null;
        }

        return new Credentials($body[$this->identifier], $body[$this->password]);
    }
}
