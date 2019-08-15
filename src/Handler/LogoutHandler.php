<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Handler;

use Jnjxp\Vk\Aware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class LogoutHandler implements RequestHandlerInterface
{
    use Aware\SessionAwareTrait;
    use Aware\FlashAwareTrait;

    public function handle(Request $request) : Response
    {
        $session  = $this->getSession($request);
        $session->clear();
        $session->regenerate();
        $this->goodbye($request);

        return new RedirectResponse('/');
    }

    protected function goodbye(Request $request) : void
    {
        $this->flashMessage($request, 'success', 'You are logged out');
    }
}
