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

namespace Jnjxp\Vk\Responder;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Jnjxp\Vk\Responder\AuthResponder;
use Jnjxp\Vk\Responder\RuleResponder;

use Jnjxp\Vk\Responder\AuthResponder\HtmlResponder as AuthHtml;
use Jnjxp\Vk\Responder\AuthResponder\JsonResponder as AuthJson;

use Jnjxp\Vk\Responder\RuleResponder\HtmlResponder as RuleHtml;
use Jnjxp\Vk\Responder\RuleResponder\JsonResponder as RuleJson;

/**
 * Config
 *
 * @category Config
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see ContainerConfig
 */
class Config extends ContainerConfig
{
    /**
     * Define
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {
        $json = 'application/json';
        $html = 'text/html';

        $di->params[AuthResponder::class]['factories'] = [
            $html => $di->lazyNew(AuthHtml::class),
            $json => $di->lazyNew(AuthJson::class)
        ];

        $di->params[RuleResponder::class]['factories'] = [
            $html => $di->lazyNew(RuleHtml::class),
            $json => $di->lazyNew(RuleJson::class)
        ];
    }
}
