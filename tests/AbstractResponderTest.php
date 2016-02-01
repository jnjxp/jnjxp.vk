<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

class AbstractResponderTest extends \PHPUnit_Framework_TestCase
{
    protected $view;

    public function setUp()
    {
        $this->view = $this->getMockBuilder('Aura\View\View')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = ServerRequestFactory::fromGlobals();
        $this->response = new Response();
        $this->payload = new \Aura\Payload\Payload();

        $this->responder = new FakeResponder($this->view);
    }

    protected function respond($payload = true)
    {
        return $this->responder->__invoke(
            $this->request,
            $this->response,
            $payload ?  $this->payload : null
        );
    }

    public function testUnknown()
    {
        $this->payload->setStatus('foo');
        $response = $this->respond();
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testNoContent()
    {
        $this->responder->setViewScript('foo');
        $this->view->expects($this->once())
            ->method('setView')
            ->with('foo');

        $this->view->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue('rendered'));

        $response = $this->respond(false);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('rendered', (string) $response->getBody());
    }

    public function testSuccess()
    {
        $this->responder->setDestination('foo')
            ->setMessengerAttribute('msg')
            ->setSuccessMessage('success');

        $this->payload->setStatus(Status::SUCCESS);

        $messenger = $this->getMock('stdClass', ['success']);

        $messenger->expects($this->once())
            ->method('success')
            ->with('success');

        $this->request = $this->request->withAttribute('msg', $messenger);

        $response = $this->respond();
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(['foo'], $response->getHeader('Location'));
    }

    public function testFailure()
    {
        $this->payload->setStatus(Status::FAILURE)
            ->setInput('input')
            ->setOutput('error');

        $this->responder->setViewScript('view');

        $this->view->expects($this->once())
            ->method('setView')
            ->with('view');

        $this->view->expects($this->once())
            ->method('addData')
            ->with(
                [
                    'failed' => true,
                    'input' => 'input',
                    'error' => 'error'
                ]
            );

        $this->view->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue('rendered'));

        $response = $this->respond();
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('rendered', (string) $response->getBody());
    }

    public function testError()
    {
        $this->payload->setStatus(Status::ERROR)
            ->setOutput('error');

        $this->responder->setErrorViewScript('eview');

        $this->view->expects($this->once())
            ->method('setView')
            ->with('eview');

        $this->view->expects($this->once())
            ->method('addData')
            ->with(['error' => 'error']);

        $this->view->expects($this->once())
            ->method('__invoke')
            ->will($this->returnValue('rendered'));

        $response = $this->respond();
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals('rendered', (string) $response->getBody());
    }

}
