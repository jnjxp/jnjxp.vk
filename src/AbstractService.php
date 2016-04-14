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
 * @category  Service
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://jnj.mit-license.org/2016 MIT License
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Aura\Auth\Auth;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Exception;

/**
 * Abstract base service for login and logout
 *
 * Initialized payload property and callable event notifier
 *
 * @category Service
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/ MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
abstract class AbstractService
{

    /**
     * Domain Payload Prototype
     *
     * @var Payload
     *
     * @access protected
     */
    protected $protoPayload;

    /**
     * Domain Payload Object
     *
     * @var Payload
     *
     * @access protected
     */
    protected $payload;

    /**
     * Event Notifier
     *
     * @var callable
     *
     * @access protected
     */
    protected $event;

    /**
     * Auth object
     *
     * @var Auth
     *
     * @access protected
     */
    protected $auth;

    /**
     * Create authentication service
     *
     * @param Payload  $payload Domain payload prototype
     * @param callable $event   Event notifier
     *
     * @access public
     */
    public function __construct(Payload $payload, callable $event = null)
    {
        $this->protoPayload = $payload;
        $this->event   = $event;
    }

    /**
     * Initialize new payload and notify system of processing
     *
     * @param Auth  $auth  Auth Object
     * @param array $input Input parameters
     *
     * @return void
     *
     * @access protected
     */
    protected function init(Auth $auth, array $input = null)
    {
        $this->payload = clone $this->protoPayload;
        $this->payload
            ->setStatus(Status::PROCESSING)
            ->setInput($input)
            ->setExtras(['auth' => $auth]);
        $this->auth = $auth;
        $this->notify();
    }

    /**
     * Notify system of authentication event
     *
     * @return void
     *
     * @access protected
     */
    protected function notify()
    {
        $event = $this->event;
        if ($event) {
            $event($this, $this->payload);
        }
    }

    /**
     * Error
     *
     * @param Exception $exception Error exception
     *
     * @return void
     *
     * @access protected
     */
    protected function error(Exception $exception)
    {
        $this->payload
            ->setStatus(Status::ERROR)
            ->setOutput($exception);
    }
}
