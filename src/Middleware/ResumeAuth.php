<?php

namespace Jnjxp\Vk\Middleware;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService as Resume;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

/**
 * ResumeAuth
 *
 * @see MiddlewareInterface
 */
class ResumeAuth implements MiddlewareInterface
{
    public const AUTH_ATTRIBUTE = Auth::class;

    /**
     * Resume
     *
     * @var Resume
     *
     * @access protected
     */
    protected $resume;

    /**
     * Auth
     *
     * @var Auth
     *
     * @access protected
     */
    protected $auth;

    /**
     * __construct
     *
     * @param Auth   $auth   Aura\Auth current user representation
     * @param Resume $resume Aura\Auth Resume service
     *
     * @access public
     */
    public function __construct(Auth $auth, Resume $resume)
    {
        $this->auth   = $auth;
        $this->resume = $resume;
    }

    /**
     * Resume
     *
     * @return Auth
     *
     * @access protected
     */
    protected function resume() : Auth
    {
        $this->resume->resume($this->auth);
        return $this->auth;
    }

    /**
     * Resumes Authenticated Session
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
            self::AUTH_ATTRIBUTE, $this->resume()
        );
        return $handler->handle($request);
    }
}
