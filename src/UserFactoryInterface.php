<?php

declare(strict_types=1);

namespace Jnjxp\Vk;

use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionInterface;

interface UserFactoryInterface
{
    public function __invoke(
        string $identity,
        array $roles = [],
        array $details = []
    ) : UserInterface;

    public function newUser(
        string $identity,
        array $roles = [],
        array $details = []
    ) : UserInterface;

    public function fromSession(SessionInterface $session) : ?UserInterface;
}
