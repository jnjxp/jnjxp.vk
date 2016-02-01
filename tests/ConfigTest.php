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
            'Jnjxp\Vk\Config'
        ];
    }

    public function provideGet()
    {
        return [
            ['aura/view:factory', 'Aura\View\ViewFactory'],
            ['aura/view:view', 'Aura\View\View'],
            ['aura/session:factory', 'Aura\Session\SessionFactory'],
            ['aura/session:session', 'Aura\Session\Session'],
            ['aura/session:csrf', 'Aura\Session\CsrfToken'],
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
            ['Jnjxp\Vk\AuthHandler'],
            ['Jnjxp\Vk\Login'],
            ['Jnjxp\Vk\Logout'],
            ['Jnjxp\Vk\Login\Responder'],
            ['Jnjxp\Vk\Logout\Responder'],
            ['Jnjxp\Vk\Login\Input'],
            ['Jnjxp\Vk\Logout\Input'],
            ['Jnjxp\Vk\Router\AuthRule'],
            ['Jnjxp\Vk\Router\CsrfRule'],
        ];
    }
}

