<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Logout;

use Jnjxp\Vk\Web\Action\AbstractResponder;

class Responder extends AbstractResponder
{
    protected $redirectTo = '/';

    protected function success()
    {
        return $this->redirect($this->redirectTo);
    }
}
