<?php
namespace Spark\Auth\Token;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Extracts an authentication token from a request header.
 */
class HeaderExtractor implements ExtractorInterface
{
    /**
     * @var string
     */
    protected $header;

    /**
     * @param string $header Name of the request header
     */
    public function __construct($header)
    {
        $this->header = (string) $header;
    }

    /**
     * @inheritDoc
     */
    public function getToken(ServerRequestInterface $request)
    {
        $token = current($request->getHeader($this->header));
        return $token ?: null;
    }
}
