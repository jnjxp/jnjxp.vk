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

namespace Jnjxp\Vk;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Aura\Payload\Payload;

use Jnjxp\Vk\Router\Config as RouteConfig;
use Jnjxp\Vk\Responder\Config as ResponderConfig;

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
     * Should we configure routes?
     *
     * @var bool
     *
     * @access protected
     */
    protected $route = true;

    /**
     * Should we configure responders?
     *
     * @var mixed
     *
     * @access protected
     */
    protected $responders = true;

    /**
     * Should we configure default routes?
     *
     * @param bool $bool false to stop
     *
     * @return mixed
     *
     * @access public
     */
    public function setRoute($bool)
    {
        $this->route = (bool) $bool;
        return $this;
    }

    /**
     * Should we configure responders?
     *
     * @param bool $bool false to stop
     *
     * @return $this
     *
     * @access public
     */
    public function setResponders($bool)
    {
        $this->responders = (bool) $bool;
        return $this;
    }

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
        $this->defineService($di);

        if ($this->responders) {
            $config = new ResponderConfig;
            $config->define($di);
        }
    }

    /**
     * Define Service
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineService(Container $di)
    {
        $di->params[AbstractService::class] = [
            'payload' => $di->lazyNew(Payload::class)
        ];

        $di->params[Login::class] = [
            'auraLogin' => $di->lazyGet('aura/auth:login')
        ];

        $di->params[Logout::class] = [
            'auraLogout' => $di->lazyGet('aura/auth:logout')
        ];
    }

    /**
     * Modify container
     *
     * @param Container $di DESCRIPTION
     *
     * @return mixed
     * @throws exceptionclass [description]
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        if ($this->route && $di->has('radar/adr:adr')) {
            $config = $di->newInstance(RouteConfig::class);
            $config->adr($di->get('radar/adr:adr'));
        }
    }
}
