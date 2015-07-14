<?php
namespace SparkTests\Auth\Fake;

use Spark\Auth\AbstractAuthenticator;
use Psr\Http\Message\ServerRequestInterface as Request;


class FakeAuthenticator extends AbstractAuthenticator
{

    public $fakeToken;

    public function getTokenFromRequest(Request $request)
    {
        return current( $request->getHeader("Auth") );
    }

    public function isValid()
    {
        return $this->getToken() == $this->fakeToken;
    }

}