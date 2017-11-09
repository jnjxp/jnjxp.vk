<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Web\Action\Login;

use Jnjxp\Vk\Web\Action\AbstractResponder;

class Responder extends AbstractResponder
{
    protected function noContent()
    {
        $this->viewBody('vk/login');
    }
}
