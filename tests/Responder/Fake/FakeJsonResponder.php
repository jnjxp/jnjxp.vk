<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\Fake;

use Jnjxp\Vk\Responder\JsonResponderTrait;

class FakeJsonResponder
{
    use JsonResponderTrait;

    public $request;

    public $response;

    public $payload;

    public function __call($name, $args)
    {
        return call_user_func_array([$this, $name], $args);
    }
}
