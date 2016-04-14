<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Router;

use Zend\Diactoros\ServerRequestFactory;
use Aura\Auth\Status;

class AuthRuleTest extends \PHPUnit_Framework_TestCase
{
    protected $route;

    protected $auth;

    protected $request;

    public function setUp()
    {
        $this->route = $this->getMockBuilder('Aura\Router\Route')
            ->disableOriginalConstructor()
            ->getMock();

        $this->auth = $this->getMockBuilder('Aura\Auth\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = ServerRequestFactory::fromGlobals();

        $this->rule = new AuthRouteRule();
    }

    public function testUnprotected()
    {
        $this->assertTrue(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testProtectedAuthed()
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(true));

        $this->request = $this->request->withAttribute(
            'foo', $this->auth
        );

        $this->rule->setAuthAttribute('foo');

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(Status::VALID));

        $this->assertTrue(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testProtectedNotAuthed()
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(true));

        $this->request = $this->request->withAttribute(
            'foo', $this->auth
        );

        $this->rule->setAuthAttribute('foo');

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(Status::ANON));

        $this->assertFalse(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testAnonAuthed()
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(false));

        $this->request = $this->request->withAttribute(
            'foo', $this->auth
        );

        $this->rule->setAuthAttribute('foo');

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(Status::VALID));

        $this->assertFalse(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testAnonAnon()
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(false));

        $this->request = $this->request->withAttribute(
            'foo', $this->auth
        );

        $this->rule->setAuthAttribute('foo');

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(Status::ANON));

        $this->assertTrue(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testAuthNotAvail()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(false));

        $this->rule->__invoke($this->request, $this->route);
    }
}
