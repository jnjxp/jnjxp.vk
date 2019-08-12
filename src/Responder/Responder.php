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

namespace Jnjxp\Vk\Responder;

use Fig\Http\Message\StatusCodeInterface as Code;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Throwable;

/**
 * Responder
 *
 * @category Responder
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  https://jnj.mit-license.org/ MIT License
 * @link     https://jakejohns.net
 *
 * @see ResponderInterface
 */
class Responder implements ResponderInterface
{
    /**
     * Response factory
     *
     * @var ResponseFactory
     *
     * @access protected
     */
    protected $responseFactory;

    /**
     * __construct
     *
     * @param ResponseFactory $responseFactory PSR-17 Response Factory
     *
     * @access public
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Authenticated
     *
     * @param Request $request request
     *
     * @return Response
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticated(Request $request) : Response
    {
        $this->welcomeMessage($request);
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write('Authenticated');
        return $response;
    }

    /**
     * Set welcome flash message
     *
     * @param Request $request Request
     *
     * @return void
     *
     * @access protected
     */
    protected function welcomeMessage(Request $request)
    {
        $this->getSession($request)
            ->getSegment('messages')
            ->setFlash('messages', ['Authenticated', 'success']);
    }

    /**
     * Error
     *
     * @param Request   $request   Request
     * @param Throwable $exception Error
     *
     * @return Response
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function error(Request $request, Throwable $exception) : Response
    {
        $response = $this->responseFactory
            ->createResponse(Code::STATUS_INTERNAL_SERVER_ERROR);
        $response->getBody()->write('An error occured');
        return $response;
    }

    /**
     * Invalid
     *
     * @param Request $request request
     * @param string  $message message
     *
     * @return Response
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function invalid(Request $request, string $message) : Response
    {
        $response = $this->responseFactory
            ->createResponse(Code::STATUS_UNPROCESSABLE_ENTITY);
        $response->getBody()->write($message);
        return $response;
    }

    /**
     * Logout
     *
     * @param Request $request request
     *
     * @return Response
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function logout(Request $request) : Response
    {
        $response = $this->responseFactory->createResponse(Code::STATUS_OK);
        return $response;
    }

    /**
     * NotAuthenticated
     *
     * @param Request $request Request
     *
     * @return Response
     *
     * @access public
     */
    public function notAuthenticated(Request $request) :  Response
    {
        return $this->invalid($request, 'Authentication Failed');
    }
}
