<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Zend\Diactoros\ServerRequestFactory;

class InputTest extends \PHPUnit_Framework_TestCase
{
    public function testLogin()
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute('foo', 'foo')
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
                'auth' => 'foo',
                'username' => 'un',
                'password' => 'pw'
            ],
            $input($request)
        );
    }

    public function testLogout()
    {
        $request = ServerRequestFactory::fromGlobals();
        $request = $request->withAttribute(
            'aura/auth:auth', 'foo'
        );

        $input = new \Jnjxp\Vk\Logout\Input();

        $this->assertEquals(
            ['auth' => 'foo'],
            $input($request)
        );
    }
}
