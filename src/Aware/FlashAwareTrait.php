<?php

declare(strict_types=1);

namespace Jnjxp\Vk\Aware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Zend\Expressive\Flash;

trait FlashAwareTrait
{
    protected $flashAttribute = Flash\FlashMessageMiddleware::FLASH_ATTRIBUTE;

    public function setFlashAttribute(string $attr)
    {
        $this->flashAttribute = $attr;
    }

    protected function getFlash(Request $request) : ?Flash\FlashMessagesInterface
    {
        $flash = $request->getAttribute($this->flashAttribute, false);

        if (! $flash instanceof Flash\FlashMessagesInterface) {
            return null;
        }

        return $flash;
    }

    protected function flashMessage(Request $request, string $key, string $value)
    {
        $flash = $this->getFlash($request);
        if ($flash) {
            $flash->flash($key, $value);
        }
    }
}
