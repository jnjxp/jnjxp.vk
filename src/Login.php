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
use Aura\Auth\Service\LoginService as AuraLogin;
use Aura\Auth\Exception as AuraException;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Exception;

/**
 * Login Service
 *
 * @category Service
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://jnj.mit-license.org/2016 MIT License
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class Login extends AbstractService
{
    /**
     * Aura\Auth Login Service
     *
     * @var AuraLogin
     *
     * @access protected
     */
    protected $auraLogin;

    /**
     * Create Login service
     *
     * @param Payload   $payload   Domain payload prototype
     * @param AuraLogin $auraLogin Aura\Auth login service
     * @param callable  $event     Event notifier
     *
     * @access public
     */
    public function __construct(
        Payload $payload,
        AuraLogin $auraLogin,
        callable $event = null
    ) {
        parent::__construct($payload, $event);
        $this->auraLogin = $auraLogin;
    }

    /**
     * Attempt authentication with underlying Aura\Auth
     *
     * @param Auth   $auth     Aura\Auth current user representation
     * @param string $username Username string
     * @param string $password Password string
     *
     * @return void
     *
     * @throws AuraException see Aura\Auth exceptions on Failure
     * @throws Exception on unspecificed error
     *
     * @access protected
     *
     * @see https://github.com/auraphp/Aura.Auth#logging-in
     */
    protected function doLogin(Auth $auth, $username, $password)
    {
        $input = ['username' => $username, 'password' => $password];
        $this->auraLogin->login($auth, $input);

        if (! $auth->isValid()) {
            throw new Exception('Unknown Authentication Error');
        }
    }

    /**
     * Attempt login
     *
     * @param Auth   $auth     Aura\Auth current user representation
     * @param string $username Username string
     * @param string $password Password string
     *
     * @return Payload
     *
     * @access public
     */
    public function __invoke(Auth $auth, $username, $password)
    {
        $input = ['username' => $username, 'password' => (bool) $password];
        $this->init($auth, $input);

        try {
            $this->doLogin($auth, $username, $password);
            $this->success();
        } catch (Exception $e) {
            $this->error($e);
        }

        $this->notify();
        return $this->payload;
    }

    /**
     * Success
     *
     * @return void
     *
     * @access protected
     */
    protected function success()
    {
        $auth = $this->auth;
        $out  = ['username' => $auth->getUserName(), 'data' => $auth->getUserData()];
        $this->payload->setStatus(Status::AUTHENTICATED)
            ->setOutput($out);
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
        if ($exception instanceof AuraException) {
            return $this->failure($exception);
        }

        parent::error($exception);
    }

    /**
     * Failure
     *
     * Aura\Auth throws various exceptions on failure
     *
     * @param AuraException $exception Failure exception
     *
     * @return void
     *
     * @access protected
     *
     * @see https://github.com/auraphp/Aura.Auth#logging-in
     */
    protected function failure(AuraException $exception)
    {
        $this->payload->setStatus(Status::FAILURE)
            ->setOutput($exception);
    }
}
