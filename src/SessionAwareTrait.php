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

namespace Jnjxp\Vk;

use Aura\Session\Session;
use Jnjxp\Vk\Middleware\ResumeSession;
use Psr\Http\Message\ServerRequestInterface as Request;

trait SessionAwareTrait
{
    /**
     * GetSession
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
