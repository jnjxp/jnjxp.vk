<?php

declare(strict_types=1);

namespace Jnjxp\Vk;

use Mezzio\Authentication\DefaultUser;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;

class UserFactory implements UserFactoryInterface
{
    public function __invoke(
        string $identity,
        array $roles = [],
        array $details = []
    ) : UserInterface {
        return $this->newUser($identity, $roles, $details);
    }

    public function newUser(
        string $identity,
        array $roles = [],
        array $details = []
    ) : UserInterface {
        return new DefaultUser($identity, $roles, $details);
    }

    public function fromSession(SessionInterface $session) : ?UserInterface
    {
        $userInfo = $session->get(UserInterface::class);
        if (! is_array($userInfo) || ! isset($userInfo['username'])) {
            return null;
        }
        $roles   = $userInfo['roles'] ?? [];
        $details = $userInfo['details'] ?? [];
        return $this->newUser($userInfo['username'], (array) $roles, (array) $details);
    }
}
