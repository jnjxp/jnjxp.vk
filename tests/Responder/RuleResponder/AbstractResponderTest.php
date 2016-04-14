<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Responder\RuleResponder;

use Jnjxp\Vk\Responder\AbstractResponderTest as BaseResponderTest;

abstract class AbstractResponderTest extends BaseResponderTest
{
    protected $route;

    public function setUp()
    {
        parent::setUp();
        $this->route = $this->getMockBuilder('Aura\Router\Route')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function routeAuth($bool)
    {
        $this->route->expects($this->once())
            ->method('__get')
            ->with($this->equalTo('auth'))
            ->will($this->returnValue($bool));
    }

}

