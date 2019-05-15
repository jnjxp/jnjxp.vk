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
 * @category  Helper
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk;

use Aura\Auth\Auth;

/**
 * AuthHelper
 */
class AuthHelper
{
    /**
     * Auth
     *
     * @var Auth
     *
     * @access protected
     */
    protected $auth;

    /**
     * __construct
     *
     * @param Auth $auth
     *
     * @access public
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * __invoke
     *
     * @return mixed
     *
     * @access public
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * __call
     *
     * @param mixed $method
     * @param mixed $params
     *
     * @return mixed
     *
     * @access public
     */
    public function __call($method, $params)
    {
        if (substr($method, 0, 3) == 'get'
            || substr($method, 0, 2) == 'is') {
            return $this->auth->$method();
        }
        throw new \BadMethodCallException($method);
    }
}

