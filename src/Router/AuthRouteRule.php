<?php
/**
 * Voight-Kampff Authentication
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Router
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Router;

use Vperyod\AuthHandler\AuthRequestAwareTrait;

use Aura\Auth\Auth;
use Aura\Auth\Status;
use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Require VALID status if true or non-VALID if false
 *
 * @category Router
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see RuleInterface
 */
class AuthRouteRule implements RuleInterface
{
    use AuthRequestAwareTrait;

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
        $auth = $route->auth;
        if (null !== $auth) {
            $status = $this->getAuthStatus($request);
            if ($auth) {
                // true ? require valid
                return ($status === Status::VALID);
            }
            // false ? require non-valid
            return in_array($status, [Status::ANON, Status::EXPIRED, Status::IDLE]);
        }
        return true;
    }
}
