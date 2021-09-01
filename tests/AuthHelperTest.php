<?php

namespace Jnjxp\Vk;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Jnjxp\Vk\AuthHelper;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\DefaultUser;

/**
 * Class AuthHelperTest.
 *
 * @link https://github.com/jnjxp/jnjxp.vk
 *
 * @covers \Jnjxp\Vk\AuthHelper
 */
class AuthHelperTest extends TestCase
{
    public function testAuthenticated()
    {
        $user = $this->createMock(UserInterface::class);
        $auth = new AuthHelper($user);
        $this->assertTrue($auth->isAuthenticated());
        $this->assertSame($user, $auth->getUser());
    }

    public function testNotAuthenticated()
    {
        $auth = new AuthHelper();
        $this->assertFalse($auth->isAuthenticated());
        $this->assertNull($auth->getUser());
    }

    public function testNotAuthedNoRoles()
    {
        $auth = new AuthHelper();
        $this->assertFalse($auth->hasRole('foo'));
    }

    public function testHasRole()
    {
        $user = new DefaultUser('ident', ['foo', 'bar']);
        $auth = new AuthHelper($user);
        $this->assertTrue($auth->hasRole('foo'));
        $this->assertTrue($auth->hasRole('bar'));
        $this->assertFalse($auth->hasRole('baz'));
    }
}
