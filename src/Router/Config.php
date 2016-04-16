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

namespace Jnjxp\Vk\Router;

use Radar\Adr\Adr;
use Aura\Router\Map;

use Jnjxp\Vk\Responder\AuthResponder as Responder;
use Jnjxp\Vk\Login;
use Jnjxp\Vk\Logout;
use Jnjxp\Vk\Input;


/**
 * Config
 *
 * @category Config
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class Config
{
    /**
     * Route prefix
     *
     * @var string
     *
     * @access protected
     */
    protected $prefix = 'Action\\Auth\\';

    /**
     * Add route rule?
     *
     * @var bool
     *
     * @access protected
     */
    protected $addRule = true;

    /**
     * Set route prefix
     *
     * @param string $prefix route name prefix
     *
     * @return $this
     *
     * @access public
     */
    public function setRoutePrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Should we add the route rule?
     *
     * @param bool $bool false to not add rule
     *
     * @return $this
     *
     * @access public
     */
    public function setAddRule($bool)
    {
        $this->addRule = $bool;
        return $this;
    }

    /**
     * Adr
     *
     * @param Adr $adr ADR Container
     *
     * @return mixed
     *
     * @access public
     */
    public function adr(Adr $adr)
    {
        $adr->rules()->append(new AuthRouteRule);
        $adr->attach($this->prefix, '', [$this, 'attach']);
    }

    /**
     * Attach
     *
     * @param Map $map DESCRIPTION
     *
     * @return mixed
     *
     * @access public
     */
    public function attach(Map $map)
    {
        $map->get('Login', '/login')->auth(false);
        $map->get('Logout', '/logout')->auth(true);

        $map->responder(Responder::class);

        $map->post('DoLogin', '/login', Login::class)
            ->input([Input::class, 'login'])
            ->auth(false);

        $map->post('DoLogout', '/logout', Logout::class)
            ->input([Input::class, 'logout'])
            ->auth(true);
    }
}
