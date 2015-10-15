<?php
namespace Spark\Auth;

class Token
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $metadata;

    /**
     * @param string $token
     * @param array $metadata
     */
    public function __construct($token, array $metadata)
    {
         $this->token = $token;
         $this->metadata = $metadata;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getMetadata($key = null)
    {
        if ($key !== null) {
             return isset($this->metadata[$key]) ? $this->metadata[$key] : null;
        }
        return $this->metadata;
    }
}
