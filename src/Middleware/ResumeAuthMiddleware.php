<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Middleware;

use Jnjxp\Vk\AuthHelper;
use Jnjxp\Vk\AuthHelperInterface;
use Jnjxp\Vk\Aware;
use Jnjxp\Vk\UserFactoryInterface as UserFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Zend\Expressive\Authentication\UserInterface as User;
use Zend\Expressive\Template\TemplateRendererInterface as Template;

class ResumeAuthMiddleware implements MiddlewareInterface
{
    use Aware\SessionAwareTrait;

    public const VIEW_KEY = 'auth';

    protected $template;

    protected $userFactory;

    protected $viewKey;

    public function __construct(
        Template $template,
        UserFactory $userFactory,
        string $viewKey = self::VIEW_KEY
    ) {
        $this->template    = $template;
        $this->userFactory = $userFactory;
        $this->viewKey     = $viewKey;
    }

    public function process(Request $request, Handler $handler) : Response
    {
        $user   = $this->getUser($request);
        $helper = $this->newHelper($user);
        $this->addToView($helper);

        $request = $request
            ->withAttribute(User::class, $user)
            ->withAttribute(AuthHelperInterface::class, $helper);

        return $handler->handle($request);
    }

    protected function getUser(Request $request) : ?User
    {
        $session = $this->getSession($request);
        return $this->userFactory->fromSession($session);
    }

    protected function newHelper(User $user = null) : AuthHelperInterface
    {
        return new AuthHelper($user);
    }

    protected function addToView(AuthHelperInterface $helper) : void
    {
        $this->template->addDefaultParam(
            Template::TEMPLATE_ALL,
            $this->viewKey,
            $helper
        );
    }
}
