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

use Aura\Auth\Service\LogoutService;
use Jnjxp\Vk\Responder\ResponderInterface as Responder;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * LogoutHandler
 *
 * @category Action
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 *
 * @see AbstractHandler
 */
class LogoutHandler extends AbstractHandler
{

    /**
     * __construct
     *
     * @param LogoutService $service   Logout service
     * @param Responder     $responder responder
     *
     * @access public
     */
    public function __construct(LogoutService $service, Responder $responder)
    {
        parent::__construct($service, $responder);
    }

    /**
     * Handle logout request
     *
     * @param Request $request request
     *
     * @return Response
     *
     * @access public
     */
    public function handle(Request $request) : Response
    {
        try {
            $auth  = $this->getAuth($request);
            $this->service->logout($auth);
            $this->getSession($request)->regenerateId();
            $response = $this->responder->logout($request);
        } catch (\Exception $exception) {
            $response = $this->responder->error($request, $exception);
        }

        return $response;
    }
}
