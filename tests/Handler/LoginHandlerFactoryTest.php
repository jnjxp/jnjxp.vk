<?php

namespace Test\Jnjxp\Vk\Handler;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\Handler\LoginHandlerFactory;
use Jnjxp\Vk\Handler\LoginHandler;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Authentication\Session\PhpSession;
use Zend\Expressive\Template\TemplateRendererInterface;

/**
 * Class LoginHandlerFactoryTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\Handler\LoginHandlerFactory
 */
class LoginHandlerFactoryTest extends TestCase
{
    /**
     * @var LoginHandlerFactory $loginHandlerFactory An instance of "LoginHandlerFactory" to test.
     */
    private $loginHandlerFactory;

    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->loginHandlerFactory = new LoginHandlerFactory();
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
                PhpSession::class
            ]
        );

        $handler = ($this->loginHandlerFactory)($this->container);
        $this->assertInstanceOf(LoginHandler::class, $handler);
    }
}
