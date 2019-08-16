<?php

namespace Jnjxp\Vk\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Handler\LoginHandler;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Expressive\Authentication\AuthenticationInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use Zend\Expressive\Session\SessionInterface;
use Zend\Expressive\Authentication\UserInterface as User;

/**
 * Class LoginHandlerTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Handler\LoginHandler
 */
class LoginHandlerTest extends TestCase
{
    /**
     * @var LoginHandler $loginHandler An instance of "LoginHandler" to test.
     */
    private $loginHandler;

    private $template;

    private $adapter;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {

        $this->session = $this->createMock(SessionInterface::class);
        $this->template = $this->createMock(TemplateRendererInterface::class);
        $this->adapter = $this->createMock(AuthenticationInterface::class);

        $this->request = new ServerRequest();
        $this->request = $this->request->withAttribute('session', $this->session);


        /** @todo Maybe check arguments of this constructor. */
        $this->loginHandler = new LoginHandler(
            $this->template,
            $this->adapter
        );
    }

    protected function redir()
    {
        $this->session->expects($this->once())
             ->method('set')
             ->with($this->equalTo('authentication:redirect'));
    }

    public function testDisplayForm()
    {
        $this->session->expects($this->once())
             ->method('get')
             ->with($this->equalTo('authentication:redirect'))
             ->will($this->returnValue('/foo'));

        $this->session->expects($this->once())
             ->method('set')
             ->with($this->equalTo('authentication:redirect'), $this->equalTo('/foo'));

        $this->template->expects($this->once())
             ->method('render')
             ->with(
                 $this->equalTo('vk::login'),
                 $this->equalTo([]),
             );

        $response = $this->loginHandler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testGetsRedirect()
    {
        $this->request = $this->request->withHeader('Referer', '/bar');

        $this->session->expects($this->once())
             ->method('get')
             ->with($this->equalTo('authentication:redirect'))
             ->will($this->returnValue(null));

        $this->session->expects($this->once())
             ->method('set')
             ->with($this->equalTo('authentication:redirect'), $this->equalTo('/bar'));

        $this->template->expects($this->once())
             ->method('render')
             ->with(
                 $this->equalTo('vk::login'),
                 $this->equalTo([]),
             );

        $response = $this->loginHandler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testIgnoresLogin()
    {
        $this->request = $this->request->withHeader('Referer', '/login');

        $this->session->expects($this->once())
             ->method('get')
             ->with($this->equalTo('authentication:redirect'))
             ->will($this->returnValue(null));

        $this->session->expects($this->once())
             ->method('set')
             ->with($this->equalTo('authentication:redirect'), $this->equalTo('/'));

        $this->template->expects($this->once())
             ->method('render')
             ->with(
                 $this->equalTo('vk::login'),
                 $this->equalTo([]),
             );

        $response = $this->loginHandler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testFail()
    {
        $this->request = $this->request->withMethod('POST');

        $this->session->expects($this->once())
             ->method('unset')
             ->with($this->equalTo(User::class));

        $this->adapter->expects($this->once())
             ->method('authenticate')
             ->with($this->request)
             ->will($this->returnValue(null));

        $this->template->expects($this->once())
             ->method('render')
             ->with(
                 $this->equalTo('vk::login'),
                 $this->equalTo(['error' => 'Invalid credentials; please try again']),
             );

        $response = $this->loginHandler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testLogin()
    {
        $this->request = $this->request->withMethod('POST');
        $this->request = $this->request->withHeader('Referer', '/bar');

        $this->adapter->expects($this->once())
             ->method('authenticate')
             ->with($this->request)
             ->will($this->returnValue($this->createMock(User::class)));

        $response = $this->loginHandler->handle($this->request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('/bar', $response->getHeaderLine('Location'));
    }
}
