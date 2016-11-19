<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Zend\Diactoros\ServerRequestFactory;

class InputTest extends \PHPUnit_Framework_TestCase
{

    protected $input;

    public function setUp()
    {
        $this->input = new Input; 
    }

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

        $this->input->setAuthAttribute('foo');

        $this->assertEquals(
            [
                'auth' => $auth,
                'username' => 'un',
                'password' => 'pw'
            ],
            $this->input->login($request)
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

        $this->assertEquals(
            ['auth' => $auth],
            $this->input->logout($request)
        );
    }
}
