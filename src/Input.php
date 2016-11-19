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
 * @category  Config
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Psr\Http\Message\ServerRequestInterface as Request;

use Vperyod\AuthHandler\AuthRequestAwareTrait;

/**
 * Input
 *
 * @category Input
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class Input
{
    use AuthRequestAwareTrait;

    /**
     * Get input for login
     *
     * @param Request $request PSR7 HTTP Request
     *
     * @return array
     *
     * @access public
     */
    public function login(Request $request)
    {
        $post = $request->getParsedBody();
        return [
            'auth'     => $this->getAuth($request),
            'username' => isset($post['username']) ? $post['username'] : null,
            'password' => isset($post['password']) ? $post['password'] : null,
        ];
    }

    /**
     * Get input for logout
     *
     * @param Request $request DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function logout(Request $request)
    {
        return [
            'auth' => $this->getAuth($request),
        ];
    }
}
