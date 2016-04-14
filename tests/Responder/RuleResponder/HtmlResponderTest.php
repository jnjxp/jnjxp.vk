<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\RuleResponder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;

use Vperyod\SessionHandler\SessionTestTrait;

class HtmlResponderTest extends AbstractResponderTest
{
    use SessionTestTrait;

    public function setUp()
    {
        parent::setUp();
        $this->responder = new HtmlResponder();
    }

    public function mockIntent()
    {
        $this->intent = $this->getMockBuilder('Aura\Session\Segment')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function mockMessageDetatched()
    {
        $this->messages = $this->getMockBuilder('Aura\Session\Segment')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function dataProvider()
    {
        return [
            [null, null],
            ['msg', 'url']
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testAuth($msg = null, $url = null)
    {
        $this->mockSession();
        $this->mockIntent();
        $this->mockMessageDetatched();

        if ($msg) {
            $this->responder->setRequireAuth($msg);
        }

        if ($url) {
            $this->responder->setLogin($url);
        }

        $map = [
            ['jnjxp/vk:intent', $this->intent],
            ['vperyod/session-handler:messages', $this->messages],
        ];

        $this->session->expects($this->any())
            ->method('getSegment')
            ->will($this->returnValueMap($map));

        $this->intent->expects($this->exactly(2))
            ->method('set')
            ->withConsecutive(
                [$this->equalTo('url'), $this->equalTo('http:///')],
                ['time', $this->greaterThan(time() - 2)]
            );

        $msg = $msg ?: 'Authentication required';

        $this->routeAuth(true);

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->route
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($url ?: '/login', $response->getHeaderLine('Location'));
    }


    /**
     * @dataProvider dataProvider
     */
    public function testAnon($msg = null, $url = null)
    {
        $this->mockSession();
        $this->mockMessages();

        if ($msg) {
            $this->responder->setRequireAnon($msg);
        }

        if ($url) {
            $this->responder->setDashboard($url);
        }

        $msg = $msg ?: 'You are already authenticated';

        $this->routeAuth(false);

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->route
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($url ?: '/', $response->getHeaderLine('Location'));
    }
}

