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

namespace Jnjxp\Vk\Responder\RuleResponder;

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
     * RequireAuth
     *
     * @return mixed
     *
     * @access protected
     */
    protected function requireAuth()
    {
        $this->messages()->warning($this->requireAuth);
        $this->rememberIntent();
        $this->redirect($this->login);
    }

    /**
     * RequireAnon
     *
     * @return mixed
     *
     * @access protected
     */
    protected function requireAnon()
    {
        $this->messages()->warning($this->requireAnon);
        $this->redirect($this->dashboard);
    }
}
