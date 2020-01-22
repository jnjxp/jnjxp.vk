<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Middleware;

use Jnjxp\Vk\UserFactoryInterface;
use Psr\Container\ContainerInterface;
use Mezzio\Template\TemplateRendererInterface;

class ResumeAuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : ResumeAuthMiddleware
    {
        return new ResumeAuthMiddleware(
            $container->get(TemplateRendererInterface::class),
            $container->get(UserFactoryInterface::class)
        );
    }
}
