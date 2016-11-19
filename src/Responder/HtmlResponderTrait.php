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
 * @category  Rresponder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Responder;

use Vperyod\SessionHandler\SessionRequestAwareTrait;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * HtmlResponderTrait
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @trait
 */
trait HtmlResponderTrait
{
    use SessionRequestAwareTrait;

    /**
     * Dashboard
     *
     * @var string
     *
     * @access protected
     */
    protected $dashboard = '/';

    /**
     * Home
     *
     * @var string
     *
     * @access protected
     */
    protected $home = '/';

    /**
     * Login
     *
     * @var string
     *
     * @access protected
     */
    protected $login = '/login';

    /**
     * Session namespace for intent
     *
     * @var string
     *
     * @access protected
     */
    protected $intent = 'jnjxp/vk:intent';

    /**
     * Valid lifetime of intent in seconds
     *
     * @var int
     *
     * @access protected
     */
    protected $intentLifetime = 180;

    /**
     * Set Home (logout success destination)
     *
     * @param string $uri uri destination of logout success
     *
     * @return $this
     *
     * @access public
     */
    public function setHome($uri)
    {
        $this->home = $uri;
        return $this;
    }

    /**
     * Set Dashboard (login success destination)
     *
     * @param string $dashboard uri destination of login success
     *
     * @return $this
     *
     * @access public
     */
    public function setDashboard($dashboard)
    {
        $this->dashboard = $dashboard;
        return $this;
    }

    /**
     * SetLogin
     *
     * @param mixed $login DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Messages
     *
     * @return Messenger
     *
     * @access protected
     */
    protected function messages()
    {
        return $this->newMessenger($this->getRequest());
    }

    /**
     * Intent
     *
     * @return mixed
     *
     * @access protected
     */
    protected function intent()
    {
        return $this->getSession($this->getRequest())
            ->getSegment($this->intent);
    }

    /**
     * Redirect
     *
     * @param string $uri redirection location
     *
     * @return void
     *
     * @access protected
     */
    protected function redirect($uri)
    {
        $response = $this->getResponse()
            ->withStatus(302)
            ->withHeader('Location', $uri);
        $this->setResponse($response);
    }

    /**
     * Note intent
     *
     * @return mixed
     *
     * @access protected
     */
    protected function rememberIntent()
    {
        $request = $this->getRequest();
        if ('GET' == $request->getMethod()) {
            $url = (string) $request->getUri();
            $intent = $this->intent();
            $intent->set('url', $url);
            $intent->set('time', time());
        }
        return $this;
    }

    /**
     * Get Intent
     *
     * @return mixed
     *
     * @access protected
     */
    protected function getIntent()
    {
        $intent = $this->intent();
        $url = $intent->get('url', false);
        $then = $intent->get('time', 0);
        if ($url) {
            $age = time() - $then;
            if ($age > $this->intentLifetime) {
                $url = false;
            }
        }
        $intent->clear();
        return $url;
    }

    /**
     * Get Request
     *
     * @return Request
     *
     * @access protected
     */
    abstract protected function getRequest();

    /**
     * Get Response
     *
     * @return Response
     *
     * @access protected
     */
    abstract protected function getResponse();

    /**
     * Set Response
     *
     * @param Response $response Response
     *
     * @return $this
     *
     * @access protected
     */
    abstract protected function setResponse(Response $response);
}

