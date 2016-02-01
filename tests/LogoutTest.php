<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Payload_Interface\PayloadStatus as Status;
use Aura\Auth\Status as AuthStatus;

class LogoutTest extends AbstractServiceTest
{

    public function testSuccess()
    {
        $this->aura->expects($this->once())
            ->method('logout')
            ->with($this->auth, AuthStatus::ANON);

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(AuthStatus::ANON));

        $this->event->expects($this->once())
            ->method('__invoke')
            ->with(
                'jnjxp/vk:logout.success',
                $this->isInstanceOf('Aura\Payload\Payload'),
                $this->service
            );

        $payload = $this->service->__invoke($this->auth);

        $this->assertInstanceOf('Aura\Payload\Payload', $payload);

        $this->assertEquals(Status::SUCCESS, $payload->getStatus());
        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
    }

    public function testUnknown()
    {
        $this->aura->expects($this->once())
            ->method('logout')
            ->with($this->auth, AuthStatus::ANON);

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('foo'));

        $this->event->expects($this->once())
            ->method('__invoke')
            ->with(
                'jnjxp/vk:logout.failure',
                $this->isInstanceOf('Aura\Payload\Payload'),
                $this->service
            );

        $payload = $this->service->__invoke($this->auth);

        $this->assertInstanceOf('Aura\Payload\Payload', $payload);

        $this->assertEquals(Status::ERROR, $payload->getStatus());
        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
        $this->assertInstanceOf('Exception', $payload->getOutput());
    }


    public function testError()
    {
        $exception = new \Exception();
        $this->aura->expects($this->once())
            ->method('logout')
            ->with($this->auth, AuthStatus::ANON)
            ->will($this->throwException($exception));

        $this->event->expects($this->once())
            ->method('__invoke')
            ->with(
                'jnjxp/vk:logout.error',
                $this->isInstanceOf('Aura\Payload\Payload'),
                $this->service
            );

        $payload = $this->service->__invoke($this->auth);

        $this->assertInstanceOf('Aura\Payload\Payload', $payload);

        $this->assertEquals(Status::ERROR, $payload->getStatus());
        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
        $this->assertSame($exception, $payload->getOutput());
    }

}
