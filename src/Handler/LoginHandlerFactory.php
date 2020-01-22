<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Handler;

use Psr\Container\ContainerInterface;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Template\TemplateRendererInterface;

class LoginHandlerFactory
{
    public function __invoke(ContainerInterface $container) : LoginHandler
    {
        return new LoginHandler(
            $container->get(TemplateRendererInterface::class),
            $container->get(PhpSession::class)
        );
    }
}
