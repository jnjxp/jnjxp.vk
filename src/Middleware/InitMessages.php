<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class InitMessages implements MiddlewareInterface
{
    public const MESSAGE_ATTRIBUTE = MESSENGER::class;


    public function __construct()
    {
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
            self::MESSENGER_ATTRIBUTE,
            $this->newMessenger($request)
        );
        return $handler->handle($request);
    }
}
