<?php
namespace Spark\Auth;

class Credentials
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
     * @param string $password
     */
    public function __construct($identifier, $password)
    {
        $this->identifier = $identifier;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
