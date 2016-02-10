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

        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadSuccess'])
            ]
        );

        $payload = $this->service->__invoke($this->auth);

        $this->assertPayloadStatus(Status::SUCCESS, $payload);

        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );

        $this->assertEquals(AuthStatus::ANON, $payload->getOutput());
    }

    public function testUnknown()
    {
        $this->aura->expects($this->once())
            ->method('logout')
            ->with($this->auth, AuthStatus::ANON);

        $this->auth->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue('foo'));

        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadError'])
            ]
        );

        $payload = $this->service->__invoke($this->auth);

        $this->assertPayloadStatus(Status::ERROR, $payload);

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

        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadError'])
            ]
        );

        $payload = $this->service->__invoke($this->auth);

        $this->assertPayloadStatus(Status::ERROR, $payload);

        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );

        $this->assertSame($exception, $payload->getOutput());
    }

}
