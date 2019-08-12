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
 * @category  Handler
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk\Action;

use Aura\Auth;
use Jnjxp\Vk\AuthAwareTrait;
use Jnjxp\Vk\SessionAwareTrait;
use Jnjxp\Vk\Responder\ResponderInterface as Responder;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * AbstractHandler
 *
 * @category Handler
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 *
 * @see      Handler
 * @abstract
 */
abstract class AbstractHandler implements Handler
{
    use AuthAwareTrait;
    use SessionAwareTrait;

    /**
     * Aura Auth service
     *
     * @var mixed
     *
     * @access protected
     */
    protected $service;

    /**
     * Responder
     *
     * @var ResponderInterface
     *
     * @access protected
     */
    protected $responder;

    /**
     * __construct
     *
     * @param object    $service   Service
     * @param Responder $responder Responder
     *
     * @access public
     */
    public function __construct(object $service, Responder $responder)
    {
        $this->service = $service;
        $this->responder = $responder;
    }
}