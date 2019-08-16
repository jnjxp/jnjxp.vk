<?php

namespace Test\Jnjxp\Vk;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\ConfigProvider;
use Zend\Expressive\Application;

/**
 * Class ConfigProviderTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @var ConfigProvider $configProvider An instance of "ConfigProvider" to test.
     */
    private $configProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->configProvider = new ConfigProvider();
    }


    public function testInvocationReturnsArray()
    {
        $config = ($this->configProvider)();
        $this->assertIsArray($config);
        return $config;
    }

    /**
     * @depends testInvocationReturnsArray
     */
    public function testReturnedArrayContainsDependencies(array $config)
    {
        $this->assertArrayHasKey('dependencies', $config);
        $this->assertIsArray($config['dependencies']);
    }

    /**
     * @covers \Jnjxp\Vk\ConfigProvider::registerRoutes
     */
    public function testRegisterRoutes(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }

    public function testRoutes()
    {
        $app = $this->createMock(Application::class);
        $app->expects($this->exactly(2))
            ->method('route');
        $this->configProvider->registerRoutes($app);
    }
}
