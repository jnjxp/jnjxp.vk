<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Zend\Diactoros\ServerRequestFactory;

class InputTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $auth = $this->getMockBuilder('Aura\Auth\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute('foo', $auth)
            ->withParsedBody(
            [
                'username' => 'un',
                'password' => 'pw'
            ]
        );

        $input = new \Jnjxp\Vk\Login\Input();
        $input->setAuthAttribute('foo');

        $this->assertEquals(
            [
                'auth' => $auth,
                'username' => 'un',
                'password' => 'pw'
            ],
            $input($request)
        );
    }

    public function testLogout()
    {
        $auth = $this->getMockBuilder('Aura\Auth\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute(
            'aura/auth:auth', $auth
        );

        $input = new \Jnjxp\Vk\Logout\Input();

        $this->assertEquals(
            ['auth' => $auth],
            $input($request)
        );
    }
}
