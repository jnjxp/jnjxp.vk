<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Handler;

use Fig\Http\Message\RequestMethodInterface;
use Jnjxp\Vk\Aware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Authentication\AuthenticationInterface as Adapter;
use Zend\Expressive\Authentication\UserInterface as User;
use Zend\Expressive\Session\SessionInterface as Session;
use Zend\Expressive\Template\TemplateRendererInterface as Template;

class LoginHandler implements RequestHandler, RequestMethodInterface
{
    use Aware\SessionAwareTrait;
    use Aware\FlashAwareTrait;

    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    /** @var Adapter */
    private $adapter;

    /**
     * @var Template
     */
    private $renderer;

    public function __construct(Template $renderer, Adapter $adapter)
    {
        $this->renderer = $renderer;
        $this->adapter = $adapter;
    }

    public function handle(Request $request) : Response
    {
        $session  = $this->getSession($request);
        $redirect = $this->getRedirect($request, $session);

        // Handle submitted credentials
        if (self::METHOD_POST === $request->getMethod()) {
            return $this->handleLoginAttempt($request, $session, $redirect);
        }

        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);
        return $this->displayForm();
    }

    private function getRedirect(Request $request, Session $session) : string
    {
        $redirect = $session->get(self::REDIRECT_ATTRIBUTE);

        if (! $redirect) {
            $redirect = $request->getHeaderLine('Referer');
            if (in_array($redirect, ['', '/login'], true)) {
                $redirect = '/';
            }
        }

        return $redirect;
    }

    private function handleLoginAttempt(
        Request $request,
        Session $session,
        string $redirect
    ) : Response {

        // User session takes precedence over user/pass POST in
        // the auth adapter so we remove the session prior
        // to auth attempt
        $session->unset(User::class);

        // Login was successful
        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);
            $this->welcome($request);
            return new RedirectResponse($redirect);
        }

        // Login failed
        return $this->displayForm(
            ['error' => 'Invalid credentials; please try again']
        );
    }

    protected function welcome(Request $request) : void
    {
        $this->flashMessage($request, 'success', 'Welcome!');
    }

    protected function displayForm(array $messages = []) : Response
    {
        $html = $this->renderer->render('vk::login', $messages);
        return new HtmlResponse($html);
    }
}
