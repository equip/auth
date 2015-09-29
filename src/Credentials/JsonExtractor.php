<?php
namespace Spark\Auth\Credentials;

use Psr\Http\Message\ServerRequestInterface;
use Spark\Auth\Credentials;

/**
 * Extracts credentials from top-level properties of a JSON request body.
 */
class JsonExtractor implements ExtractorInterface
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $password;

    /**
     * @param string $identifier Name of the identifier JSON property
     * @param string $password Name of the password JSON property
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
        $json = json_decode($request->getBody()->getContents(), true);

        if (empty($json[$this->identifier]) || empty($json[$this->password])) {
            return null;
        }

        return new Credentials($json[$this->identifier], $json[$this->password]);
    }
}
