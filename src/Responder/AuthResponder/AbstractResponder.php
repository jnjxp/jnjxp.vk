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
 * @category  Responder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Responder\AuthResponder;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * AbstractResponder
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @abstract
 */
abstract class AbstractResponder
{

    /**
     * Login succes message
     *
     * @var string
     *
     * @access protected
     */
    protected $loginSuccess = 'You are logged in';

    /**
     * Logout success message
     *
     * @var string
     *
     * @access protected
     */
    protected $logoutSuccess = 'You have successfully logged out';

    /**
     * Login fail message
     *
     * @var string
     *
     * @access protected
     */
    protected $loginFail = 'Authentication failed';

    /**
     * Set Login Success Message
     *
     * @param string $message text of message
     *
     * @return $this
     *
     * @access public
     */
    public function setLoginSuccess($message)
    {
        $this->loginSuccess = $message;
    }

    /**
     * Set Login Fail Message
     *
     * @param string $message text of message
     *
     * @return $this
     *
     * @access public
     */
    public function setLoginFail($message)
    {
        $this->loginFail = $message;
    }

    /**
     * Set Logout Success Message
     *
     * @param string $message text of message
     *
     * @return $this
     *
     * @access public
     */
    public function setLogoutSuccess($message)
    {
        $this->logoutSuccess = $message;
    }

    /**
     * Respond
     *
     * @param Request  $request  PSR7 Request
     * @param Response $response PSR7 Response
     * @param Payload  $payload  Domain Payload
     *
     * @return Response
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response, Payload $payload)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->payload  = $payload;

        $method = $this->getMethodForPayload();
        $this->$method();
        return $this->response;
    }


    /**
     * Get method for payload
     *
     * @return string
     *
     * @access protected
     */
    protected function getMethodForPayload()
    {
        $method = str_replace('_', '', strtolower($this->payload->getStatus()));
        return method_exists($this, $method) ? $method : 'unknown';
    }

    /**
     * Login Success
     *
     * @return void
     *
     * @access protected
     */
    abstract protected function authenticated();

    /**
     * Logout Success
     *
     * @return void
     *
     * @access protected
     */
    abstract protected function success();

    /**
     * Login Failure
     *
     * @return mixed
     *
     * @access void
     */
    abstract protected function failure();
}
