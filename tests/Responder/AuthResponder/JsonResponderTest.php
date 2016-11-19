<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\AuthResponder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;

class JsonResponderTest extends AbstractResponderTest
{
    public function setUp()
    {
        parent::setUp();
        $this->responder = new JsonResponder;
    }

    public function msgProvider()
    {
        return [
            [null],
            ['foo']
        ];
    }

    /**
     * @dataProvider msgProvider
     */
    public function testAuthenticated($msg = null)
    {
        if ($msg) {
            $this->responder->setLoginSuccess($msg);
        }

        $msg = $msg ?: 'You are logged in';

        $this->payload->setStatus('authenticated');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $body = json_encode(['message' => $msg]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }


    /**
     * @dataProvider msgProvider
     */
    public function testSuccess($msg = null)
    {
        if ($msg) {
            $this->responder->setLogoutSuccess($msg);
        }

        $msg = $msg ?: 'You have successfully logged out';

        $this->payload->setStatus('success');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $body = json_encode(['message' => $msg]);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }


    /**
     * @dataProvider msgProvider
     */
    public function testFail($msg = null)
    {
        if ($msg) {
            $this->responder->setLoginFail($msg);
        }

        $msg = $msg ?: 'Authentication failed';

        $this->payload->setStatus('failure');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $body = json_encode(['message' => $msg]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }

    public function testError()
    {
        $exception = new \Exception('Foo');
        $this->payload->setOutput($exception)
            ->setStatus('error');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $msg = 'Exception: Foo';
        $body = json_encode(['message' => $msg]);
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(
            $body,
            (string) $response->getBody()
        );
    }
}
