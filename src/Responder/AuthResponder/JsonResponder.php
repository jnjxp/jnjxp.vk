<?php
/**
 * Jnjxp\Vk
 *
 * PHP version 5
 *
 * Copyright (C) 2016 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Responder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Responder\AuthResponder;

use Jnjxp\Vk\Responder\JsonResponderTrait;

/**
 * JsonResponder
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see AbstractResponder
 */
class JsonResponder extends AbstractResponder
{
    use JsonResponderTrait;

    /**
     * Login Success
     *
     * @return void
     *
     * @access protected
     */
    protected function authenticated()
    {
        $this->response = $this->response->withStatus(200);
        $this->jsonBody(['message' => $this->loginSuccess]);
    }

    /**
     * Logout Success
     *
     * @return void
     *
     * @access protected
     */
    protected function success()
    {
        $this->response = $this->response->withStatus(200);
        $this->jsonBody(['message' => $this->logoutSuccess]);
    }

    /**
     * Login Failure
     *
     * @return mixed
     *
     * @access void
     */
    protected function failure()
    {
        $this->response = $this->response->withStatus(401);
        $this->jsonBody(['message' => $this->loginFail]);
    }
}
