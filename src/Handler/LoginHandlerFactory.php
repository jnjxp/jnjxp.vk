<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Handler;

use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Template\TemplateRendererInterface;

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
