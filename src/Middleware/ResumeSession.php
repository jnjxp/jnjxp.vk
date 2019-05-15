<?php
/**
 * VK
 *
 * PHP version 7
 *
 * Copyright (C) 2019 Jake Johns
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 *
 * @category  Middleware
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2019 Jake Johns
 * @license   http://jnj.mit-license.org/2019 MIT License
 * @link      http://jakejohns.net
 */

declare(strict_types = 1);

namespace Jnjxp\Vk\Middleware;

use Aura\Session\SessionFactory;
use Aura\Session\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * ResumeSession
 *
 * @see MiddlewareInterface
 */
class ResumeSession implements MiddlewareInterface
{
    public const SESSION_ATTRIBUTE = Session::class;

    /**
     * Factory
     *
     * @var SessionFactory
     *
     * @access protected
     */
    protected $factory;

    /**
     * __construct
     *
     * @param SessionFactory $factory
     *
     * @access public
     */
    public function __construct(SessionFactory $factory = null)
    {
        $this->factory = $factory ?: new SessionFactory();
    }

    /**
     * Create new session
     *
     * @param Request $request
     *
     * @return Session
     *
     * @access protected
     */
    protected function newSession(Request $request) : Session
    {
        return $this->factory->newInstance(
            $request->getCookieParams()
        );
    }

    /**
     * Resumes Session
     *
     * @param Request  $request  PSR7 HTTP Request
     * @param Handler  $handler  Next handler
     *
     * @return Response
     *
     * @access public
     */
    public function process(Request $request, Handler $handler): Response
    {
        $request = $request->withAttribute(
            self::SESSION_ATTRIBUTE,
            $this->newSession($request)
        );
        return $handler->handle($request);
    }
}
