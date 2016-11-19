<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

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

    protected function assertPayloadStatus($expect, $payload)
    {
        $this->assertInstanceOf('Aura\Payload\Payload', $payload);
        $this->assertEquals($expect, $payload->getStatus());
        return true;
    }

    public function payloadProcessing($payload)
    {
        $this->assertPayloadStatus(Status::PROCESSING, $payload);
    }

    public function payloadSuccess($payload)
    {
        $this->assertPayloadStatus(Status::SUCCESS, $payload);
    }

    public function payloadFailure($payload)
    {
        $this->assertPayloadStatus(Status::FAILURE, $payload);
    }

    public function payloadError($payload)
    {
        $this->assertPayloadStatus(Status::Error, $payload);
    }

    protected function events(array $events)
    {
        $params = [];
        foreach ($events as $payload) {
            $params = [$this->service, $payload];
        }

        $method = $this->event->expects($this->exactly(count($events)))
            ->method('__invoke');

        call_user_func_array([$method, 'withConsecutive'], $params);
    }

}
