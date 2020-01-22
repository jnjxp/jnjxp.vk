<?php

declare(strict_types=1);

namespace Jnjxp\Vk;

use Mezzio\Application;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Session\PhpSession;

class ConfigProvider
{
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
        ];
    }

    public function getDependencies() : array
    {
        return [
            'aliases' => [
                AuthenticationInterface::class => PhpSession::class,
                UserFactoryInterface::class => UserFactory::class
            ],
            'invokables' => [
                Handler\LogoutHandler::class => Handler\LogoutHandler::class,
                UserFactory::class => UserFactory::class,
            ],
            'factories'  => [
                Middleware\ResumeAuthMiddleware::class => Middleware\ResumeAuthMiddlewareFactory::class,
                Handler\LoginHandler::class => Handler\LoginHandlerFactory::class,
            ],
        ];
    }

    public function getTemplates() : array
    {
        return [
            'paths' => [
                'vk' => [__DIR__ . '/../resources/templates/'],
            ],
        ];
    }

    public function registerRoutes(Application $app) : void
    {
        $app->route('/login', Handler\LoginHandler::class, ['GET', 'POST'], 'login');
        $app->route('/logout', Handler\LogoutHandler::class, ['GET', 'POST'], 'logout');
    }
}
