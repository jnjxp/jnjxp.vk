<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Aware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Session;

trait SessionAwareTrait
{
    protected $sessionAttribute = Session\SessionMiddleware::SESSION_ATTRIBUTE;

    public function setSessionAttribute(string $attr) : void
    {
        $this->sessionAttribute = $attr;
    }

    protected function getSession(Request $request) : Session\SessionInterface
    {
        $session = $request->getAttribute($this->sessionAttribute, false);

        if (! $session instanceof Session\SessionInterface) {
            throw new \Exception("Session not available");
        }

        return $session;
    }
}
