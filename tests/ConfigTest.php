<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Di\AbstractContainerConfigTest;

class ConfigTest extends AbstractContainerConfigTest
{
    protected function setUp()
    {
        @session_start();
        parent::setUp();
    }

    protected function getConfigClasses()
    {
        return [
            'Fusible\AuthProvider\Config',
            'Radar\Adr\Config',
            'Jnjxp\Vk\Config',
        ];
    }

    public function provideGet()
    {
        return [
            ['aura/auth:factory', 'Aura\Auth\AuthFactory'],
            ['aura/auth:auth', 'Aura\Auth\Auth'],
            ['aura/auth:login', 'Aura\Auth\Service\LoginService'],
            ['aura/auth:logout', 'Aura\Auth\Service\LogoutService'],
            ['aura/auth:resume', 'Aura\Auth\Service\ResumeService'],
        ];
    }

    public function provideNewInstance()
    {
        return [
            ['Jnjxp\Vk\Login'],
            ['Jnjxp\Vk\Logout'],
            ['Jnjxp\Vk\Input'],
            ['Jnjxp\Vk\Router\AuthRouteRule'],
            ['Jnjxp\Vk\Responder\AuthResponder'],
            ['Jnjxp\Vk\Responder\AuthResponder\HtmlResponder'],
            ['Jnjxp\Vk\Responder\AuthResponder\JsonResponder'],
            ['Jnjxp\Vk\Responder\RuleResponder'],
            ['Jnjxp\Vk\Responder\RuleResponder\HtmlResponder'],
            ['Jnjxp\Vk\Responder\RuleResponder\JsonResponder'],
        ];
    }

    public function testSettings()
    {
        // todo actually test something
        $config = new Config;
        $this->assertSame($config, $config->setRoute(true));
        $this->assertSame($config, $config->setResponders(true));

        $config = new Router\Config;
        $this->assertSame($config, $config->setRoutePrefix('Action\\Auth\\'));
        $this->assertSame($config, $config->setAddRule(true));
    }
}

