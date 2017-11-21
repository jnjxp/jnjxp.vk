<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Authenticate;

use Jnjxp\Vk\Web\Action\AbstractResponder;

class Responder extends AbstractResponder
{
    protected $redirectTo = '/';

    protected function authenticated()
    {
        return $this->redirect($this->redirectTo);
    }

    protected function notValid()
    {
        $this->viewBody(
            'vk/login',
            ['errors' => $this->payload->getMessages()]
        );
    }

    protected function notAuthenticated()
    {
        $this->viewBody(
            'vk/login',
            ['errors' => ['Login Failed']]
        );
    }
}
