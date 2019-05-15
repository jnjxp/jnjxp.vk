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
 * @category  Trait
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk;

use Aura\Auth\Auth;
use Jnjxp\Vk\Middleware\ResumeAuth;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Trait: AuthAwareTrait
 *
 * @category Trait
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 */
trait AuthAwareTrait
{
    /**
     * GetAuth
     *
     * @param ServerRequestInterface $request Request
     *
     * @return Auth
     *
     * @access protected
     */
    protected function geAuth(ServerRequestInterface $request) : Auth
    {
        return $request->getAttribute(ResumeAuth::AUTH_ATTRIBUTE);
    }

    /**
     * Get auth status from request
     *
     * @param ServerRequestInterface $request PSR7 Request
     *
     * @return string
     *
     * @access protected
     */
    protected function getAuthStatus(ServerRequestInterface $request) : string
    {
        return $this->getAuth($request)->getStatus();
    }

    /**
     * Is auth status valid?
     *
     * @param ServerRequestInterface $request PSR7 Request
     *
     * @return bool
     *
     * @access protected
     */
    protected function isAuthValid(ServerRequestInterface $request) : bool
    {
        return $this->getAuth($request)->isValid();
    }
}
