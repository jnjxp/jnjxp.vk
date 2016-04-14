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

namespace Jnjxp\Vk\Responder\RuleResponder;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Aura\Router\Route;

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
     * RequireAuth
     *
     * @var string
     *
     * @access protected
     */
    protected $requireAuth = 'Authentication required';

    /**
     * RequireAnon
     *
     * @var string
     *
     * @access protected
     */
    protected $requireAnon = 'You are already authenticated';

    /**
     * PSR7 Request
     *
     * @var Request
     *
     * @access protected
     */
    protected $request;

    /**
     * PSR7 Response
     *
     * @var Response
     *
     * @access protected
     */
    protected $response;

    /**
     * Failed Route
     *
     * @var Route
     *
     * @access protected
     */
    protected $route;

    /**
     * __invoke
     *
     * @param Request  $request  DESCRIPTION
     * @param Response $response DESCRIPTION
     * @param Route    $route    DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response, Route $route)
    {
        $this->request  = $request;
        $this->response = $response;
        $this->route    = $route;

        $method = $this->getMethodForFailedRoute();

        $this->$method();

        return $this->response;
    }

    /**
     * GetMethodForFailedRoute
     *
     * @return string
     *
     * @access protected
     */
    protected function getMethodForFailedRoute()
    {
        return ($this->route->auth)
            ? 'requireAuth'
            : 'requireAnon';
    }

    /**
     * Set RequireAuth Message
     *
     * @param mixed $message text of message
     *
     * @return mixed
     *
     * @access public
     */
    public function setRequireAuth($message)
    {
        $this->requireAuth = $message;
        return $this;
    }

    /**
     * Set RequireAnon Message
     *
     * @param mixed $message text of message
     *
     * @return mixed
     *
     * @access public
     */
    public function setRequireAnon($message)
    {
        $this->requireAnon = $message;
        return $this;
    }

    /**
     * Get Request
     *
     * @return Request
     *
     * @access protected
     */
    protected function getRequest()
    {
        return $this->request;
    }

    /**
     * Get Response
     *
     * @return Response
     *
     * @access protected
     */
    protected function getResponse()
    {
        return $this->response;
    }

    /**
     * Set Response
     *
     * @param Response $response Response
     *
     * @return $this
     *
     * @access protected
     */
    protected function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * RequireAuth
     *
     * @return mixed
     *
     * @access protected
     */
    abstract protected function requireAuth();

    /**
     * RequireAnon
     *
     * @return mixed
     *
     * @access protected
     */
    abstract protected function requireAnon();
}
