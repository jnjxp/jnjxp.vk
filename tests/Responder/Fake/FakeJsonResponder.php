<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\Fake;

use Jnjxp\Vk\Responder\JsonResponderTrait;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

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

    protected function getRequest()
    {
        return $this->request;
    }

    protected function getResponse()
    {
        return $this->response;
    }

    protected function getPayload()
    {
        return $this->payload;
    }

    protected function setResponse(Response $response)
    {
        $this->response = $response;
        return $this;
    }

}
