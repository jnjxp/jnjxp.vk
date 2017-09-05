<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Authenticate;

use Jnjxp\Vk\Web\Action\AbstractResponder;

class Responder extends AbstractResponder
{
    protected $redirectTo = '/';

    protected function authenticated()
    {
        $this->messages()
            ->success('You are logged in');

        return $this->redirect($this->redirectTo);
    }

    protected function notValid()
    {
        return $this->viewBody(
            'vk/login',
            ['errors' => $this->payload->getMessages()]
        );
    }

    protected function notAuthenticated()
    {
        return $this->viewBody(
            'vk/login',
            ['errors' => ['Login Failed']]
        );
    }
}
