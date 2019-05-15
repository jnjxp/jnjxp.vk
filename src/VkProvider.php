<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Auth;
use Aura\Router\RouterContainer;
use Aura\Session\SessionFactory;
use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class VkProvider implements ServiceProviderInterface
{
    public function getFactories() : array
    {
        return [
            AuthHelper::class               => [$this, 'newAuthHelper'],
            Csrf\CsrfGuard::class           => [$this, 'newCsrfGuard'],
            LoginHandler::class             => [$this, 'newLoginHandler'],
            LogoutHandler::class            => [$this, 'newLogoutHandler'],
            Middleware\ResumeAuth::class    => [$this, 'newResumeAuth'],
            Middleware\ResumeSession::class => [$this, 'newResumeSession'],
            ResponderInterface::class       => [$this, 'newResponder'],
            Router\AuthRouteRule::class     => [$this, 'newAuthRouteRule'],
            Router\CsrfRouteRule::class     => [$this, 'newCsrfRouteRule'],
            Router\RouteMapBuilder::class   => [$this, 'newRouteMapBuilder'],

        ];
    }

    public function getExtensions() : array
    {
        return [
            RouterContainer::class => [$this, 'modifyRouter']
        ];
    }

    public function modifyRouter(
        Container $container, RouterContainer $router
    ) {
        $rules = $router->getRuleIterator();
        $rules->append($container->get(Router\AuthRouteRule::class));
        $rules->append($container->get(Router\CsrfRouteRule::class));
    }

    public function newAuthHelper(Container $container) : AuthHelper
    {
        return new AuthHelper($container->get(Auth\Auth::class));
    }

    public function newAuthRouteRule() : Router\AuthRouteRule
    {
        return new Router\AuthRouteRule();
    }

    public function newCsrfRouteRule(Container $container) : Router\CsrfRouteRule
    {
        return new Router\CsrfRouteRule(
            $container->get(Csrf\CsrfGuard::class)
        );
    }

    public function newLoginHandler(Container $container) : LoginHandler
    {
        return new LoginHandler(
            $container->get(Auth\Service\LoginService::class),
            $container->get(ResponderInterface::class),
        );
    }

    public function newLogoutHandler(Container $container) : LogoutHandler
    {
        return new LogoutHandler(
            $container->get(Auth\Service\LogoutService::class),
            $container->get(ResponderInterface::class),
        );
    }

    public function newResumeSession(Container $container) : Middleware\ResumeSession
    {
        return new Middleware\ResumeSession(
            $container->has(SessionFactory::class)
            ? $container->get(SessionFactory::class)
            : null
        );
    }

    public function newResumeAuth(Container $container) : Middleware\ResumeAuth
    {
        return new Middleware\ResumeAuth(
            $container->get(Auth\Auth::class),
            $container->get(Auth\Service\ResumeService::class),
        );
    }

    public function newResponder(Container $container) : ResponderInterface
    {
        return new Responder($container->get(ResponseFactory::class));
    }

    public function newRouteMapBuilder() : Router\RouteMapBuilder
    {
        return new Router\RouteMapBuilder();
    }

    public function newCsrfGuard() : Csrf\CsrfGuard
    {
        return new Csrf\CsrfGuard();
    }
}
