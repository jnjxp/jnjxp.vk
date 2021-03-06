<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Aware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Jnjxp\Vk\Exception;
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
            throw Exception\MissingAttributeException::missing(
                Session\SessionInterface::class,
                $this->sessionAttribute,
                $this
            );
        }

        return $session;
    }
}
