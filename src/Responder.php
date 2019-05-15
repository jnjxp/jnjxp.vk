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

use Fig\Http\Message\StatusCodeInterface as Code;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \Throwable;

/**
 * Responder
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
     * @access private
     */
    private $responseFactory;

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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function authenticated(Request $request) : Response
    {
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write('Authenticated');
        return $response;
    }

    /**
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
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function logout(Request $request) : Response
    {
        $response = $this->responseFactory
                         ->createResponse(Code::STATUS_OK);
        return $response;
    }

    public function notAuthenticated($request) :  Response
    {
        return $this->invalid($request, 'Authentication Failed');
    }
}
