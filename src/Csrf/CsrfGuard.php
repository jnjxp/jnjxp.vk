<?php
/**
 * VK
 *
 * PHP version 7
 *
 * Copyright (C) 2019 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Csrf
 * @package   Jnjxp\Csrf
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

namespace Jnjxp\Vk\Csrf;

use Aura\Session\CsrfToken;
use Aura\Session\Session;
use Jnjxp\Vk\Middleware\ResumeSession;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * CsrfGuard
 *
 * @category Csrf
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 */
class CsrfGuard
{
    /**
     * Key
     *
     * @var string
     *
     * @access protected
     */
    protected $key;

    /**
     * __construct
     *
     * @param string $key csrf param
     *
     * @access public
     */
    public function __construct(string $key = '__csrf')
    {
        $this->key = $key;
    }

    /**
     * Is valid?
     *
     * @param Request $request Request
     *
     * @return bool
     *
     * @access public
     */
    public function isValid(Request $request) : bool
    {
        $token = $this->getToken($request);
        $data = $this->getData($request);
        return $token->isValid($data);
    }

    /**
     * Get Value
     *
     * @param Request $request Request
     *
     * @return string
     *
     * @access public
     */
    public function getValue(Request $request) : string
    {
        return $this->getToken($request)->getValue();
    }

    /**
     * Get form input element
     *
     * @param Request $request Request
     *
     * @return CsrfInput
     *
     * @access public
     */
    public function getInput(Request $request) : CsrfInput
    {
        $value = $this->getValue($request);
        return new CsrfInput($value, $this->key);
    }

    /**
     * Get Token
     *
     * @param Request $request Request
     *
     * @return CsrfToken
     *
     * @access public
     */
    public function getToken(Request $request) : CsrfToken
    {
        $this->getSession($request)->getCsrfToken();
    }

    /**
     * Get data
     *
     * @param Request $request Request
     *
     * @return string
     *
     * @access public
     */
    public function getData(Request $request) : string
    {
        $post = $request->getParsedBody();
        if (! is_array($post)) {
            return '';
        }
        return $post[$this->key] ?: '';
    }

    /**
     * Get Sesssion
     *
     * @param Request $request Request
     *
     * @return Session
     *
     * @access protected
     */
    protected function getSession(Request $request) : Session
    {
        return $request->getAttribute(ResumeSession::SESSION_ATTRIBUTE);
    }
}
