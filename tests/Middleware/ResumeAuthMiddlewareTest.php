<?php

namespace Jnjxp\Vk\Middleware;

use Jnjxp\Vk\AuthHelperInterface;
use Jnjxp\Vk\Middleware\ResumeAuthMiddleware;
use Jnjxp\Vk\UserFactoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Response;
use Mezzio\Authentication\UserInterface as User;
use Mezzio\Session\SessionInterface;
use Mezzio\Template\TemplateRendererInterface;

/**
 * Class ResumeAuthMiddlewareTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Middleware\ResumeAuthMiddleware
 */
class ResumeAuthMiddlewareTest extends TestCase
{
    /**
     * @var ResumeAuthMiddleware $resumeAuthMiddleware An instance of
     * "ResumeAuthMiddleware" to test.
     */
    private $resumeAuthMiddleware;

    private $template;

    private $userFactory;

    private $session;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->session = $this->createMock(SessionInterface::class);
        $this->template = $this->createMock(TemplateRendererInterface::class);
        $this->userFactory = $this->createMock(UserFactoryInterface::class);

        $this->resumeAuthMiddleware = new ResumeAuthMiddleware(
            $this->template,
            $this->userFactory
        );

        $this->request = new ServerRequest();
        $this->request = $this->request->withAttribute('session', $this->session);
    }

    public function testAuthed()
    {
        $user = $this->createMock(User::class);

        $this->userFactory->expects($this->once())
             ->method('fromSession')
             ->with($this->session)
             ->will($this->returnValue($user));

        $this->template->expects($this->once())
             ->method('addDefaultParam');

        $test = new class($user, $this) implements RequestHandlerInterface {
            protected $user;
            protected $test;
            public function __construct($user, $test)
            {
                $this->user = $user;
                $this->test = $test;
            }

            public function handle(ServerRequestInterface $request) : ResponseInterface
            {
                $this->test->assertSame(
                    $request->getAttribute(User::class),
                    $this->user
                );

                $this->test->assertSame(
                    $request->getAttribute(AuthHelperInterface::class)->getUser(),
                    $this->user
                );
                return new Response();
            }
        };

        $this->resumeAuthMiddleware->process(
            $this->request,
            $test
        );
    }
}
