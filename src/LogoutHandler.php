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

namespace Jnjxp\Vk;

use Aura\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * LogoutHandler
 *
 * @see AbstractHandler
 */
class LogoutHandler extends AbstractHandler
{

    /**
     * __construct
     *
     * @param Auth\Service\LogoutService $service
     * @param ResponderInterface $responder
     *
     * @access public
     */
    public function __construct(
        Auth\Service\LogoutService $service,
        ResponderInterface $responder
    ) {
        parent::__construct($service, $responder);
    }

    /**
     * Handle logout request
     *
     * @param Request $request
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
            $response = $this->responder->logout($request);
        } catch (\Exception $exception) {
            $response = $this->responder->error($request, $exception);
        }

        return $response;
    }
}
