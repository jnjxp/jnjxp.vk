<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Payload\Payload;

abstract class AbstractServiceTest extends \PHPUnit_Framework_TestCase
{
    protected $payload;

    protected $event;

    protected $auth;

    protected $aura;

    protected $service;

    protected $result;

    public function setUp()
    {
        $this->auth = $this->getMockBuilder('Aura\Auth\Auth')
            ->disableOriginalConstructor()
            ->getMock();

        $this->event = $this->getMock('stdClass', ['__invoke']);

        $this->action = substr(get_class($this), 9, -4);

        switch($this->action){
        case 'Login':
            $this->aura = $this->getMockBuilder('Aura\Auth\Service\LoginService')
                ->disableOriginalConstructor()
                ->getMock();

            $this->service = new Login(
                new Payload(),
                $this->aura,
                $this->event
            );
            break;

        case 'Logout':
            $this->aura = $this->getMockBuilder('Aura\Auth\Service\LogoutService')
                ->disableOriginalConstructor()
                ->getMock();

            $this->service = new Logout(
                new Payload(),
                $this->aura,
                $this->event
            );
            break;

        default:
            throw new \Exception('Invalid case');
            break;
        }
    }
}
