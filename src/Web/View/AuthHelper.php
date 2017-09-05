<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\View;

use Aura\Auth\Auth;

class AuthHelper
{

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function __invoke()
    {
        return $this;
    }

    public function __call($method, $params)
    {
        if (substr($method, 0, 3) == 'get'
            || substr($method, 0, 2) == 'is') {
            return $this->auth->$method();
        }
        throw new \BadMethodCallException($method);
    }
}

