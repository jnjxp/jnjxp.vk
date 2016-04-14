<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\Fake;

use Jnjxp\Vk\Responder\HtmlResponderTrait;

class FakeHtmlResponder
{
    use HtmlResponderTrait;

    public $request;

    public $response;

    public $payload;

    public function __call($name, $args)
    {
        return call_user_func_array([$this, $name], $args);
    }
}
