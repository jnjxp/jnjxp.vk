<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;
use Vperyod\SessionHandler\SessionTestTrait;

class HtmlResponderTest extends AbstractResponderTest
{
    use SessionTestTrait;

    protected $intent;

    public function setUp()
    {
        parent::setUp();
        $this->responder = new Fake\FakeHtmlResponder;
    }

    public function mockIntent()
    {
        $this->intent = $this->getMockBuilder('Aura\Session\Segment')
            ->disableOriginalConstructor()
            ->getMock();

        $this->session->expects($this->once())
            ->method('getSegment')
            ->with($this->equalTo('jnjxp/vk:intent'))
            ->will($this->returnValue($this->intent));
    }

    public function testMessages()
    {
        $this->mockSession();
        $this->mockMessages();
        $this->responder->request = $this->request;
        $this->assertInstanceOf(
            'Vperyod\SessionHandler\Messenger',
            $this->responder->messages()
        );
    }

    public function testIntent()
    {
        $this->mockSession();
        $this->mockIntent();
        $this->responder->request = $this->request;
        $this->assertSame($this->intent, $this->responder->intent());
    }

    public function testRedirect()
    {
        $this->responder->response = $this->response;
        $this->responder->redirect('foo');
        $result = $this->responder->response;
        $this->assertEquals(302, $result->getStatusCode());
        $this->assertEquals('foo', $result->getHeaderLine('Location'));
    }

    public function rememberProvider()
    {
        return [
            ['GET', 'foo'],
            ['POST'],
            ['PUT'],
            ['DELETE'],
            ['PATCH']
        ];
    }

    /**
     * @dataProvider rememberProvider
     */
    public function testRemember($method, $url = false)
    {
        $request = $this->getMockBuilder('Psr\Http\Message\ServerRequestInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('getMethod')
            ->will($this->returnValue($method));

        if ($url) {
            $this->mockSession();
            $this->mockIntent();
            $request->expects($this->once())
                ->method('getAttribute')
                ->with($this->equalTo('aura/session:session'))
                ->will($this->returnValue($this->session));
            $request->expects($this->once())
                ->method('getUri')
                ->will($this->returnValue($url));
            $this->intent->expects($this->exactly(2))
                ->method('set')
                ->withConsecutive(
                    [$this->equalTo('url'), $this->equalTo($url)],
                    ['time', $this->greaterThan(time() - 2)]
                );
        }

        $this->responder->request = $request;

        $this->assertSame(
            $this->responder,
            $this->responder->rememberIntent()
        );
    }


    public function getIntentProvider()
    {
        return [
            [false, 0, false],
            ['foo', time(), 'foo'],
            ['foo', time() - 1000, false]
        ];
    }

    /**
     * @dataProvider getIntentProvider
     */
    public function testGetIntent($url, $then, $expect)
    {
        $this->mockSession();
        $this->mockIntent();
        $this->responder->request = $this->request;

        $map = [
            [ 'url', false, $url ],
            [ 'time', 0, $then ]
        ];

        $this->intent->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap($map));

        $this->intent->expects($this->once())
            ->method('clear');

        $this->assertEquals(
            $expect,
            $this->responder->getIntent()
        );
    }
}
