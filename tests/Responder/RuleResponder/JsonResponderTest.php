<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\RuleResponder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;

class JsonResponderTest extends AbstractResponderTest
{

    public function setUp()
    {
        parent::setUp();
        $this->responder = new JsonResponder();
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
    public function testAuth($msg = null)
    {
        if ($msg) {
            $this->responder->setRequireAuth($msg);
        }

        $msg = $msg ?: 'Authentication required';

        $this->routeAuth(true);

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->route
        );

        $body = json_encode(['error' => $msg]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }


    /**
     * @dataProvider msgProvider
     */
    public function testAnon($msg = null)
    {
        if ($msg) {
            $this->responder->setRequireAnon($msg);
        }

        $msg = $msg ?: 'You are already authenticated';

        $this->routeAuth(false);

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->route
        );

        $body = json_encode(['error' => $msg]);
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals($body, (string) $response->getBody());
    }
}
