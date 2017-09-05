<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Logout;

use Jnjxp\Vk\Web\Action\AbstractResponder;

class Responder extends AbstractResponder
{
    protected $redirectTo = '/';

    protected function success()
    {
        $this->messages()
            ->success('You are logged out');

        return $this->redirect($this->redirectTo);
    }
}
