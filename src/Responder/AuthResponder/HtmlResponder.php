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

use Jnjxp\Vk\Responder\HtmlResponderTrait;

/**
 * HtmlResponder
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see AbstractResponder
 */
class HtmlResponder extends AbstractResponder
{
    use HtmlResponderTrait;

    /**
     * Login Success
     *
     * @return void
     *
     * @access protected
     */
    protected function authenticated()
    {
        $destination = $this->getIntent() ?: $this->dashboard;
        $this->messages()->success($this->loginSuccess);
        $this->redirect($destination);
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
        $this->messages()->success($this->logoutSuccess);
        $this->redirect($this->home);
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
        $this->messages()->warning($this->loginFail);
        $this->redirect($this->login);
    }

    /**
     * Error
     *
     * @return void
     *
     * @access protected
     */
    protected function error()
    {
        $this->response = $this->response
            ->withStatus(500)
            ->withHeader('Content-Type', 'text/plain');

        $exception = $this->payload->getOutput();

        $this->response->getBody()->write(
            sprintf(
                '%s: %s',
                get_class($exception),
                $exception->getMessage()
            )
        );
    }
}
