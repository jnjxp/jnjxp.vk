<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;
use Vperyod\SessionHandler\SessionTestTrait;

class JsonResponderTest extends AbstractResponderTest
{

    public function testError()
    {
        $this->responder = new Fake\FakeJsonResponder;
        $exception = new \Exception('Foo');
        $this->payload->setOutput($exception);
        $this->responder->payload = $this->payload;
        $this->responder->response = $this->response;

        $this->responder->error();

        $result = $this->responder->response;

        $this->assertEquals(500, $result->getStatusCode());
        $this->assertEquals('application/json', $result->getHeaderLine('Content-Type'));

        $expect = json_encode(['error' => 'Exception: Foo']);

        $this->assertEquals(
            $expect,
            (string) $result->getBody()
        );
    }
}

