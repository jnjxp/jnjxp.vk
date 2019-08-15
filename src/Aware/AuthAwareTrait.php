<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Aware;

use Jnjxp\Vk\AuthHelperInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

trait AuthAwareTrait
{
    protected $authAttribute = AuthHelperInterface::class;

    public function setAuthAttribute(string $attr) : void
    {
        $this->authAttribute = $attr;
    }

    protected function getAuthHelper(Request $request) : AuthHelperInterface
    {
        $auth = $request->getAttribute($this->authAttribute, false);

        if (! $auth instanceof AuthHelperInterface) {
            throw new \Exception("AuthHelper not available");
        }

        return $auth;
    }
}
