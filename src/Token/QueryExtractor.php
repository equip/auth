<?php
namespace Equip\Auth\Token;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Extracts an authentication token from a query string parameter.
 */
class QueryExtractor implements ExtractorInterface
{
    /**
     * @var string
     */
    protected $parameter;

    /**
     * @param string $parameter Name of the query string parameter
     */
    public function __construct($parameter)
    {
        $this->parameter = (string) $parameter;
    }

    /**
     * @inheritDoc
     */
    public function getToken(ServerRequestInterface $request)
    {
        $query = $request->getQueryParams();
        if (isset($query[$this->parameter])) {
            return $query[$this->parameter];
        }
        return null;
    }
}
