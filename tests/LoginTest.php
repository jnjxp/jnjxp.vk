<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk;

use Aura\Payload_Interface\PayloadStatus as Status;

class LoginTest extends AbstractServiceTest
{

    public function testSuccess()
    {
        $this->aura->expects($this->once())
            ->method('login')
            ->with(
                $this->auth,
                $this->equalTo(
                    [
                        'username' => 'un',
                        'password' => 'pw'
                    ]
                )
            );

        $this->auth->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->auth->expects($this->once())
            ->method('getUserName')
            ->will($this->returnValue('un'));

        $this->auth->expects($this->once())
            ->method('getUserData')
            ->will($this->returnValue('data'));

        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadSuccess'])
            ]
        );

        $payload = $this->service->__invoke($this->auth, 'un', 'pw');

        $this->assertPayloadStatus(Status::AUTHENTICATED, $payload);

        $this->assertEquals(
            [
                'username' => 'un',
                'password' => true
            ],
            $payload->getInput()
        );

        $this->assertEquals(
            [
                'username' => 'un',
                'data' => 'data'
            ],
            $payload->getOutput()
        );

        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
    }

    public function testUnknown()
    {
        $this->aura->expects($this->once())
            ->method('login')
            ->with(
                $this->auth,
                $this->equalTo(
                    [
                        'username' => 'un',
                        'password' => 'pw'
                    ]
                )
            );

        $this->auth->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadError'])
            ]
        );

        $payload = $this->service->__invoke($this->auth, 'un', 'pw');

        $this->assertPayloadStatus(Status::ERROR, $payload);

        $this->assertEquals(
            [
                'username' => 'un',
                'password' => true
            ],
            $payload->getInput()
        );
        $this->assertInstanceOf(
            'Exception',
            $payload->getOutput()
        );
        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
    }

    public function testFail()
    {
        $exception = new \Aura\Auth\Exception();

        $this->aura->expects($this->once())
            ->method('login')
            ->with(
                $this->auth,
                $this->equalTo(
                    [
                        'username' => 'un',
                        'password' => 'pw'
                    ]
                )
            )->will($this->throwException($exception));


        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadFailure'])
            ]
        );

        $payload = $this->service->__invoke($this->auth, 'un', 'pw');

        $this->assertPayloadStatus(Status::FAILURE, $payload);

        $this->assertEquals(
            [
                'username' => 'un',
                'password' => true
            ],
            $payload->getInput()
        );
        $this->assertSame($exception, $payload->getOutput());
        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
    }

    public function testError()
    {
        $exception = new \Exception();

        $this->aura->expects($this->once())
            ->method('login')
            ->with(
                $this->auth,
                $this->equalTo(
                    [
                        'username' => 'un',
                        'password' => 'pw'
                    ]
                )
            )->will($this->throwException($exception));


        $this->events(
            [
                $this->callback([$this, 'payloadProcessing']),
                $this->callback([$this, 'payloadError'])
            ]
        );

        $payload = $this->service->__invoke($this->auth, 'un', 'pw');

        $this->assertPayloadStatus(Status::ERROR, $payload);

        $this->assertEquals(
            [
                'username' => 'un',
                'password' => true
            ],
            $payload->getInput()
        );

        $this->assertSame($exception, $payload->getOutput());

        $this->assertEquals(
            ['auth' => $this->auth],
            $payload->getExtras()
        );
    }
}
