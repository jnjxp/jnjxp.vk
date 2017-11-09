<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

use Aura\View\View as AuraView;
use Vperyod\AuthHandler\AuthHandler;
use Aura\Auth;

class Config extends ContainerConfig
{

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {

        $di->params[AuthHandler::class] = [
            'resume' => $di->lazyGet(Auth\Service\ResumeService::class),
            'auth'   => $di->lazyGet(Auth\Auth::class)
        ];

        $di->params[Action\AbstractResponder::class] = [
            'view'     => $di->lazyGet(AuraView::class),
            'url'      => $di->lazyGetCall(
                'radar/adr:router',
                'newRouteHelper'
            ),
        ];

        $di->params[View\AuthHelper::class] = [
            'auth' => $di->lazyGet(Auth\Auth::class)
        ];

    }

    /**
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        if ($di->has('radar/adr:router')) {
            $router = $di->get('radar/adr:router');
            $map    = $router->getMap();
            $rules  = $router->getRuleIterator();

            $rule   = $di->newInstance(Router\AuthRouteRule::class);
            $routes = $di->newInstance(Action::class);

            $rules->append($rule);
            $map->attach('', '', $routes);
        }

        if ($di->has(AuraView::class)) {
            $view = $di->get(AuraView::class);
            $helpers = $view->getHelpers();
            $helpers->set('auth', $di->lazyNew(View\AuthHelper::class));

            $path = dirname(__DIR__) . '/../resources/views/';
            $templates = $view->getViewRegistry();
            $templates->appendPath($path);
        }
    }

}
