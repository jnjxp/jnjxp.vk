<?php

namespace Jnjxp\Vk\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Middleware\ResumeAuthMiddlewareFactory;
use Psr\Container\ContainerInterface;
use Jnjxp\Vk\Middleware\ResumeAuthMiddleware;
use Mezzio\Template\TemplateRendererInterface;
use Jnjxp\Vk\UserFactoryInterface;

/**
 * Class ResumeAuthMiddlewareFactoryTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Middleware\ResumeAuthMiddlewareFactory
 */
class ResumeAuthMiddlewareFactoryTest extends TestCase
{
    /**
     * @var ResumeAuthMiddlewareFactory $resumeAuthMiddlewareFactory An instance
     * of "ResumeAuthMiddlewareFactory" to test.
     */
    private $resumeAuthMiddlewareFactory;

    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->resumeAuthMiddlewareFactory = new ResumeAuthMiddlewareFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    protected function containerHas(array $specs)
    {
        $consecutive = [];
        foreach ($specs as $spec) {
            $consecutive[] = [$spec];
        }

        $this->container
          ->expects($this->exactly(count($specs)))
          ->method('get')
          ->withConsecutive(...$consecutive)
          ->will($this->returnCallback([$this, 'containerMock']));
    }

    public function containerMock($spec)
    {
        return $this->createMock($spec);
    }

    /**
     * @covers \Jnjxp\Vk\Handler\LoginHandlerFactory::__invoke
     */
    public function testInvoke(): void
    {
        $this->containerHas(
            [
                TemplateRendererInterface::class,
                UserFactoryInterface::class
            ]
        );

        $handler = ($this->resumeAuthMiddlewareFactory)($this->container);
        $this->assertInstanceOf(ResumeAuthMiddleware::class, $handler);
    }
}
