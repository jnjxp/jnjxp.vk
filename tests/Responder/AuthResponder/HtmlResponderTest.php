<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\AuthResponder;

use Vperyod\SessionHandler\SessionTestTrait;

class HtmlResponderTest extends AbstractResponderTest
{
    use SessionTestTrait;

    protected $intent;

    public function setUp()
    {
        parent::setUp();
        $this->responder = new HtmlResponder;
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

    public function authenticatedProvider()
    {
        return [
            [ null, null, '/' ],
            [ 'foo', null, 'foo' ],
            [ null, 'set', 'set'],
            [ null, null, '/', 'succ'],
        ];
    }

    public function expectMessage($msg, $level)
    {
        $this->messages->expects($this->once())
            ->method('setFlash')
            ->with(
                $this->equalTo('messages'),
                $this->equalTo(
                    [
                        [
                            'level' => $level,
                            'message' => $msg
                        ]
                    ]
                )
            );
    }

    /**
     * @dataProvider authenticatedProvider
     */
    public function testAuthenticated($intent, $dash, $expect, $msg = null)
    {
        $this->mockSession();
        $this->mockIntent();
        $this->mockMessageDetatched();

        $map = [
            ['jnjxp/vk:intent', $this->intent],
            ['vperyod/session-handler:messages', $this->messages],
        ];

        $this->session->expects($this->any())
            ->method('getSegment')
            ->will($this->returnValueMap($map));

        if ($intent) {
            $imap = [
                ['url', false, $intent],
                ['time', 0, time() - 2]
            ];
            $this->intent->expects($this->exactly(2))
                ->method('get')
                ->will($this->returnValueMap($imap));
        }

        if ($dash) {
            $this->responder->setDashboard($dash);
        }

        if ($msg) {
            $this->responder->setLoginSuccess($msg);
        }

        $this->expectMessage(
            $msg ?: 'You are logged in',
            'success'
        );

        $this->payload->setStatus('authenticated');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($expect, $response->getHeaderLine('Location'));
    }

    public function successProvider()
    {
        return [
            [null, null],
            ['suc', 'home']
        ];
    }

    /**
     * @dataProvider successProvider
     */
    public function testSuccess($msg = null, $home = null)
    {
        $this->mockSession();
        $this->mockMessages();

        if ($msg) {
            $this->responder->setLogoutSuccess($msg);
        }

        if ($home) {
            $this->responder->setHome($home);
        }

        $this->expectMessage(
            $msg ?: 'You have successfully logged out',
            'success'
        );

        $this->payload->setStatus('success');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($home ?: '/', $response->getHeaderLine('Location'));
    }

    public function failProvider()
    {
        return [
            [null, null],
            ['suc', 'login']
        ];
    }

    /**
     * @dataProvider successProvider
     */
    public function testFail($msg = null, $login = null)
    {
        $this->mockSession();
        $this->mockMessages();

        if ($msg) {
            $this->responder->setLoginFail($msg);
        }

        if ($login) {
            $this->responder->setLogin($login);
        }

        $this->expectMessage(
            $msg ?: 'Authentication failed',
            'warning'
        );

        $this->payload->setStatus('failure');

        $response = $this->responder->__invoke(
            $this->request,
            $this->response,
            $this->payload
        );

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals($login ?: '/login', $response->getHeaderLine('Location'));
    }
}

