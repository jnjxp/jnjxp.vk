<?php

namespace Jnjxp\Vk\Router;

use Aura\Auth\Auth;
use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;
use Jnjxp\Vk\AuthAwareTrait;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Require VALID status if true or non-VALID if false
 *
 * @see RuleInterface
 */
class AuthRouteRule implements RuleInterface
{
    use AuthAwareTrait;

    /**
     * Check that authentication status is allowed by the route
     *
     * @param Request $request PSR7 Server Request
     * @param Route   $route   Route
     *
     * @return bool
     *
     * @access public
     */
    public function __invoke(Request $request, Route $route)
    {
        $auth     = $this->getAuth($request);
        $required = $route->auth;

        // Require valid
        if (true === $required) {
            return $auth->isValid();
        }

        // Reqire invalid
        if (false === $required) {
            return ! $auth->isValid();
        }

        return true;
    }
}
