<?php

namespace Test\Jnjxp\Vk\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Handler\LogoutHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Session\SessionInterface;

/**
 * Class LogoutHandlerTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Handler\LogoutHandler
 */
class LogoutHandlerTest extends TestCase
{
    /**
     * @var LogoutHandler $logoutHandler An instance of "LogoutHandler" to test.
     */
    private $logoutHandler;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->logoutHandler = new LogoutHandler();
    }

    /**
     * @covers \Jnjxp\Vk\Handler\LogoutHandler::handle
     */
    public function testHandle(): void
    {
        $request = new ServerRequest();
        $session = $this->createMock(SessionInterface::class);
        $request = $request->withAttribute('session', $session);

        $session->expects($this->once())
             ->method('clear');

        $session->expects($this->once())
             ->method('regenerate');

        $response = $this->logoutHandler->handle($request);

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertEquals('/', $response->getHeaderLine('Location'));
    }
}
