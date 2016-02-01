<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Router;

use Zend\Diactoros\ServerRequestFactory;
use Aura\Auth\Status;

class CsrfRuleTest extends \PHPUnit_Framework_TestCase
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

        $this->token = $this->getMockBuilder('Aura\Session\CsrfToken')
            ->disableOriginalConstructor()
            ->getMock();

        $this->request = ServerRequestFactory::fromGlobals();

        $this->rule = new CsrfRule($this->token);
    }

    protected function requireAuth()
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with('auth')
            ->will($this->returnValue(true));
        return $this;
    }

    public function testUnprotected()
    {
        $this->assertTrue(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testValid()
    {
        $this->requireAuth();
        $this->request = $this->request->withMethod('DELETE')
            ->withAttribute('auth', $this->auth)
            ->withParsedBody(['key' => 'asd']);

        $this->rule->setAuthAttribute('auth')
            ->setCsrfKey('key');

        $this->auth->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->token->expects($this->once())
            ->method('isValid')
            ->with('asd')
            ->will($this->returnValue(true));

        $this->assertTrue(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testInvalid()
    {
        $this->requireAuth();
        $this->request = $this->request->withMethod('DELETE')
            ->withAttribute('auth', $this->auth)
            ->withParsedBody(['key' => 'asd']);

        $this->rule->setAuthAttribute('auth')
            ->setCsrfKey('key');

        $this->auth->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->token->expects($this->once())
            ->method('isValid')
            ->with('asd')
            ->will($this->returnValue(false));

        $this->assertFalse(
            $this->rule->__invoke($this->request, $this->route)
        );
    }

    public function testAuthNotAvail()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->requireAuth();
        $this->request = $this->request->withMethod('DELETE');

        $this->rule->__invoke($this->request, $this->route);
    }
}
