<?php

namespace Test\Jnjxp\Vk;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\UserFactory;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;

/**
 * Class UserFactoryTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\UserFactory
 */
class UserFactoryTest extends TestCase
{
    /**
     * @var UserFactory $userFactory An instance of "UserFactory" to test.
     */
    private $userFactory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        /** @todo Maybe add some arguments to this constructor */
        $this->userFactory = new UserFactory();
    }

    /**
     * @covers \Jnjxp\Vk\UserFactory::__invoke
     */
    public function testInvoke(): void
    {
        $user = ($this->userFactory)('ident', ['role'], ['details']);
        $this->assertEquals('ident', $user->getIdentity());
        $this->assertEquals(['role'], $user->getRoles());
        $this->assertEquals(['details'], $user->getDetails());
    }

    public function testEmptySession()
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo(UserInterface::class))
                ->will($this->returnValue(null));

        $this->assertNull($this->userFactory->fromSession($session));
    }

    public function testUserSession()
    {
        $session = $this->createMock(SessionInterface::class);
        $session->expects($this->once())
                ->method('get')
                ->with($this->equalTo(UserInterface::class))
                ->will($this->returnValue([
                    'username' => 'ident',
                    'roles' => ['role'],
                    'details' => ['details']
                ]));
        $user = $this->userFactory->fromSession($session);
        $this->assertEquals('ident', $user->getIdentity());
        $this->assertEquals(['role'], $user->getRoles());
        $this->assertEquals(['details'], $user->getDetails());
    }

    /**
     * @covers \Jnjxp\Vk\UserFactory::fromSession
     */
    public function testFromSession(): void
    {
        /** @todo Complete this unit test method. */
        $this->markTestIncomplete();
    }
}
