<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Middleware;

use Jnjxp\Vk\Aware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Zend\Diactoros\Response\RedirectResponse;

class RequireRoleMiddleware implements MiddlewareInterface
{
    use Aware\FlashAwareTrait;
    use Aware\AuthAwareTrait;

    protected $roles = [];

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function process(Request $request, Handler $handler) : Response
    {
        $helper = $this->getAuthHelper($request);

        if (! $helper->isAuthenticated()) {
            return $this->fail($request);
        }

        foreach ($this->roles as $role) {
            if ($helper->hasRole($role)) {
                return $handler->handle($request);
            }
        }

        return $this->fail($request);
    }

    protected function fail(Request $request) : Response
    {
        $this->notify($request);
        return new RedirectResponse('/login');
    }

    protected function notify(Request $request) : void
    {
        $this->flashMessage($request, 'danger', 'Operation not authorized!');
    }
}
