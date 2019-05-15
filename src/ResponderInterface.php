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
 * @category  Responder
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Throwable;

/**
 * Interface: ResponderInterface
 */
interface ResponderInterface
{
    /**
     * Successfully authenticated
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @access public
     */
    public function authenticated(Request $request) : Response;

    /**
     * Unknown error
     *
     * @param Request   $request   Request
     * @param Throwable $exception Error
     *
     * @return Response
     *
     * @access public
     */
    public function error(Request $request, Throwable $exception) : Response;

    /**
     * Invalid login attempt
     *
     * @param Request $request Request
     * @param string  $message Message
     *
     * @return Response
     *
     * @access public
     */
    public function invalid(Request $request, string $message) : Response;

    /**
     * Logout successful
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @access public
     */
    public function logout(Request $request) : Response;

    /**
     * Authentication failed
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @access public
     */
    public function notAuthenticated($request) : Response;
}
