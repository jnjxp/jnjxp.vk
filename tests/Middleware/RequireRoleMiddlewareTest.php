<?php

namespace Jnjxp\Vk\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Middleware\RequireRoleMiddleware;
use Jnjxp\Vk\AuthHelper;
use Jnjxp\Vk\AuthHelperInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Expressive\Authentication\DefaultUser;
use Zend\Diactoros\ServerRequest;

/**
 * Class RequireRoleMiddlewareTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Middleware\RequireRoleMiddleware
 */
class RequireRoleMiddlewareTest extends TestCase
{
    /**
     * @var RequireRoleMiddleware $requireRoleMiddleware An instance of "RequireRoleMiddleware" to test.
     */
    private $requireRoleMiddleware;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe check arguments of this constructor. */
        $this->requireRoleMiddleware = new RequireRoleMiddleware(["foo", "bar", "baz"]);
    }

    public function testPass()
    {
        $user = new DefaultUser('foo', ['bar']);
        $helper = new AuthHelper($user);
        $request = new ServerRequest();
        $request = $request->withAttribute(AuthHelperInterface::class, $helper);

        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->expects($this->once())
                ->method('handle')
                ->with($request);

        $this->requireRoleMiddleware->process($request, $handler);
    }

    public function testNoAuth()
    {
        $helper  = new AuthHelper();
        $request = new ServerRequest();
        $request = $request->withAttribute(AuthHelperInterface::class, $helper);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $this->requireRoleMiddleware->process($request, $handler);
        $this->assertEquals('302', $response->getStatusCode());
    }

    public function testFails()
    {
        $user = new DefaultUser('foo', ['nope']);
        $helper = new AuthHelper($user);
        $request = new ServerRequest();
        $request = $request->withAttribute(AuthHelperInterface::class, $helper);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $response = $this->requireRoleMiddleware->process($request, $handler);
        $this->assertEquals('302', $response->getStatusCode());
    }
}
