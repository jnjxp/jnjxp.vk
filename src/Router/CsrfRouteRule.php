<?php
/**
 * Vk
 *
 * PHP version 7
 *
 * Copyright (C) 2019 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Router
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

namespace Jnjxp\Vk\Router;

use Aura\Auth\Auth;
use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;
use Jnjxp\Vk\Csrf\CsrfGuard;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * CsrfRouteRule
 *
 * @category Router
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 *
 * @see RuleInterface
 */
class CsrfRouteRule implements RuleInterface
{
    /**
     * Csrf guard
     *
     * @var CsrfGuard
     *
     * @access protected
     */
    protected $guard;

    /**
     * __construct
     *
     * @param CsrfGuard $guard | null
     *
     * @access public
     */
    public function __construct(CsrfGuard $guard = null)
    {
        $this->guard = $guard ?: new CsrfGuard();
    }

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
        if ($this->isSafeRequest($request)
            || $this->isSafeRoute($route)
        ) {
            return true;
        }

        return $this->guard->isValid($request);
    }

    /**
     * IsSafeRequest
     *
     * @param Request $request Request
     *
     * @return bool
     *
     * @access protected
     */
    protected function isSafeRequest(Request $request) : bool
    {
        return 'GET' === $request->getMethod();
    }

    /**
     * IsSafeRoute?
     *
     * @param Route $route Route
     *
     * @return bool
     *
     * @access protected
     */
    protected function isSafeRoute(Route $route) : bool
    {
        return $route->auth != true;
    }
}
